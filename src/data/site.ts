// Datos institucionales y de marca para EMAX Energía
// Centralizados para mantener consistencia en toda la web.

export const site = {
  name: "EMAX Energía",
  legalName: "ENERGYMAX ADVISORY, S.L.U.",
  cif: "B02994333",
  url: "https://emaxenergia.com",
  email: "hola@emaxenergia.com",
  emailGerencia: "gerencia@emaxenergia.com",
  emailAdministracion: "administracion@emaxenergia.com",
  emailSoporte: "administracion@emaxenergia.com",
  correosFormularios: {
    // Leads (estudio de factura luz/gas, llamada gratis, canal directo) -> gerencia y hola (copia a administracion)
    leads: {
      para: ["gerencia@emaxenergia.com", "hola@emaxenergia.com"],
      cc: ["administracion@emaxenergia.com"],
    },
    // Soporte o página de contacto (/contacto/ y /atencion-al-cliente/) -> administracion (copia a gerencia y hola)
    soporteContacto: {
      para: ["administracion@emaxenergia.com"],
      cc: ["gerencia@emaxenergia.com", "hola@emaxenergia.com"],
    },
  },
  phone: "933 949 343",
  phoneHref: "tel:+34933949343",
  horario: "Lunes a viernes de 9:00 a 13:00 y de 15:00 a 19:00",
  direccion: "Carrer Esteve Gilabert Bruniquer, 52, 08402 Granollers, Barcelona",
  tagline: "Energía para empresas, a partir de tu consumo",
  subTagline: "La mejor tarifa no está en ningún catálogo: se construye con tus datos.",
} as const;
