<?php
/**
 * EMAX Energía · Endpoint de envío de formularios (Hostinger, PHP 8.x).
 *
 * Recibe un POST JSON de los formularios de la web y lo envía por correo con
 * Resend (API HTTPS) o por SMTP. Toda la configuración (credenciales,
 * destinatarios, orígenes permitidos, límites) está en UN archivo fuera de
 * public_html: dirname(__DIR__, 2) . '/emax-forms-config.php'.
 * Ver config/emax-forms-config.example.php.
 *
 * Respuestas (siempre JSON, sin detalles internos):
 *   200 {"ok":true}                                  enviado (o descartado por bot)
 *   400 {"ok":false,"error":"formato|tipo"}          JSON inválido o tipo desconocido
 *   403 {"ok":false,"error":"origen"}                Origin/Referer no permitido
 *   405 {"ok":false,"error":"metodo_no_permitido"}   no es POST
 *   413 {"ok":false,"error":"payload_demasiado_grande"}
 *   422 {"ok":false,"error":"validacion","campos":[...]}
 *   429 {"ok":false,"error":"limite"}                demasiados envíos desde esa IP
 *   502 {"ok":false,"error":"envio"}                 falló el transporte de correo
 *   503 {"ok":false,"error":"configuracion"}         falta el archivo de configuración o un dato
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function responder(int $codigo, array $cuerpo): void {
  http_response_code($codigo);
  echo json_encode($cuerpo, JSON_UNESCAPED_UNICODE);
  exit;
}

function fallo(int $codigo, string $error, array $extra = []): void {
  responder($codigo, ['ok' => false, 'error' => $error] + $extra);
}

// =============================================================================
// 1. MÉTODO
// =============================================================================
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  header('Allow: POST');
  fallo(405, 'metodo_no_permitido');
}

// =============================================================================
// 2. CONFIGURACIÓN EXTERNA (única fuente de credenciales y destinatarios)
// =============================================================================
function cargar_configuracion(): ?array {
  $ruta = dirname(__DIR__, 2) . '/emax-forms-config.php';
  if (!is_file($ruta)) return null;
  $config = (static function (string $archivo) { return require $archivo; })($ruta);
  if (!is_array($config)) return null;

  $texto = static fn($v): bool => is_string($v) && trim($v) !== '' && !str_contains($v, 'CAMBIAR');

  $transport = $config['transport'] ?? null;
  if (!in_array($transport, ['resend', 'smtp'], true)) return null;
  if (!$texto($config['from'] ?? null) || parse_direccion($config['from']) === null) return null;
  if ($transport === 'resend' && !$texto($config['resend_api_key'] ?? null)) return null;
  if ($transport === 'smtp') {
    foreach (['smtp_host', 'smtp_user', 'smtp_pass'] as $k) {
      if (!$texto($config[$k] ?? null)) return null;
    }
    if ((int) ($config['smtp_port'] ?? 0) <= 0) return null;
  }

  $origenes = $config['allowed_origins'] ?? null;
  if (!is_array($origenes) || $origenes === []) return null;

  $routing = $config['routing'] ?? null;
  if (!is_array($routing) || $routing === []) return null;
  foreach ($routing as $entrada) {
    if (!is_array($entrada) || empty($entrada['to']) || !is_array($entrada['to'])) return null;
    foreach (array_merge($entrada['to'], $entrada['cc'] ?? []) as $dir) {
      if (!is_string($dir) || !filter_var($dir, FILTER_VALIDATE_EMAIL)) return null;
    }
  }

  $limite = $config['rate_limit'] ?? null;
  if (!is_array($limite) || (int) ($limite['max'] ?? 0) <= 0 || (int) ($limite['window'] ?? 0) <= 0) return null;

  if (!$texto($config['storage_dir'] ?? null)) return null;
  if (!$texto($config['ip_salt'] ?? null)) return null;

  return $config;
}

/** Extrae la dirección de "Nombre <correo@dominio>" o de "correo@dominio". */
function parse_direccion(string $valor): ?string {
  if (preg_match('/<([^<>\s]+)>\s*$/', $valor, $m)) $valor = $m[1];
  $valor = trim($valor);
  return filter_var($valor, FILTER_VALIDATE_EMAIL) ? $valor : null;
}

$config = cargar_configuracion();
if ($config === null) {
  error_log('EMAX formularios: falta emax-forms-config.php o un dato obligatorio');
  fallo(503, 'configuracion');
}

// =============================================================================
// 3. ORIGEN (Origin o, si falta, host del Referer)
// =============================================================================
function origen_de_la_peticion(): ?string {
  $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
  if (is_string($origin) && $origin !== '' && $origin !== 'null') {
    return rtrim(strtolower($origin), '/');
  }
  $referer = $_SERVER['HTTP_REFERER'] ?? '';
  if (is_string($referer) && $referer !== '') {
    $p = parse_url($referer);
    if (is_array($p) && isset($p['scheme'], $p['host'])) {
      return strtolower($p['scheme'] . '://' . $p['host'] . (isset($p['port']) ? ':' . $p['port'] : ''));
    }
  }
  return null;
}

$origenPeticion = origen_de_la_peticion();
$permitidos = array_map(static fn($o) => rtrim(strtolower((string) $o), '/'), $config['allowed_origins']);
if ($origenPeticion === null || !in_array($origenPeticion, $permitidos, true)) {
  fallo(403, 'origen');
}

// =============================================================================
// 4. LECTURA DEL JSON
// =============================================================================
$raw = file_get_contents('php://input') ?: '';
if (strlen($raw) > 32 * 1024) {
  fallo(413, 'payload_demasiado_grande');
}
$datos = json_decode($raw, true);
if (!is_array($datos) || $datos === [] || array_keys($datos) === range(0, count($datos) - 1)) {
  fallo(400, 'formato');
}

// =============================================================================
// 5. TIPOS, CAMPOS PERMITIDOS Y OBLIGATORIOS
//    Lista blanca según lo que envía hoy cada formulario. Cualquier otro campo se descarta.
//    'campos'      : clave => [etiqueta legible, longitud máxima]
//    'obligatorios': claves que tienen que venir rellenas
// =============================================================================
const CAMPOS_COMUNES = [
  'pagina' => ['Página de origen', 200],
];

const TIPOS = [
  // Funnel de luz (Formulario.astro)
  'lead-luz' => [
    'campos' => [
      'nombre' => ['Nombre o empresa', 100],
      'telefono' => ['Teléfono', 25],
      'franja' => ['Franja para llamar', 20],
      'suministros' => ['Suministros o sedes', 40],
      'canal' => ['Canal elegido', 40],
      'tramo_origen' => ['Origen / tramo', 200],
    ],
    'obligatorios' => ['nombre', 'telefono'],
  ],
  // Funnel de gas (FormularioGas.astro)
  'lead-gas' => [
    'campos' => [
      'nombre' => ['Nombre o empresa', 100],
      'telefono' => ['Teléfono', 25],
      'franja' => ['Franja para llamar', 20],
      'segmento' => ['Segmento', 40],
      'gasto_mensual' => ['Gasto mensual', 60],
      'suministros' => ['Suministros o sedes', 40],
      'canal' => ['Canal elegido', 40],
      'tramo_origen' => ['Origen / tramo', 200],
    ],
    'obligatorios' => ['nombre', 'telefono'],
  ],
  // Popover "Te llamamos gratis" (Header.astro)
  'lead-llamada' => [
    'campos' => [
      'telefono' => ['Teléfono', 25],
    ],
    'obligatorios' => ['telefono'],
  ],
  // Canal Directo (canal-directo/index.astro): el formulario exige los seis
  'lead-canal-directo' => [
    'campos' => [
      'nombre' => ['Nombre', 100],
      'empresa' => ['Empresa o despacho', 120],
      'perfil' => ['Actividad', 80],
      'provincia' => ['Provincia', 80],
      'email' => ['Email', 120],
      'telefono' => ['Teléfono', 25],
    ],
    'obligatorios' => ['nombre', 'empresa', 'perfil', 'provincia', 'email', 'telefono'],
  ],
  // Contacto (HablaConNosotros.astro): el formulario exige nombre, email y mensaje
  'soporte-contacto' => [
    'campos' => [
      'nombre' => ['Nombre', 100],
      'email' => ['Email', 120],
      'asunto' => ['Asunto', 150],
      'mensaje' => ['Mensaje', 3000],
      'telefono' => ['Teléfono', 25],
    ],
    'obligatorios' => ['nombre', 'email', 'mensaje'],
  ],
];

$tipo = $datos['tipo'] ?? null;
if (!is_string($tipo) || !isset(TIPOS[$tipo]) || !isset($config['routing'][$tipo])) {
  fallo(400, 'tipo');
}
$esquema = TIPOS[$tipo];
$ruta = $config['routing'][$tipo];

// =============================================================================
// 6. RATE LIMIT POR IP (archivo por sha256(ip + sal); nunca la IP en claro)
// =============================================================================
function limite_excedido(array $config): bool {
  $dir = rtrim((string) $config['storage_dir'], '/\\');
  if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
    error_log('EMAX formularios: no se puede crear storage_dir');
    fallo(503, 'configuracion');
  }
  if (!is_writable($dir)) {
    error_log('EMAX formularios: storage_dir no es escribible');
    fallo(503, 'configuracion');
  }

  $ventana = (int) $config['rate_limit']['window'];
  $maximo = (int) $config['rate_limit']['max'];
  $ahora = time();

  // Limpieza de entradas vencidas (sin tocar el archivo de lock de otros procesos en uso)
  foreach (glob($dir . '/rl_*.json') ?: [] as $archivo) {
    $mtime = @filemtime($archivo);
    if ($mtime !== false && $mtime < $ahora - $ventana) @unlink($archivo);
  }

  $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
  $archivo = $dir . '/rl_' . hash('sha256', $ip . (string) $config['ip_salt']) . '.json';

  $f = @fopen($archivo, 'c+');
  if ($f === false) {
    error_log('EMAX formularios: no se puede abrir el archivo de límite');
    fallo(503, 'configuracion');
  }
  flock($f, LOCK_EX);
  $contenido = stream_get_contents($f);
  $marcas = json_decode($contenido !== false && $contenido !== '' ? $contenido : '[]', true);
  $marcas = is_array($marcas) ? array_values(array_filter($marcas, static fn($t) => is_int($t) && $t > $ahora - $ventana)) : [];

  $excedido = count($marcas) >= $maximo;
  if (!$excedido) $marcas[] = $ahora;

  ftruncate($f, 0);
  rewind($f);
  fwrite($f, json_encode($marcas));
  fflush($f);
  flock($f, LOCK_UN);
  fclose($f);
  return $excedido;
}

// =============================================================================
// 7. ANTI-SPAM: honeypot "web" y trampa de tiempo "te"
//    Si salta, se responde 200 {"ok":true} sin enviar nada (el bot no aprende nada).
// =============================================================================
function es_bot(array $datos): bool {
  if (isset($datos['web']) && is_scalar($datos['web']) && trim((string) $datos['web']) !== '') return true;

  // "te" = milisegundos que el formulario llevaba visible, medidos en el navegador con
  // performance.now(). No se compara con la hora del servidor ni con el reloj del cliente.
  $te = $datos['te'] ?? null;
  return !is_int($te) || $te < 3000;   // falta, no es un entero o se envió en menos de 3 s
}

if (es_bot($datos)) {
  // Cuenta para el límite: un bot que insiste acaba en 429
  limite_excedido($config);
  responder(200, ['ok' => true]);
}

// =============================================================================
// 8. VALIDACIÓN Y SANEADO (solo campos de la lista blanca)
// =============================================================================
function limpiar_valor(mixed $valor, int $max, bool $multilinea): string {
  if (!is_scalar($valor) || is_bool($valor)) return '';
  $texto = (string) $valor;
  $texto = str_replace("\r\n", "\n", $texto);
  if (!$multilinea) $texto = str_replace(["\r", "\n", "\t"], ' ', $texto);
  $texto = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $texto) ?? '';
  return mb_substr(trim($texto), 0, $max, 'UTF-8');
}

$permitidosCampos = $esquema['campos'] + CAMPOS_COMUNES;
$limpios = [];
foreach ($permitidosCampos as $clave => [$etiqueta, $max]) {
  if (!array_key_exists($clave, $datos)) continue;
  $valor = limpiar_valor($datos[$clave], $max, $clave === 'mensaje');
  if ($valor !== '') $limpios[$clave] = $valor;
}

$invalidos = [];
foreach ($esquema['obligatorios'] as $clave) {
  if (!isset($limpios[$clave])) $invalidos[] = $clave;
}
if (isset($limpios['email']) && !filter_var($limpios['email'], FILTER_VALIDATE_EMAIL)) $invalidos[] = 'email';
if (isset($limpios['telefono']) && (
  !preg_match('/^[0-9+()\s.\-]+$/', $limpios['telefono']) || strlen(preg_replace('/\D/', '', $limpios['telefono']) ?? '') < 6
)) {
  $invalidos[] = 'telefono';
}
if ($invalidos !== []) {
  fallo(422, 'validacion', ['campos' => array_values(array_unique($invalidos))]);
}

// La página de origen solo se acepta como ruta del propio sitio
if (isset($limpios['pagina']) && !preg_match('#^/[A-Za-z0-9/_\-.%]*$#', $limpios['pagina'])) {
  unset($limpios['pagina']);
}

// =============================================================================
// 9. LÍMITE DE ENVÍOS POR IP (solo cuentan los envíos que llegan hasta aquí)
// =============================================================================
if (limite_excedido($config)) {
  fallo(429, 'limite');
}

// =============================================================================
// 10. CORREO
// =============================================================================
$destinatarios = array_values(array_unique($ruta['to']));
$copia = array_values(array_diff(array_unique($ruta['cc'] ?? []), $destinatarios));
$replyTo = (isset($limpios['email'])) ? $limpios['email'] : null; // ya validado arriba
$prefijo = limpiar_valor($ruta['prefijo'] ?? '[Web]', 80, false);

$asunto = trim($prefijo . ' ' . ($limpios['nombre'] ?? '') . (isset($limpios['nombre'], $limpios['telefono']) ? ' · ' : '') . ($limpios['telefono'] ?? ''));
if (!isset($limpios['nombre']) && !isset($limpios['telefono'])) $asunto = trim($prefijo . ' ' . ($limpios['email'] ?? $tipo));
if (isset($limpios['asunto'])) $asunto .= ' · ' . $limpios['asunto'];
$asunto = mb_substr($asunto, 0, 200, 'UTF-8');

$lineas = [
  'EMAX ENERGÍA · NOTIFICACIÓN DE FORMULARIO WEB',
  str_repeat('=', 46),
  '',
  'Formulario         : ' . $tipo,
  'Fecha y hora (UTC) : ' . gmdate('Y-m-d H:i:s') . ' UTC',
  '',
];
foreach ($permitidosCampos as $clave => [$etiqueta, $max]) {
  if (!isset($limpios[$clave])) continue;
  if ($clave === 'mensaje') {
    $lineas[] = $etiqueta . ':';
    $lineas[] = $limpios[$clave];
  } else {
    $lineas[] = str_pad($etiqueta, 20) . ': ' . $limpios[$clave];
  }
}
$lineas[] = '';
$lineas[] = '--';
$lineas[] = 'EMAX Energía · formulario web';
// Saltos de línea siempre CRLF (también dentro del mensaje del usuario)
$cuerpo = preg_replace('/\r\n|\r|\n/', "\r\n", implode("\n", $lineas)) ?? '';

// ---- Transporte 1: Resend (API HTTPS) ---------------------------------------
function enviar_por_resend(array $config, array $para, array $cc, ?string $replyTo, string $asunto, string $cuerpo): bool {
  $carga = [
    'from' => $config['from'],
    'to' => $para,
    'subject' => $asunto,
    'text' => $cuerpo,
  ];
  if ($cc !== []) $carga['cc'] = $cc;
  if ($replyTo !== null) $carga['reply_to'] = $replyTo;

  $ch = curl_init('https://api.resend.com/emails');
  if ($ch === false) {
    error_log('EMAX Resend: curl no disponible');
    return false;
  }
  curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
      'Authorization: Bearer ' . $config['resend_api_key'],
      'Content-Type: application/json',
    ],
    CURLOPT_POSTFIELDS => json_encode($carga, JSON_UNESCAPED_UNICODE),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 10,
  ]);
  $respuesta = curl_exec($ch);
  $codigo = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
  $errorCurl = curl_error($ch);
  curl_close($ch);

  if ($respuesta === false) {
    error_log('EMAX Resend: error de red (' . $errorCurl . ')');
    return false;
  }
  $json = json_decode((string) $respuesta, true);
  if ($codigo >= 200 && $codigo < 300 && is_array($json) && !empty($json['id'])) {
    return true;
  }
  // Solo el código HTTP y el mensaje de error de Resend: nunca datos del formulario
  $mensaje = is_array($json) ? (string) ($json['message'] ?? $json['name'] ?? '') : '';
  error_log('EMAX Resend: HTTP ' . $codigo . ' ' . mb_substr($mensaje, 0, 300, 'UTF-8'));
  return false;
}

// ---- Transporte 2: SMTP -----------------------------------------------------
function smtp_leer($socket): string {
  $respuesta = '';
  while ($linea = fgets($socket, 515)) {
    $respuesta .= $linea;
    if (isset($linea[3]) && $linea[3] === ' ') break;
  }
  return $respuesta;
}

function smtp_comando($socket, string $cmd, array $codigosEsperados = [250]): bool {
  fwrite($socket, $cmd . "\r\n");
  $resp = smtp_leer($socket);
  return in_array((int) substr($resp, 0, 3), $codigosEsperados, true);
}

function enviar_por_smtp(array $config, array $para, array $cc, ?string $replyTo, string $asunto, string $cuerpo): bool {
  $host = (string) $config['smtp_host'];
  $port = (int) $config['smtp_port'];
  $user = (string) $config['smtp_user'];
  $pass = (string) $config['smtp_pass'];
  $desde = (string) $config['from'];
  $desdeDireccion = parse_direccion($desde) ?? $user;

  $remoto = ($port === 465 ? 'ssl://' : '') . $host . ':' . $port;
  $errno = 0;
  $errstr = '';
  $socket = @stream_socket_client($remoto, $errno, $errstr, 10, STREAM_CLIENT_CONNECT);
  if (!$socket) {
    error_log("EMAX SMTP: error de conexión ($errno)");
    return false;
  }
  stream_set_timeout($socket, 10);

  $cerrar = static function () use ($socket): bool {
    fclose($socket);
    return false;
  };

  if ((int) substr(smtp_leer($socket), 0, 3) !== 220) return $cerrar();
  $dominioLocal = (string) (parse_url('https://' . substr($desdeDireccion, (int) strrpos($desdeDireccion, '@') + 1), PHP_URL_HOST) ?: 'localhost');
  if (!smtp_comando($socket, "EHLO $dominioLocal")) return $cerrar();

  if ($port === 587) {
    if (!smtp_comando($socket, 'STARTTLS', [220])) return $cerrar();
    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) return $cerrar();
    if (!smtp_comando($socket, "EHLO $dominioLocal")) return $cerrar();
  }

  if (!smtp_comando($socket, 'AUTH LOGIN', [334])) return $cerrar();
  if (!smtp_comando($socket, base64_encode($user), [334])) return $cerrar();
  if (!smtp_comando($socket, base64_encode($pass), [235])) {
    error_log('EMAX SMTP: fallo de autenticación');
    return $cerrar();
  }

  if (!smtp_comando($socket, "MAIL FROM:<$user>")) return $cerrar();
  foreach (array_unique(array_merge($para, $cc)) as $destino) {
    if (!smtp_comando($socket, "RCPT TO:<$destino>", [250, 251])) return $cerrar();
  }
  if (!smtp_comando($socket, 'DATA', [354])) return $cerrar();

  $cabeceras = [
    'Date: ' . date('r'),
    'From: ' . $desde,
    'To: ' . implode(', ', $para),
  ];
  if ($cc !== []) $cabeceras[] = 'Cc: ' . implode(', ', $cc);
  if ($replyTo !== null) $cabeceras[] = 'Reply-To: ' . $replyTo;
  $cabeceras[] = 'Subject: =?UTF-8?B?' . base64_encode($asunto) . '?=';
  $cabeceras[] = 'MIME-Version: 1.0';
  $cabeceras[] = 'Content-Type: text/plain; charset=UTF-8';
  $cabeceras[] = 'Content-Transfer-Encoding: quoted-printable';

  // Quoted-printable: ninguna línea supera los 76 caracteres (límite de 998 de SMTP)
  $cuerpoQp = quoted_printable_encode($cuerpo);
  $cuerpoQp = preg_replace('/^\./m', '..', $cuerpoQp) ?? $cuerpoQp;

  if (!smtp_comando($socket, implode("\r\n", $cabeceras) . "\r\n\r\n" . $cuerpoQp . "\r\n.")) return $cerrar();

  smtp_comando($socket, 'QUIT', [221, 250]);
  fclose($socket);
  return true;
}

// ---- Elección del transporte (sin respaldo: si falla, el envío falló) --------
$enviado = match ($config['transport']) {
  'resend' => enviar_por_resend($config, $destinatarios, $copia, $replyTo, $asunto, $cuerpo),
  'smtp' => enviar_por_smtp($config, $destinatarios, $copia, $replyTo, $asunto, $cuerpo),
};

if (!$enviado) {
  fallo(502, 'envio');
}
responder(200, ['ok' => true]);
