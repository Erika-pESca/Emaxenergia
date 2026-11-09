// Envío de formularios al endpoint PHP (public/api/enviar-formulario.php).
// Lo usan TODOS los formularios de la web: funnel de luz, funnel de gas, contacto,
// popover "Te llamamos gratis" y Canal Directo. No se duplica esta lógica en los componentes.
//
// Reglas:
//  - Los datos de contacto del aviso de error (teléfono y email de src/data/site.ts) los
//    pinta BaseLayout en atributos data-contacto-* del <body>; así no acaban dentro del JS.
//  - Éxito SOLO si la respuesta es 2xx y el JSON trae ok === true. Todo lo demás
//    (error de red, 4xx, 5xx, JSON raro) es fallo: nunca se confirma un envío que no se hizo.
//  - Los destinatarios no se mandan desde el navegador: los decide el servidor.
export const ENDPOINT_FORMULARIOS = "/api/enviar-formulario.php";

export type TipoFormulario =
  | "lead-luz"
  | "lead-gas"
  | "lead-llamada"
  | "lead-canal-directo"
  | "soporte-contacto";

export type ResultadoEnvio =
  | { ok: true }
  | {
      ok: false;
      /** validacion: 422 · limite: 429 · red: sin respuesta o JSON inválido · otro: resto de errores */
      motivo: "validacion" | "limite" | "red" | "otro";
      status: number;
      campos: string[];
    };

// Trampa de tiempo: el servidor recibe "te" = milisegundos desde que el formulario se mostró al
// usuario, medidos aquí con performance.now() (reloj monotónico). Así no depende de la hora del
// dispositivo ni de la del servidor.
const inicios = new Map<TipoFormulario, number>();

/**
 * Marca el instante en que el formulario se vuelve visible para el usuario: al abrir el modal
 * (funnels), al abrir el popover, o al cargar la página (contacto y Canal Directo).
 * Se llama de nuevo cada vez que se vuelve a mostrar: el contador arranca de cero.
 */
export function marcarInicio(tipo: TipoFormulario): void {
  inicios.set(tipo, performance.now());
}

/**
 * Envía el formulario y devuelve el resultado ya interpretado. Añade solo el campo "te"
 * (ms desde `marcarInicio(tipo)`); si nunca se marcó, "te" es 0 y el servidor lo trata como bot.
 */
export async function enviarFormulario(
  tipo: TipoFormulario,
  campos: Record<string, string>,
): Promise<ResultadoEnvio> {
  const inicio = inicios.get(tipo);
  const te = inicio === undefined ? 0 : Math.round(performance.now() - inicio);
  const control = new AbortController();
  const temporizador = setTimeout(() => control.abort(), 15000);
  try {
    const respuesta = await fetch(ENDPOINT_FORMULARIOS, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ...campos, tipo, te, pagina: window.location.pathname }),
      signal: control.signal,
    });

    let json: { ok?: unknown; campos?: unknown } | null = null;
    try {
      json = await respuesta.json();
    } catch {
      json = null;
    }

    if (respuesta.ok && json !== null && json.ok === true) return { ok: true };

    const camposServidor =
      json && Array.isArray(json.campos) ? json.campos.filter((c): c is string => typeof c === "string") : [];
    if (respuesta.status === 422) return { ok: false, motivo: "validacion", status: 422, campos: camposServidor };
    if (respuesta.status === 429) return { ok: false, motivo: "limite", status: 429, campos: [] };
    return { ok: false, motivo: json === null ? "red" : "otro", status: respuesta.status, campos: [] };
  } catch {
    return { ok: false, motivo: "red", status: 0, campos: [] };
  } finally {
    clearTimeout(temporizador);
  }
}

/** Bloquea o libera el botón de envío mientras se envía (evita dobles envíos). */
export function bloquearEnvio(boton: HTMLButtonElement | null, enviando: boolean): void {
  if (!boton) return;
  boton.disabled = enviando;
  boton.setAttribute("aria-busy", enviando ? "true" : "false");
}

function limpiarMarcas(formulario: HTMLElement | null): void {
  formulario?.querySelectorAll("[aria-invalid]").forEach((el) => el.removeAttribute("aria-invalid"));
}

/** Oculta el aviso de error y quita las marcas de campos inválidos. */
export function limpiarErrorEnvio(aviso: HTMLElement | null, formulario: HTMLElement | null = null): void {
  if (aviso) {
    aviso.hidden = true;
    aviso.replaceChildren();
  }
  limpiarMarcas(formulario);
}

/**
 * Muestra un error accesible (role="alert") junto al botón, con el teléfono y el email de
 * contacto de src/data/site.ts. En 422 marca los campos que indica el servidor.
 * No toca lo que el usuario ha escrito: puede reintentar.
 */
export function mostrarErrorEnvio(
  aviso: HTMLElement | null,
  resultado: ResultadoEnvio,
  formulario: HTMLElement | null = null,
): void {
  if (!aviso || resultado.ok) return;
  limpiarMarcas(formulario);
  aviso.replaceChildren();

  if (resultado.motivo === "limite") {
    aviso.append("Demasiados intentos. Prueba de nuevo en unos minutos.");
  } else if (resultado.motivo === "validacion") {
    aviso.append("Revisa los campos marcados: faltan datos o no son válidos.");
    let primero: HTMLElement | null = null;
    for (const nombre of resultado.campos) {
      const campo = formulario?.querySelector<HTMLElement>(`[name="${CSS.escape(nombre)}"]`) ?? null;
      campo?.setAttribute("aria-invalid", "true");
      primero ??= campo;
    }
    primero?.focus();
  } else {
    aviso.append("No hemos podido enviar tu solicitud. Prueba de nuevo o contacta con nosotros: ");
    const { contactoTelHref, contactoTel, contactoEmail } = document.body.dataset;
    const tel = document.createElement("a");
    tel.href = contactoTelHref ?? "";
    tel.textContent = contactoTel ?? "";
    const mail = document.createElement("a");
    mail.href = `mailto:${contactoEmail ?? ""}`;
    mail.textContent = contactoEmail ?? "";
    aviso.append(tel, " · ", mail, ".");
  }
  aviso.hidden = false;
}
