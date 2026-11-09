# Verificación previa a publicación · 0.3.3

Fecha: 2026-11-09. Cambios realizados por Luciano Lyall (lucianolyall@gmail.com).

## Título de la home en el HTML inicial

Tras `git pull` (`Already up to date.`), `src/components/home/ComoEmpezar.astro:57` y el `dist/index.html` existente ya contienen el título completo:

```html
<h2 class="proceso-titulo">
  TÚ HACES TRES COSAS.<br />
  DEL RESTO <span class="proceso-titulo-cursiva">nos encargamos.</span>
</h2>
```

El título es texto estático, no se construye con JavaScript, pseudo-elementos ni contenido de una animación. El script del componente controla la línea y los estados de los pasos, sin modificar el título. Su CSS no oculta «RESTO» ni cambia su contenido. No se modificó el título, sus estilos ni la interacción al no reproducirse el problema en este checkout; la captura/Lighthouse mencionados no permiten atribuir una causa al texto incompleto observado en otra ejecución.

Se extrajo el `<h2>` directamente de `dist/index.html` y se comprobó su texto exacto. Además, Chromium headless abrió la home con `javaScriptEnabled: false` en estos cuatro contextos:

| Ancho | `prefers-reduced-motion` | Resultado |
| --- | --- | --- |
| 1440 px | Sin preferencia | Título completo y visible |
| 1440 px | `reduce` | Título completo y visible |
| 375 px | Sin preferencia | Título completo y visible |
| 375 px | `reduce` | Título completo y visible |

Texto leído en los cuatro casos: **TÚ HACES TRES COSAS. DEL RESTO nos encargamos.**

Evidencias locales, ignoradas por Git: `.astro/revision-033/home-estatica.json` y las cuatro capturas `home-sin-js-*.png`. El build y el resto de las comprobaciones finales se registran al completar el punto 4.
