export const site = {
  name: "EMAX Energía",
  url: "https://emaxenergia.com",
  email: "info@emaxenergia.com",
  phone: "900 000 050",
  phoneHref: "tel:+34900000050",
  whatsappHref: "https://wa.me/34900000050",
  tagline: "La mejor tarifa no existe. Existe la que necesita tu empresa.",
} as const;

export const headerNav = [
  { href: "/", label: "Inicio" },
  { href: "/metodo", label: "Método" },
  { href: "/maat", label: "Maat" },
  { href: "/empresas", label: "Empresas" },
  { href: "/casos-de-exito", label: "Casos de éxito" },
  { href: "/recursos", label: "Recursos" },
] as const;

export const primaryCta = {
  href: "/#estudio",
  label: "Analizar mi consumo",
} as const;

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
    links: [{ href: "/contacto", label: "Contacto" }],
  },
] as const;

export const legalNav = [
  { href: "/privacidad", label: "Política de privacidad" },
  { href: "/aviso-legal", label: "Aviso legal" },
] as const;
