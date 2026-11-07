<?php
/**
 * EMAX Energía · Endpoint de envío de formularios para Hostinger (PHP 8.x + SMTP)
 *
 * REGLAS DE ENRUTAMIENTO DE CORREOS:
 * 1. Si van por SOPORTE o PÁGINA DE CONTACTO (/contacto/, /atencion-al-cliente/):
 *    - Para (To): administracion@emaxenergia.com
 *    - Copia (CC): gerencia@emaxenergia.com, hola@emaxenergia.com
 *
 * 2. Si son LEADS (Estudio de factura Luz, Estudio de factura Gas, Te llamamos gratis, Canal Directo):
 *    - Para (To): gerencia@emaxenergia.com, hola@emaxenergia.com
 *    - Copia (CC): administracion@emaxenergia.com
 *
 * CONFIGURACIÓN SMTP (HOSTINGER O GOOGLE WORKSPACE):
 * - Opción A (Hostinger directo): $SMTP_HOST = 'smtp.hostinger.com'; $SMTP_PORT = 465;
 * - Opción B (Google Workspace):  $SMTP_HOST = 'smtp.gmail.com';     $SMTP_PORT = 465; (usar Contraseña de Aplicación de Google de 16 caracteres)
 * Puedes rellenar $SMTP_PASS aquí directamente antes de subir a Hostinger,
 * o crear un archivo fuera de public_html en ../emax-smtp-config.php que devuelva
 * ['smtp_user' => 'hola@emaxenergia.com', 'smtp_pass' => 'TU_CONTRASENA'].
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
  header('Allow: POST');
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'metodo_no_permitido'], JSON_UNESCAPED_UNICODE);
  exit;
}

// =============================================================================
// 1. DESTINATARIOS OFICIALES EMAX ENERGÍA
// =============================================================================
const CORREO_GERENCIA       = 'gerencia@emaxenergia.com';
const CORREO_ADMINISTRACION = 'administracion@emaxenergia.com';
const CORREO_HOLA           = 'hola@emaxenergia.com';

// =============================================================================
// 2. CONFIGURACIÓN SMTP HOSTINGER
// =============================================================================
$configExterna = [];
$rutaConfigExterna = dirname(__DIR__, 2) . '/emax-smtp-config.php';
if (is_file($rutaConfigExterna)) {
  $cargada = require $rutaConfigExterna;
  if (is_array($cargada)) {
    $configExterna = $cargada;
  }
}

$SMTP_HOST = (string) ($configExterna['smtp_host'] ?? getenv('EMAX_SMTP_HOST') ?: 'smtp.hostinger.com');
$SMTP_PORT = (int)    ($configExterna['smtp_port'] ?? getenv('EMAX_SMTP_PORT') ?: 465);
$SMTP_USER = (string) ($configExterna['smtp_user'] ?? getenv('EMAX_SMTP_USER') ?: CORREO_HOLA);
$SMTP_PASS = (string) ($configExterna['smtp_pass'] ?? getenv('EMAX_SMTP_PASS') ?: '');
$SMTP_FROM_NAME = 'EMAX Energía Web';

// =============================================================================
// 3. LECTURA Y VALIDACIÓN DE DATOS
// =============================================================================
$raw = file_get_contents('php://input') ?: '';
if (strlen($raw) > 128 * 1024) {
  http_response_code(413);
  echo json_encode(['ok' => false, 'error' => 'payload_demasiado_grande'], JSON_UNESCAPED_UNICODE);
  exit;
}

$datos = json_decode($raw, true);
if (!is_array($datos)) {
  $datos = $_POST;
}
if (!is_array($datos) || empty($datos)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'formato_invalido'], JSON_UNESCAPED_UNICODE);
  exit;
}

// Campo trampa antispam ("web"): si viene relleno por un bot, respondemos 200 sin enviar nada
if (!empty($datos['web']) && trim((string) $datos['web']) !== '') {
  http_response_code(200);
  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
  exit;
}

function limpiar_linea(mixed $valor, int $max = 250): string {
  if (!is_scalar($valor)) return '';
  $texto = trim(str_replace(["\r", "\n"], ' ', (string) $valor));
  return mb_substr($texto, 0, $max, 'UTF-8');
}

function limpiar_multilinea(mixed $valor, int $max = 3000): string {
  if (!is_scalar($valor)) return '';
  $texto = trim(str_replace("\r\n", "\n", (string) $valor));
  return mb_substr($texto, 0, $max, 'UTF-8');
}

$tipo = strtolower(limpiar_linea($datos['tipo'] ?? 'lead', 40));

// =============================================================================
// 4. ASIGNACIÓN DE DESTINATARIOS SEGÚN REGLA DE NEGOCIO
// =============================================================================
$esSoporteOContacto = in_array($tipo, ['soporte', 'contacto', 'soporte-contacto', 'atencion-cliente'], true);

if ($esSoporteOContacto) {
  // Si van por soporte o página contacto -> a administracion@emaxenergia.com
  // Copia a gerencia@emaxenergia.com y hola@emaxenergia.com
  $destinatariosPara = [CORREO_ADMINISTRACION];
  $destinatariosCc   = [CORREO_GERENCIA, CORREO_HOLA];
  $prefijoAsunto     = '[Soporte / Contacto Web]';
} else {
  // Si son leads -> a gerencia@emaxenergia.com y a hola@emaxenergia.com
  // Copia a administracion@emaxenergia.com
  $destinatariosPara = [CORREO_GERENCIA, CORREO_HOLA];
  $destinatariosCc   = [CORREO_ADMINISTRACION];
  $prefijoAsunto     = '[Nuevo Lead Web]';
}

$nombre   = limpiar_linea($datos['nombre'] ?? '', 100);
$telefono = limpiar_linea($datos['telefono'] ?? '', 40);
$email    = limpiar_linea($datos['email'] ?? '', 120);
$asuntoUsuario = limpiar_linea($datos['asunto'] ?? $datos['tramo_origen'] ?? $tipo, 120);

$asuntoCorreo = trim("$prefijoAsunto " . ($nombre !== '' ? "$nombre · " : '') . ($telefono !== '' ? "$telefono · " : '') . $asuntoUsuario);

// Construir cuerpo del correo
$lineasCuerpo = [
  '==========================================================',
  '  EMAX ENERGÍA · NOTIFICACIÓN DE FORMULARIO WEB',
  '==========================================================',
  '',
  'Tipo de formulario : ' . $tipo,
  'Para (To)          : ' . implode(', ', $destinatariosPara),
  'Copia (CC)         : ' . implode(', ', $destinatariosCc),
  'Fecha y hora (UTC) : ' . gmdate('Y-m-d H:i:s') . ' UTC',
  'Página de origen   : ' . limpiar_linea($datos['pagina'] ?? ($_SERVER['HTTP_REFERER'] ?? '—'), 250),
  '',
  '----------------------------------------------------------',
  '  DATOS ENVIADOS POR EL USUARIO',
  '----------------------------------------------------------',
];

$camposIgnorados = ['web', 'para', 'cc'];
foreach ($datos as $clave => $valor) {
  if (in_array($clave, $camposIgnorados, true)) continue;
  if (is_array($valor)) {
    $valorTexto = json_encode($valor, JSON_UNESCAPED_UNICODE);
  } else {
    $valorTexto = limpiar_multilinea($valor, 2500);
  }
  if ($valorTexto === '') continue;
  $etiqueta = ucfirst(str_replace(['_', '-'], ' ', (string) $clave));
  $lineasCuerpo[] = str_pad($etiqueta, 20, ' ') . ': ' . $valorTexto;
}

$lineasCuerpo[] = '';
$lineasCuerpo[] = '--';
$lineasCuerpo[] = 'EMAX Energía · https://emaxenergia.com';
$cuerpoTexto = implode("\r\n", $lineasCuerpo);

$replyTo = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? $email : CORREO_HOLA;

// =============================================================================
// 5. ENVÍO POR SMTP DE HOSTINGER (O FALLBACK A mail() DE PHP)
// =============================================================================
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
  $codigo = (int) substr($resp, 0, 3);
  return in_array($codigo, $codigosEsperados, true);
}

function enviar_por_smtp_hostinger(
  string $host,
  int $port,
  string $user,
  string $pass,
  string $fromName,
  array $para,
  array $cc,
  string $replyTo,
  string $asunto,
  string $cuerpo
): bool {
  $remoto = ($port === 465 ? 'ssl://' : '') . $host . ':' . $port;
  $errno = 0;
  $errstr = '';
  $socket = @stream_socket_client($remoto, $errno, $errstr, 12, STREAM_CLIENT_CONNECT);
  if (!$socket) {
    error_log("EMAX SMTP error de conexión ($remoto): $errstr ($errno)");
    return false;
  }
  stream_set_timeout($socket, 12);

  $saludo = smtp_leer($socket);
  if ((int) substr($saludo, 0, 3) !== 220) {
    fclose($socket);
    return false;
  }

  $dominioLocal = 'emaxenergia.com';
  if (!smtp_comando($socket, "EHLO $dominioLocal", [250])) {
    fclose($socket);
    return false;
  }

  if ($port === 587) {
    if (!smtp_comando($socket, 'STARTTLS', [220])) {
      fclose($socket);
      return false;
    }
    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
      fclose($socket);
      return false;
    }
    if (!smtp_comando($socket, "EHLO $dominioLocal", [250])) {
      fclose($socket);
      return false;
    }
  }

  if (!smtp_comando($socket, 'AUTH LOGIN', [334])) {
    fclose($socket);
    return false;
  }
  if (!smtp_comando($socket, base64_encode($user), [334])) {
    fclose($socket);
    return false;
  }
  if (!smtp_comando($socket, base64_encode($pass), [235])) {
    error_log('EMAX SMTP: fallo de autenticación para ' . $user);
    fclose($socket);
    return false;
  }

  if (!smtp_comando($socket, "MAIL FROM:<$user>", [250])) {
    fclose($socket);
    return false;
  }

  $todosLosDestinos = array_unique(array_merge($para, $cc));
  foreach ($todosLosDestinos as $dest) {
    if (!smtp_comando($socket, "RCPT TO:<$dest>", [250, 251])) {
      fclose($socket);
      return false;
    }
  }

  if (!smtp_comando($socket, 'DATA', [354])) {
    fclose($socket);
    return false;
  }

  $cabeceras = [
    'Date: ' . date('r'),
    'From: =?UTF-8?B?' . base64_encode($fromName) . "?= <$user>",
    'To: ' . implode(', ', $para),
    'Cc: ' . implode(', ', $cc),
    'Reply-To: ' . $replyTo,
    'Subject: =?UTF-8?B?' . base64_encode($asunto) . '?=',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
  ];

  $cuerpoEscapado = preg_replace('/^\./m', '..', $cuerpo);
  $mensajeCompleto = implode("\r\n", $cabeceras) . "\r\n\r\n" . $cuerpoEscapado . "\r\n.";

  if (!smtp_comando($socket, $mensajeCompleto, [250])) {
    fclose($socket);
    return false;
  }

  smtp_comando($socket, 'QUIT', [221, 250]);
  fclose($socket);
  return true;
}

$enviado = false;
if ($SMTP_PASS !== '') {
  $enviado = enviar_por_smtp_hostinger(
    $SMTP_HOST,
    $SMTP_PORT,
    $SMTP_USER,
    $SMTP_PASS,
    $SMTP_FROM_NAME,
    $destinatariosPara,
    $destinatariosCc,
    $replyTo,
    $asuntoCorreo,
    $cuerpoTexto
  );
}

// Respaldo con mail() nativo de Hostinger si aún no se ha puesto la contraseña SMTP
if (!$enviado) {
  $cabecerasMail = implode("\r\n", [
    'From: =?UTF-8?B?' . base64_encode($SMTP_FROM_NAME) . '?= <' . $SMTP_USER . '>',
    'Cc: ' . implode(', ', $destinatariosCc),
    'Reply-To: ' . $replyTo,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
  ]);
  $enviado = @mail(
    implode(', ', $destinatariosPara),
    '=?UTF-8?B?' . base64_encode($asuntoCorreo) . '?=',
    $cuerpoTexto,
    $cabecerasMail,
    '-f' . $SMTP_USER
  );
}

http_response_code(200);
echo json_encode([
  'ok' => true,
  'enviado' => $enviado,
  'para' => $destinatariosPara,
  'cc' => $destinatariosCc,
], JSON_UNESCAPED_UNICODE);

