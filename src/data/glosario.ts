// Diccionario único de términos técnicos para tooltips de las guías
// EMAX Energía · Glosario oficial

export const glosario: Record<string, string> = {
  "maximetro": "Registra la potencia más alta que ha pedido tu empresa en cada periodo, medida como la media de un cuarto de hora.",
  "maxímetro": "Registra la potencia más alta que ha pedido tu empresa en cada periodo, medida como la media de un cuarto de hora.",
  "cups": "Código Unificado de Punto de Suministro: el código único de 20 o 22 caracteres que identifica tu instalación ante distribuidora.",
  "CUPS": "Código Unificado de Punto de Suministro: el código único de 20 o 22 caracteres que identifica tu instalación ante distribuidora.",
  "peaje": "Coste regulado fijado por la CNMC por el uso de las redes de transporte y distribución.",
  "peaje-3-0td": "Peaje de acceso para suministros en baja tensión con más de 15 kW en al menos uno de sus seis periodos.",
  "3.0TD": "Peaje de acceso para suministros en baja tensión con más de 15 kW en al menos uno de sus seis periodos.",
  "3.0td": "Peaje de acceso para suministros en baja tensión con más de 15 kW en al menos uno de sus seis periodos.",
  "6.1TD": "Peaje de acceso para suministros en alta tensión (tensión superior a 1 kV y hasta 30 kV).",
  "6.1td": "Peaje de acceso para suministros en alta tensión (tensión superior a 1 kV y hasta 30 kV).",
  "2.0TD": "Peaje de acceso para suministros domésticos o pequeñas empresas con hasta 15 kW de potencia.",
  "2.0td": "Peaje de acceso para suministros domésticos o pequeñas empresas con hasta 15 kW de potencia.",
  "omie": "Operador del Mercado Ibérico de Energía: fija el precio marginal del kWh en España y Portugal en subasta diaria.",
  "OMIE": "Operador del Mercado Ibérico de Energía: fija el precio marginal del kWh en España y Portugal en subasta diaria.",
  "reactiva": "Energía que ciertos equipos (motores, transformadores, cámaras) intercambian con la red sin hacer trabajo. Se penaliza en P1 a P5 si supera el 33 % de la activa.",
  "energía reactiva": "Energía que ciertos equipos intercambian con la red sin hacer trabajo. Se penaliza en P1 a P5 si supera el 33 % de la activa.",
  "energia-reactiva": "Energía que ciertos equipos intercambian con la red sin hacer trabajo. Se penaliza en P1 a P5 si supera el 33 % de la activa.",
  "exceso": "Potencia demandada por encima de lo contratado en un periodo de 15 minutos, facturada con recargo disuasorio.",
  "exceso-de-potencia": "Potencia demandada por encima de lo contratado en un periodo de 15 minutos, facturada con recargo disuasorio.",
  "potencia-contratada": "Capacidad máxima en kW que tu empresa tiene disponible para consumir a la vez en cada periodo, facturada por días.",
  "potencia": "Capacidad máxima en kW que tu empresa tiene disponible para consumir a la vez en cada periodo, facturada por días.",
  "cuartohoraria": "Medición registrada por el contador cada 15 minutos que sirve para calcular la potencia máxima y los excesos.",
  "derechos-extension": "Importe pagado a distribuidora que reserva la potencia técnica de tu instalación durante un periodo máximo de caducidad.",
  "boletin": "Certificado de Instalación Eléctrica (CIE) que garantiza que la instalación cumple la normativa técnica.",
  "indexado": "Modalidad contractual donde el precio de la energía varía según el mercado mayorista diario (OMIE).",
  "precio-fijo": "Modalidad contractual donde el precio del kWh se mantiene constante durante la vigencia pactada.",
  "p1": "Periodo punta: las horas con el coste de peajes y potencia más alto.",
  "p6": "Periodo valle: las horas de menor coste (noches, fines de semana y festivos nacionales).",
};

export function getDefinicion(clave: string): string {
  if (!clave) return "";
  const normalizada = clave.toLowerCase().trim();
  return glosario[clave] || glosario[normalizada] || "";
}
