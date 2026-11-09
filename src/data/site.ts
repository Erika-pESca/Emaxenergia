// Datos institucionales y de marca para EMAX Energía
// Centralizados para mantener consistencia en toda la web.

export const site = {
  name: "EMAX Energía",
  legalName: "ENERGYMAX ADVISORY, S.L.U.",
  cif: "B02994333",
  url: "https://emaxenergia.com",
  email: "hola@emaxenergia.com",
  // Contacto legal publicado en https://emaxenergia.com/#/privacidad (2026-11-09).
  emailLegal: "administracion@consultoriaemax.com",
  // Contacto público del área de atención al cliente (se muestra en /atencion-al-cliente/).
  // Los destinatarios de los formularios NO están aquí: viven solo en emax-forms-config.php.
  emailSoporte: "administracion@emaxenergia.com",
  phone: "933 949 343",
  phoneHref: "tel:+34933949343",
  horario: "Lunes a viernes de 9:00 a 13:00 y de 15:00 a 19:00",
  direccion: "Calle Gerona 38 Local, Santa Eulalia de Ronçana, 08187 Barcelona",
  tagline: "Energía para empresas, a partir de tu consumo",
  subTagline: "La mejor tarifa no está en ningún catálogo: se construye con tus datos.",
} as const;
