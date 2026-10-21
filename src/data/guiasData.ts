// Datos estructurados de las 16 Guías de Luz para Empresas · EMAX Energía
// Copys oficiales y arquitectura de contenido editorial humanizado

export interface GuiaItem {
  slug: string;
  href: string;
  titulo: string;
  h1: string;
  descripcion: string;
  situacion?: string;
  tema: "Tu factura" | "Potencia" | "Tu instalación" | "Tu contrato" | "Tus datos" | "Diccionario";
  tiempoLectura: string;
  normativa: string;
  fechaRevision: string;
}

export const guiasData: GuiaItem[] = [
  {
    slug: "bajar-factura-luz-empresa",
    href: "/guias/bajar-factura-luz-empresa",
    titulo: "Cómo bajar la factura de luz de tu empresa",
    h1: "Cómo bajar la factura de luz de tu empresa: por dónde empezar",
    descripcion: "Bajar la factura de luz de una empresa no empieza por el kWh. Las cinco palancas, en orden: potencia, excesos y reactiva, horarios, modalidad y eficiencia.",
    situacion: "Quiero pagar menos de luz y no sé por dónde empezar",
    tema: "Tu factura",
    tiempoLectura: "5 min",
    normativa: "Circular 3/2020 CNMC",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "factura-luz-empresa",
    href: "/guias/factura-luz-empresa",
    titulo: "Cómo leer la factura de luz de tu empresa",
    h1: "Cómo leer la factura de luz de tu empresa, concepto a concepto",
    descripcion: "Qué es cada concepto de una factura 3.0TD, qué depende de cómo compras la energía, qué se ajusta con datos y qué no depende de nadie. Con un ejemplo anotado.",
    situacion: "No entiendo mi factura",
    tema: "Tu factura",
    tiempoLectura: "7 min",
    normativa: "Circular 3/2020 CNMC",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "peaje-3-0td",
    href: "/guias/peaje-3-0td",
    titulo: "El peaje 3.0TD, periodo a periodo",
    h1: "El peaje 3.0TD, periodo a periodo: horarios, festivos y potencias (2026)",
    descripcion: "Qué horas son P1 a P6, qué festivos cuentan de verdad, las cuatro temporadas oficiales por zona y la regla técnica de potencias crecientes.",
    situacion: "No sé qué horas son las baratas",
    tema: "Tu factura",
    tiempoLectura: "6 min",
    normativa: "Circular 3/2020 CNMC",
    fechaRevision: "Tarifas 2026",
  },
  {
    slug: "subida-factura-luz-empresa",
    href: "/guias/subida-factura-luz-empresa",
    titulo: "Por qué ha subido la factura de tu empresa",
    h1: "¿Por qué ha subido la factura de luz de tu empresa?",
    descripcion: "Si la factura de tu empresa ha subido, la causa está en una de cinco partes. Cómo comparar dos facturas, línea a línea, para saber cuál ha sido exactamente.",
    situacion: "Me ha subido la factura y no sé por qué",
    tema: "Tu factura",
    tiempoLectura: "4 min",
    normativa: "Resolución peajes 2026",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "peaje-6-1td",
    href: "/guias/peaje-6-1td",
    titulo: "El peaje 6.1TD en alta tensión",
    h1: "El peaje 6.1TD: qué es y en qué cambia frente a la 3.0TD",
    descripcion: "La 6.1TD no depende de tu potencia, sino de la tensión de tu conexión (> 1 kV). Qué comparte con la 3.0TD, qué cambia y qué mirar si eres 6.1TD.",
    situacion: "Mi factura dice 6.1TD",
    tema: "Tu factura",
    tiempoLectura: "5 min",
    normativa: "Circular 3/2020 CNMC",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "potencia-contratada",
    href: "/guias/potencia-contratada",
    titulo: "Potencia contratada",
    h1: "Potencia contratada: cuánta necesita tu empresa y cómo cambiarla",
    descripcion: "La potencia se paga cada día aunque no la uses. Cuánta necesita tu empresa en cada periodo, qué pasa si contratas de menos y cómo se cambia ante distribuidora.",
    situacion: "No sé si me sobra o me falta potencia",
    tema: "Potencia",
    tiempoLectura: "6 min",
    normativa: "RD 1164/2001 & Circular CNMC",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "exceso-de-potencia",
    href: "/guias/exceso-de-potencia",
    titulo: "Exceso de potencia",
    h1: "Exceso de potencia: qué es, cómo se factura y qué cambió en 2025",
    descripcion: "Cómo se mide y se factura un exceso de potencia según tu contador (tipo 3 o 4), qué cambió con la Circular 1/2025 y por qué contratar de menos nunca compensa.",
    situacion: "Me aparecen excesos de potencia",
    tema: "Potencia",
    tiempoLectura: "5 min",
    normativa: "Circular 1/2025 CNMC",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "maximetro",
    href: "/guias/maximetro",
    titulo: "El maxímetro de tu contador",
    h1: "El maxímetro: qué mide y por qué decide tu potencia contratada",
    descripcion: "El maxímetro registra la potencia más alta que pide tu empresa en cada periodo, en cuartohorarias. Dónde verlo en la factura y cómo usarlo para optimizar.",
    situacion: "Quiero saber cómo mide mi maxímetro",
    tema: "Potencia",
    tiempoLectura: "4 min",
    normativa: "RD 1110/2007 Medida",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "energia-reactiva",
    href: "/guias/energia-reactiva",
    titulo: "Energía reactiva",
    h1: "Energía reactiva: cuándo se paga, cuánto cuesta y cuándo compensa corregirla",
    descripcion: "Quién paga reactiva, en qué periodos (P1 a P5), a qué precio en 2026 y qué pasa con la capacitiva en P6. Cuándo compensa instalar una batería de condensadores.",
    situacion: "Me cobran energía reactiva y no sé qué es",
    tema: "Tu instalación",
    tiempoLectura: "5 min",
    normativa: "Resolución CNMC peajes 2026",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "precio-fijo-o-indexado",
    href: "/guias/precio-fijo-o-indexado",
    titulo: "Precio fijo o indexado",
    h1: "Precio fijo o indexado: cómo decidir sin adivinar el mercado",
    descripcion: "Qué pagas y qué riesgo asumes con precio fijo, indexado a OMIE o cuota fija. Cuatro preguntas para decidir con tus horarios de producción.",
    situacion: "Tengo que elegir entre fijo e indexado",
    tema: "Tu contrato",
    tiempoLectura: "6 min",
    normativa: "Mercado mayorista OMIE",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "revision-precio-luz-empresa",
    href: "/guias/revision-precio-luz-empresa",
    titulo: "Carta de revisión de precio",
    h1: "Te ha llegado una carta que cambia tu precio de la luz: qué revisar",
    descripcion: "Si tu comercializadora te cambia el precio, la carta tiene que traer el precio de antes y el de después y tu coste anual. Qué revisar antes de aceptar o firmar.",
    situacion: "Me ha llegado una carta que cambia el precio",
    tema: "Tu contrato",
    tiempoLectura: "4 min",
    normativa: "RD 88/2026 & Ley 24/2013",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "renovacion-contrato-luz-empresa",
    href: "/guias/renovacion-contrato-luz-empresa",
    titulo: "Renovación y permanencia",
    h1: "Renovación y permanencia del contrato de luz: qué revisar antes",
    descripcion: "Cuándo se renueva tu contrato de luz, con cuánto tiempo te tienen que avisar si cambian las condiciones y qué penalización máxima te pueden cobrar según el RD 88/2026.",
    situacion: "Mi contrato se acaba o se renueva pronto",
    tema: "Tu contrato",
    tiempoLectura: "5 min",
    normativa: "RD 88/2026 de Contratación",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "tarifa-indexada-con-techo",
    href: "/guias/tarifa-indexada-con-techo",
    titulo: "Indexado con techo (Cap)",
    h1: "Tarifa indexada con techo: qué protege y qué no",
    descripcion: "Una tarifa indexada con techo sigue al mercado con un máximo garantizado. Qué protege, qué no, cuánto cuesta esa prima de seguro y qué preguntas hacer antes de firmar.",
    situacion: "Me ofrecen una tarifa indexada con techo",
    tema: "Tu contrato",
    tiempoLectura: "4 min",
    normativa: "Mercados de futuros OMIP",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "omie",
    href: "/guias/omie",
    titulo: "Qué es OMIE y cómo influye",
    h1: "Qué es OMIE y qué papel juega en tu factura de luz",
    descripcion: "OMIE fija cada día el precio mayorista de la luz, hora a hora. Qué es el pool eléctrico, cómo llega a tu factura indexada y qué margen real añade tu comercializadora.",
    situacion: "Mi contrato es indexado y no sé qué es OMIE",
    tema: "Tu contrato",
    tiempoLectura: "5 min",
    normativa: "Operador del Mercado Ibérico (OMIE)",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "cups-y-tus-datos",
    href: "/guias/cups-y-tus-datos",
    titulo: "Qué se ve con tu CUPS y qué no",
    h1: "¿Es seguro dar el CUPS de tu empresa? Qué se ve con él y qué no",
    descripcion: "Qué datos de consumo y potencia se consultan en el sistema SIPS con tu código CUPS, cuáles no puede ver nadie sin autorización expresa y cómo proteger tus datos.",
    situacion: "Me piden el CUPS y no sé si darlo",
    tema: "Tus datos",
    tiempoLectura: "4 min",
    normativa: "RD 88/2026 & RGPD",
    fechaRevision: "Actualizado 2026",
  },
  {
    slug: "glosario",
    href: "/guias/glosario",
    titulo: "Glosario de la factura eléctrica",
    h1: "Glosario de la factura de luz de empresa: los conceptos en claro",
    descripcion: "CUPS, peaje de acceso, cargos del sistema, maxímetro, reactiva capacitiva, OMIE, cuartohorarias: cada término explicado en un lenguaje humano y riguroso.",
    situacion: "Busco una definición rápida de un término",
    tema: "Diccionario",
    tiempoLectura: "8 min",
    normativa: "CNMC y Sistema Eléctrico Español",
    fechaRevision: "Actualizado 2026",
  },
];

// Conceptos rápidos de factura para la barra de acceso directo
export const conceptosFactura = [
  { nombre: "CUPS", href: "/guias/cups-y-tus-datos", desc: "Código único de tu suministro" },
  { nombre: "Peaje 3.0TD", href: "/guias/peaje-3-0td", desc: "Tarifa para más de 15 kW" },
  { nombre: "Potencia", href: "/guias/potencia-contratada", desc: "Término fijo contratado en kW" },
  { nombre: "Maxímetro", href: "/guias/maximetro", desc: "Medidor de pico cuartohorario" },
  { nombre: "Excesos", href: "/guias/exceso-de-potencia", desc: "Penalización por sobrepasar kW" },
  { nombre: "Reactiva", href: "/guias/energia-reactiva", desc: "Coste por desfase inductivo/capacitivo" },
  { nombre: "Fijo vs Indexado", href: "/guias/precio-fijo-o-indexado", desc: "Modalidad de compra de energía" },
  { nombre: "OMIE", href: "/guias/omie", desc: "Mercado diario mayorista" },
  { nombre: "Glosario", href: "/guias/glosario", desc: "Diccionario completo de términos" },
];

// Agrupaciones temáticas
export const temasGuias = [
  {
    titulo: "Tu factura",
    descripcion: "Conceptos, desglose y cómo auditar cada euro que te cobran.",
    colorBorde: "var(--azul-900)",
    slugs: ["bajar-factura-luz-empresa", "factura-luz-empresa", "peaje-3-0td", "subida-factura-luz-empresa", "peaje-6-1td"],
  },
  {
    titulo: "Potencia",
    descripcion: "El término fijo: cuánta necesitas de verdad y cómo evitar penalizaciones.",
    colorBorde: "var(--azul-700)",
    slugs: ["potencia-contratada", "exceso-de-potencia", "maximetro"],
  },
  {
    titulo: "Tu instalación",
    descripcion: "Eficiencia, energía reactiva y amortización de equipos correctores.",
    colorBorde: "#0284c7",
    slugs: ["energia-reactiva"],
  },
  {
    titulo: "Tu contrato",
    descripcion: "Cláusulas, revisiones de precio, renovaciones y cómo defenderte de comerciales agresivos.",
    colorBorde: "var(--azul-600)",
    slugs: ["precio-fijo-o-indexado", "revision-precio-luz-empresa", "renovacion-contrato-luz-empresa", "tarifa-indexada-con-techo", "omie"],
  },
  {
    titulo: "Tus datos",
    descripcion: "Privacidad, qué información técnica contiene tu CUPS y quién puede verla.",
    colorBorde: "var(--azul-900)",
    slugs: ["cups-y-tus-datos", "glosario"],
  },
];

// Helper: Calcula la siguiente guía en su tema; si es la última del tema, la primera del siguiente
export function getSiguienteGuia(slugActual: string): GuiaItem {
  const guiaActual = guiasData.find((g) => g.slug === slugActual);
  if (!guiaActual) return guiasData[0];

  const temaObj = temasGuias.find((t) => t.slugs.includes(slugActual));
  if (temaObj) {
    const idxEnTema = temaObj.slugs.indexOf(slugActual);
    if (idxEnTema !== -1 && idxEnTema < temaObj.slugs.length - 1) {
      const sigSlug = temaObj.slugs[idxEnTema + 1];
      const sigGuia = guiasData.find((g) => g.slug === sigSlug);
      if (sigGuia) return sigGuia;
    }

    const idxTema = temasGuias.indexOf(temaObj);
    const sigTemaObj = temasGuias[(idxTema + 1) % temasGuias.length];
    const primerSlugSig = sigTemaObj.slugs[0];
    const primeraGuiaSig = guiasData.find((g) => g.slug === primerSlugSig);
    if (primeraGuiaSig) return primeraGuiaSig;
  }

  const idxTotal = guiasData.findIndex((g) => g.slug === slugActual);
  return guiasData[(idxTotal + 1) % guiasData.length];
}

// Helper: Filtra guías enlazables evitando enlazar a la guía actual
export function filtrarEnlazables(
  slugActual: string,
  limite: number = 3
): Array<{ titulo: string; href: string }> {
  const guiaActual = guiasData.find((g) => g.slug === slugActual);
  const tema = guiaActual?.tema;

  let candidatas = guiasData.filter(
    (g) => g.slug !== slugActual && (!tema || g.tema === tema)
  );

  if (candidatas.length < limite) {
    const otras = guiasData.filter(
      (g) => g.slug !== slugActual && !candidatas.some((c) => c.slug === g.slug)
    );
    candidatas = candidatas.concat(otras);
  }

  return candidatas.slice(0, limite).map((g) => ({
    titulo: g.titulo,
    href: g.href,
  }));
}

