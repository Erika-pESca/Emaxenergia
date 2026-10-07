// Datos que se repiten en cabecera, pie y SEO. Se cambian aquí, no en cada componente.

export const site = {
  name: "EMAX Energía",
  url: "https://emaxenergia.com",
  email: "info@emaxenergia.com",
  phone: "900 000 000",
  phoneHref: "tel:+34900000000",
  tagline: "La mejor tarifa no existe. Existe la que necesita tu empresa.",
} as const;

// Enlaces de la cabecera.
export const headerNav = [
  { href: "/", label: "Inicio" },
  { href: "/metodo", label: "Método" },
  { href: "/maxi", label: "Maxi" },
  { href: "/empresas", label: "Empresas" },
  { href: "/casos-de-exito", label: "Casos de éxito" },
  { href: "/recursos", label: "Recursos" },
] as const;

// Botón principal de la cabecera. Apunta al bloque del inicio que aún no existe.
export const primaryCta = {
  href: "/#estudio",
  label: "Analizar mi consumo",
} as const;

// Columnas del pie. Contacto no tiene enlaces todavía.
export const footerNav = [
  {
    title: "Método",
    links: [
      { href: "/metodo", label: "El Método EMAX" },
      { href: "/diseno-tarifario", label: "Diseño tarifario" },
    ],
  },
  {
    title: "Empresas",
    links: [
      { href: "/empresas", label: "Tipos de empresas" },
      { href: "/sectores", label: "Sectores" },
      { href: "/orientacion-energetica", label: "Orientación energética" },
    ],
  },
  {
    title: "Contacto",
    links: [] as { href: string; label: string }[],
  },
] as const;

// Enlaces de la franja inferior del pie.
export const legalNav = [
  { href: "/privacidad", label: "Política de privacidad" },
  { href: "/aviso-legal", label: "Aviso legal" },
] as const;
