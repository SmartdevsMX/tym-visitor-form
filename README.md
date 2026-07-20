# Formularios Trigo y Miel

Dos formularios estilo Typeform para la iglesia, cada uno es un solo archivo HTML
autocontenido (sin Node.js, sin build, sin dependencias) — pensados para subirse tal cual
al hosting compartido del sitio de la iglesia.

- **`index.html`** — Ficha de visita. La llena el staff/greeters cuando llega alguien nuevo.
- **`casas-esperanza.html`** — Reporte semanal de Casas de Esperanza. Lo llenan los líderes
  de grupo después de su reunión. Reemplaza el Google Form anterior.

## Cómo instalarlo (subir al hosting)

1. Entra al panel de tu hosting (cPanel, Plesk, o el que uses) y abre el **Administrador
   de archivos** (File Manager), o conéctate por **FTP** con un cliente como FileZilla.
2. Ve a la carpeta pública de tu sitio (normalmente se llama `public_html` o `www`).
3. Sube `index.html` y `casas-esperanza.html` tal cual están, sin modificarlos. Puedes
   subirlos directo a la raíz, o crear una subcarpeta si prefieres (ej. `/formularios/`).
4. Listo — no hay build, no hay `npm install`, no hay nada más que instalar. Son archivos
   HTML normales, como cualquier página web estática.

**Dónde quedan disponibles**, según dónde los subas:
- Si los subes a la raíz: `https://tudominio.com/index.html` y
  `https://tudominio.com/casas-esperanza.html`
- Si los subes a una subcarpeta `/formularios/`:
  `https://tudominio.com/formularios/index.html` (etc.)

Si quieres URLs más cortas y bonitas (ej. `tudominio.com/visitas` en vez de
`/index.html`), dímelo y lo configuramos con el `.htaccess` del hosting — depende de qué
tipo de servidor uses.

## Cómo actualizar un cambio

Cada vez que se actualice un formulario, solo hay que volver a subir el archivo
correspondiente y sobrescribir el que ya está en el hosting — mismo proceso que la
instalación inicial (pasos 1-3).

## De dónde vienen los datos

Ambos formularios mandan cada respuesta a un backend en n8n (ya configurado y probado),
que las guarda en paralelo en:
- Una tabla interna de n8n (respaldo, siempre funciona)
- Un Google Sheet (para verlos fácil sin entrar a n8n):
  - Ficha de visita → **Trigo y Miel — Registro de Visitantes** (carpeta TYMSLP en Drive)
  - Casas de Esperanza → **Reportes - Casas de esperanza** (el mismo spreadsheet que ya
    usaba el Google Form anterior — mismas columnas, con "Fecha de la reunión" y un ID
    interno agregados al final)

No se necesita ninguna configuración adicional en el hosting para que esto funcione — los
formularios llaman al backend directo desde el navegador de quien los llena.
