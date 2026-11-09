<?php
/**
 * EMAX Energía · Configuración del endpoint de formularios (ejemplo, SIN secretos).
 *
 * Cómo usarlo en Hostinger:
 *   1. Copia este archivo como  emax-forms-config.php  FUERA de public_html.
 *      El endpoint (public_html/api/enviar-formulario.php) lo busca en
 *      dirname(__DIR__, 2) . '/emax-forms-config.php', es decir, dos niveles por
 *      encima de la carpeta api/. Si el sitio está en
 *      /home/usuario/domains/emaxenergia.com/public_html/api/, el archivo va en
 *      /home/usuario/domains/emaxenergia.com/emax-forms-config.php
 *   2. Rellena los valores. Este archivo NUNCA se sube al repositorio con datos reales.
 *   3. Permisos recomendados: 600 (solo el propietario lo lee).
 *
 * Todos los destinatarios y credenciales viven aquí y solo aquí. Si el archivo no
 * existe o falta un dato obligatorio, el endpoint responde 503 {"ok":false,"error":"configuracion"}
 * y no intenta enviar nada.
 */
return [
    // Transporte de envío: 'resend' (API HTTPS, recomendado) o 'smtp'.
    // Si el transporte configurado falla, el envío falla (no hay respaldo con mail()).
    'transport' => 'resend',

    // Resend: clave de API (empieza por "re_"). Obligatoria si transport = 'resend'.
    'resend_api_key' => 're_CAMBIAR_POR_LA_CLAVE_REAL',

    // Remitente. El dominio tiene que estar verificado en Resend (SPF/DKIM).
    'from' => 'EMAX Energía Web <noreply@emaxenergia.com>',

    // SMTP (solo si transport = 'smtp'). Puerto 465 (SSL) o 587 (STARTTLS).
    'smtp_host' => 'smtp.hostinger.com',
    'smtp_port' => 465,
    'smtp_user' => 'noreply@emaxenergia.com',
    'smtp_pass' => 'CAMBIAR_POR_LA_CONTRASENA',

    // Orígenes desde los que se aceptan envíos (esquema + host, sin barra final).
    // Se compara con la cabecera Origin o, si falta, con el Referer.
    // Durante la revisión: el .es. Al publicar el .com, añadir su origen (o dejar ambos).
    'allowed_origins' => [
        'https://emaxenergia.com',
        'https://www.emaxenergia.com',
        'https://emaxenergia.es',
        'https://www.emaxenergia.es',
    ],

    // Destinatarios por tipo de formulario. SOLO los tipos que aparecen aquí se aceptan.
    //   to / cc  : direcciones (Google Workspace)
    //   prefijo  : (opcional) texto que se antepone al asunto
    'routing' => [
        // Soporte y página de contacto
        'soporte-contacto' => [
            'to' => ['administracion@emaxenergia.com'],
            'cc' => ['gerencia@emaxenergia.com', 'hola@emaxenergia.com'],
            'prefijo' => '[Soporte / Contacto Web]',
        ],
        // Leads comerciales
        'lead-luz' => [
            'to' => ['gerencia@emaxenergia.com', 'hola@emaxenergia.com'],
            'cc' => ['administracion@emaxenergia.com'],
            'prefijo' => '[Nuevo Lead Web · Luz]',
        ],
        'lead-gas' => [
            'to' => ['gerencia@emaxenergia.com', 'hola@emaxenergia.com'],
            'cc' => ['administracion@emaxenergia.com'],
            'prefijo' => '[Nuevo Lead Web · Gas]',
        ],
        'lead-llamada' => [
            'to' => ['gerencia@emaxenergia.com', 'hola@emaxenergia.com'],
            'cc' => ['administracion@emaxenergia.com'],
            'prefijo' => '[Nuevo Lead Web · Te llamamos]',
        ],
        'lead-canal-directo' => [
            'to' => ['gerencia@emaxenergia.com', 'hola@emaxenergia.com'],
            'cc' => ['administracion@emaxenergia.com'],
            'prefijo' => '[Nuevo Lead Web · Canal Directo]',
        ],
    ],

    // Límite de envíos por IP: 'max' envíos cada 'window' segundos. Al superarlo, 429.
    'rate_limit' => [
        'max' => 5,
        'window' => 600,
    ],

    // Carpeta para el límite de envíos (un archivo por hash de IP, nunca la IP en claro).
    // Tiene que estar FUERA de public_html y ser escribible por PHP (permisos 700).
    // Si no existe, el endpoint intenta crearla.
    'storage_dir' => __DIR__ . '/emax-forms-storage',

    // Sal para el hash de la IP. Cadena larga y aleatoria (p. ej. `openssl rand -hex 32`).
    // Si se cambia, se reinician los contadores.
    'ip_salt' => 'CAMBIAR_POR_UNA_CADENA_ALEATORIA_LARGA',
];
