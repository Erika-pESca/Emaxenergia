// Datos institucionales y de navegación para EMAX Energía (v12.16)
// Centralizados para mantener consistencia en toda la web.

export const site = {
  name: "EMAX Energía",
  legalName: "ENERGYMAX ADVISORY, S.L.U.",
  cif: "B02994333",
  url: "https://emaxenergia.com",
  email: "hola@emaxenergia.com",
  phone: "933 949 343",
  phoneHref: "tel:+34933949343",
  horario: "Lunes a viernes de 9:00 a 13:00 y de 15:00 a 19:00",
  direccion: "Carrer Esteve Gilabert Bruniquer, 52, 08402 Granollers, Barcelona",
  tagline: "Energía para empresas, a partir de tu consumo",
  subTagline: "La mejor tarifa no está en ningún catálogo: se construye con tus datos.",
} as const;

// Enlaces principales de navegación
export const headerNav = [
  { href: "/", label: "Inicio" },
  { href: "/metodo", label: "Método" },
  { href: "/tarifas", label: "Tarifas" },
  { href: "/sectores", label: "Sectores" },
  { href: "/guias", label: "Guías" },
  { href: "/contacto", label: "Contacto" },
] as const;

// Botón de llamada a la acción principal
export const primaryCta = {
  href: "/#estudio",
  label: "Analizar mi factura",
} as const;

// Enlace secundario para llamada telefónica rápida
export const phoneCta = {
  href: site.phoneHref,
  label: site.phone,
  sublabel: "Te atendemos directo",
} as const;

// Columnas del pie de página
export const footerNav = [
  {
    title: "Propuesta",
    links: [
      { href: "/metodo", label: "El Método EMAX" },
      { href: "/tarifas", label: "Tarifas de Luz y Gas" },
      { href: "/gas-natural", label: "Gas natural" },
      { href: "/varios-suministros", label: "Varios suministros" },
    ],
  },
  {
    title: "Sectores",
    links: [
      { href: "/sectores/restaurantes", label: "Restaurantes y hostelería" },
      { href: "/sectores/hoteles", label: "Hoteles y alojamientos" },
      { href: "/sectores/supermercados", label: "Supermercados" },
      { href: "/sectores/logistica-frio", label: "Logística y frío" },
      { href: "/sectores/industria", label: "Industria" },
    ],
  },
  {
    title: "Aprender",
    links: [
      { href: "/guias", label: "Centro de guías" },
      { href: "/guias/bajar-factura-luz-empresa", label: "Bajar la factura" },
      { href: "/guias/potencia-contratada", label: "Optimizar potencia" },
      { href: "/guias/energia-reactiva", label: "Evitar reactiva" },
    ],
  },
  {
    title: "Contacto",
    links: [
      { href: "/contacto", label: "Formulario de contacto" },
      { href: "/atencion-al-cliente", label: "Atención al cliente" },
      { href: "tel:+34933949343", label: "Tel. 933 949 343" },
      { href: "mailto:hola@emaxenergia.com", label: "hola@emaxenergia.com" },
    ],
  },
] as const;

// Enlaces legales
export const legalNav = [
  { href: "/aviso-legal", label: "Aviso legal" },
  { href: "/privacidad", label: "Política de privacidad" },
  { href: "/cookies", label: "Política de cookies" },
] as const;
