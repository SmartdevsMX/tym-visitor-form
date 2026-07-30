<?php
require_once __DIR__ . '/crece_auth_config.php';
$crece_session = crece_require_session_or_redirect();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crece — Trigo y Miel</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --gold: #E0C34B;
    --bronze: #AB9256;
    --coral: #C87F7F;
    --charcoal: #323e45;
    --warm-gray: #7A7A7A;
    --bg-warm: #faf8f4;
    --white: #ffffff;
    --green: #7BA88A;
    --green-bg: #EAF3EC;
    --amber: #D9A441;
    --amber-bg: #FBF1DD;
    --red: #C87F7F;
    --red-bg: #FBEDED;
  }

  body {
    font-family: 'Raleway', sans-serif;
    background: var(--bg-warm);
    color: var(--charcoal);
    min-height: 100vh;
    padding: 24px 32px 64px;
  }

  h1, h2, .kpi-value { font-family: 'Playfair Display', serif; }

  header.top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 32px;
  }

  header.top h1 {
    font-size: 28px;
    font-weight: 700;
  }
  header.top .subtitle {
    font-size: 13px;
    color: var(--warm-gray);
    margin-top: 2px;
  }

  select#rango {
    font-family: 'Raleway', sans-serif;
    font-size: 14px;
    padding: 10px 16px;
    border-radius: 999px;
    border: 1px solid var(--bronze);
    background: var(--white);
    color: var(--charcoal);
    cursor: pointer;
  }

  .kpi-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
  }

  .kpi-card {
    background: var(--white);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(50, 62, 69, 0.06);
  }

  .kpi-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--warm-gray);
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }

  .kpi-value {
    font-size: 40px;
    font-weight: 700;
    color: var(--charcoal);
    margin: 6px 0 4px;
  }

  .kpi-value.na { font-size: 22px; color: var(--warm-gray); font-family: 'Raleway', sans-serif; }

  .kpi-desc {
    font-size: 12.5px;
    color: var(--warm-gray);
    line-height: 1.4;
  }

  .kpi-trend {
    font-size: 12.5px;
    font-weight: 600;
    margin-top: 8px;
    display: inline-block;
    padding: 3px 10px;
    border-radius: 999px;
  }
  .kpi-trend.up { color: var(--green); background: var(--green-bg); }
  .kpi-trend.down { color: var(--red); background: var(--red-bg); }
  .kpi-trend.flat { color: var(--warm-gray); background: #f0eee8; }

  .kpi-spark { margin-top: 12px; }

  section h2 {
    font-size: 20px;
    margin-bottom: 16px;
  }

  .casas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
  }

  .casa-card {
    background: var(--white);
    border-radius: 16px;
    padding: 18px 20px;
    box-shadow: 0 4px 16px rgba(50, 62, 69, 0.05);
    border-top: 4px solid var(--warm-gray);
  }
  .casa-card.semaforo-verde { border-top-color: var(--green); }
  .casa-card.semaforo-ambar { border-top-color: var(--amber); }
  .casa-card.semaforo-rojo { border-top-color: var(--red); }

  .casa-card {
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s;
  }
  .casa-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(50, 62, 69, 0.1);
  }

  .casa-card .casa-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
  }
  .casa-card .casa-nombre {
    font-weight: 700;
    font-size: 15.5px;
  }
  .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .dot.semaforo-verde { background: var(--green); }
  .dot.semaforo-ambar { background: var(--amber); }
  .dot.semaforo-rojo { background: var(--red); }

  .casa-metrics {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    font-size: 12.5px;
    color: var(--warm-gray);
    margin-bottom: 10px;
  }
  .casa-metrics b { color: var(--charcoal); }

  .casa-nota {
    font-size: 12.5px;
    color: var(--charcoal);
    font-style: italic;
    opacity: 0.85;
    line-height: 1.4;
  }
  .ia-tag {
    font-style: normal;
    font-size: 10.5px;
    font-weight: 700;
    color: var(--bronze);
    opacity: 1;
    margin-right: 2px;
  }

  .empty-state, .loading-state, .error-state {
    padding: 40px;
    text-align: center;
    color: var(--warm-gray);
    font-size: 14px;
    background: var(--white);
    border-radius: 16px;
  }
  .error-state { color: var(--coral); }

  .metodologia {
    font-size: 11.5px;
    color: var(--warm-gray);
    margin-top: -32px;
    margin-bottom: 40px;
  }

  .mensajes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
  }

  .mensaje-card {
    background: var(--white);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 16px rgba(50, 62, 69, 0.05);
    border-left: 4px solid var(--gold);
    display: flex;
    flex-direction: column;
  }

  .mensaje-card .mensaje-nombre {
    font-weight: 700;
    font-size: 16px;
    color: var(--charcoal);
    margin-bottom: 12px;
  }

  .mensaje-card .mensaje-actions {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
    flex-wrap: wrap;
  }

  .mensaje-card .mensaje-actions a,
  .mensaje-card .mensaje-actions button {
    font-family: 'Raleway', sans-serif;
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid var(--bronze);
    background: var(--white);
    color: var(--charcoal);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
  }

  .mensaje-card .mensaje-actions a:hover,
  .mensaje-card .mensaje-actions button:hover {
    background: var(--gold);
    color: var(--white);
    border-color: var(--gold);
  }

  .mensaje-card .mensaje-actions a {
    display: inline-block;
  }

  .mensaje-card .mensaje-texto {
    font-size: 13.5px;
    line-height: 1.5;
    color: var(--charcoal);
    flex-grow: 1;
    margin-bottom: 12px;
  }

  .mensaje-card .mensaje-fecha {
    font-size: 11px;
    color: var(--warm-gray);
  }

  .persona-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    font-size: 13px;
    padding: 6px 0;
    border-top: 1px solid #f0eee8;
  }
  .persona-row:first-of-type { border-top: none; }
  .persona-row .persona-dias {
    color: var(--warm-gray);
    font-size: 11.5px;
  }
  .persona-row a {
    font-size: 11.5px;
    color: var(--bronze);
    text-decoration: none;
    white-space: nowrap;
  }
  .persona-row a:hover { text-decoration: underline; }

  /* Casa detail panel */
  .back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--bronze);
    text-decoration: none;
    margin-bottom: 20px;
    cursor: pointer;
  }
  .back-link:hover { text-decoration: underline; }

  .detail-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
  }
  .detail-header h1 { font-size: 26px; }
  .detail-subtitle {
    font-size: 13px;
    color: var(--warm-gray);
    margin-bottom: 32px;
  }

  .detail-section {
    background: var(--white);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 16px rgba(50, 62, 69, 0.05);
    margin-bottom: 24px;
  }
  .detail-section h3 {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
  }
  .stat-item .stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    font-weight: 700;
    color: var(--charcoal);
  }
  .stat-item .stat-label {
    font-size: 11.5px;
    color: var(--warm-gray);
    text-transform: uppercase;
    letter-spacing: 0.02em;
  }

  .comp-bar {
    display: flex;
    height: 28px;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 12px;
  }
  .comp-bar span { display: block; height: 100%; }
  .comp-bar .comp-hombres { background: var(--bronze); }
  .comp-bar .comp-mujeres { background: var(--coral); }
  .comp-bar .comp-ninos { background: var(--gold); }
  .comp-legend {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    font-size: 12.5px;
    color: var(--charcoal);
  }
  .comp-legend .swatch {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 3px;
    margin-right: 6px;
    vertical-align: middle;
  }

  .salud-banner {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    border-radius: 12px;
    background: var(--green-bg);
  }
  .salud-banner.semaforo-ambar { background: var(--amber-bg); }
  .salud-banner.semaforo-rojo { background: var(--red-bg); }
  .salud-banner .dot { width: 14px; height: 14px; }
  .salud-banner .salud-label { font-weight: 700; font-size: 14.5px; }
  .salud-banner .salud-texto { font-size: 13px; color: var(--charcoal); opacity: 0.85; margin-top: 2px; }

  .insight-box {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 16px 18px;
    background: #FBF6E9;
    border-radius: 12px;
    border-left: 4px solid var(--gold);
  }
  .insight-box .insight-texto {
    font-size: 14px;
    line-height: 1.5;
    color: var(--charcoal);
  }
  .insight-box .insight-fecha {
    font-size: 11px;
    color: var(--warm-gray);
    margin-top: 6px;
  }

  table.asistencia-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  table.asistencia-table th {
    text-align: left;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    color: var(--warm-gray);
    padding: 8px 10px;
    border-bottom: 1px solid #f0eee8;
  }
  table.asistencia-table td {
    padding: 10px;
    border-bottom: 1px solid #f0eee8;
  }
  table.asistencia-table a {
    color: var(--bronze);
    font-size: 12px;
    text-decoration: none;
  }
  table.asistencia-table a:hover { text-decoration: underline; }

  .copy-feedback {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--green);
    color: var(--white);
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 13px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
  }
</style>
</head>
<body>

<header class="top">
  <div>
    <h1>Crece</h1>
    <div class="subtitle">Trigo y Miel — San Luis Potosí</div>
  </div>
  <select id="rango">
    <option value="4">Últimas 4 semanas</option>
    <option value="8" selected>Últimas 8 semanas</option>
    <option value="12">Últimas 12 semanas</option>
  </select>
  <a href="/logout.php" style="font-size:13px;color:var(--charcoal);opacity:0.6;text-decoration:none;margin-left:12px;">Cerrar sesión</a>
</header>

<div id="loading" class="loading-state">Cargando datos…</div>
<div id="content" style="display:none;">

  <div class="kpi-row" id="kpiRow"></div>
  <p class="metodologia">Retención y Conexión en 6 semanas son estimaciones agregadas a partir de los reportes de las Casas — no dan seguimiento a personas individuales. El conteo de visitantes nuevos se estima a partir del campo de texto libre de cada reporte, así que estas dos métricas son aproximadas, no exactas.</p>

  <section>
    <h2>Salud por Casa de Esperanza</h2>
    <div class="casas-grid" id="casasGrid"></div>
  </section>

  <section>
    <h2>A quién contactar (no han vuelto en 3+ semanas)</h2>
    <div class="casas-grid" id="noHanVueltoGrid"></div>
  </section>

  <section>
    <h2>Mensajes para invitar a Casa</h2>
    <div class="mensajes-grid" id="mensajesGrid"></div>
  </section>

</div>

<div id="casaDetail" style="display:none;"></div>

<div id="errorBox" class="error-state" style="display:none;"></div>

<script>
const API = 'api-dashboard.php';
let RAW = null;

function isoWeekKey(dateStr) {
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return null;
  const target = new Date(Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate()));
  const dayNum = (target.getUTCDay() + 6) % 7;
  target.setUTCDate(target.getUTCDate() - dayNum + 3);
  const firstThursday = new Date(Date.UTC(target.getUTCFullYear(), 0, 4));
  const week = 1 + Math.round(((target - firstThursday) / 86400000 - 3 + ((firstThursday.getUTCDay() + 6) % 7)) / 7);
  return `${target.getUTCFullYear()}-W${String(week).padStart(2, '0')}`;
}

function weeksAgo(n) {
  const d = new Date();
  d.setUTCDate(d.getUTCDate() - n * 7);
  return d;
}

function inRange(dateStr, start, end) {
  const d = new Date(dateStr);
  return d >= start && d <= end;
}

function pct(n, d) {
  if (!d || d <= 0) return null;
  return Math.min(Math.round((n / d) * 100), 100);
}

function fmtPct(v) {
  return v === null ? '—' : v + '%';
}

function sparkline(values, color) {
  if (!values.length || values.every(v => v === 0)) return '';
  const w = 200, h = 40, pad = 4;
  const max = Math.max(...values, 1);
  const min = Math.min(...values, 0);
  const range = max - min || 1;
  const step = (w - pad * 2) / Math.max(values.length - 1, 1);
  const pts = values.map((v, i) => {
    const x = pad + i * step;
    const y = h - pad - ((v - min) / range) * (h - pad * 2);
    return `${x.toFixed(1)},${y.toFixed(1)}`;
  }).join(' ');
  return `<svg class="kpi-spark" width="${w}" height="${h}" viewBox="0 0 ${w} ${h}"><polyline points="${pts}" fill="none" stroke="${color}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
}

function weeklyBuckets(rows, dateField, valueFn, start, end) {
  const buckets = {};
  rows.forEach(r => {
    if (!r[dateField] || !inRange(r[dateField], start, end)) return;
    const wk = isoWeekKey(r[dateField]);
    if (!wk) return;
    buckets[wk] = (buckets[wk] || 0) + valueFn(r);
  });
  return Object.keys(buckets).sort().map(k => buckets[k]);
}

function computeRetencion(casas, start, end) {
  const rows = casas.filter(c => c.fecha && inRange(c.fecha, start, end));
  const num = rows.reduce((s, c) => s + (c.segunda_vez_count || 0), 0);
  const den = rows.reduce((s, c) => s + (c.hubo_visitantes_nuevos ? c.visitantes_nuevos_count : 0), 0);
  return { value: pct(num, den), num, den };
}

function computeParticipacion(casas, asistencia, start, end) {
  const casasByWeek = {};
  casas.filter(c => c.fecha && inRange(c.fecha, start, end)).forEach(c => {
    const wk = isoWeekKey(c.fecha);
    if (!wk) return;
    casasByWeek[wk] = (casasByWeek[wk] || 0) + (c.total_asistentes || 0);
  });
  const domByWeek = {};
  asistencia.filter(a => a.fecha && inRange(a.fecha, start, end)).forEach(a => {
    const wk = isoWeekKey(a.fecha);
    if (!wk) return;
    domByWeek[wk] = (domByWeek[wk] || 0) + (a.total_asistentes || 0);
  });
  let num = 0, den = 0;
  Object.keys(domByWeek).forEach(wk => {
    den += domByWeek[wk];
    num += casasByWeek[wk] || 0;
  });
  return { value: pct(num, den), num, den };
}

function computeConexion(visitantes, casas, start, end) {
  const nuevosPorSemana = {};
  visitantes.filter(v => v.completed && v.primera_vez === 'Sí' && v.started_at).forEach(v => {
    const wk = isoWeekKey(v.started_at);
    if (!wk) return;
    if (!nuevosPorSemana[wk]) nuevosPorSemana[wk] = { fecha: new Date(v.started_at), count: 0 };
    nuevosPorSemana[wk].count += 1;
  });
  let num = 0, den = 0, cohortesConsideradas = 0;
  Object.entries(nuevosPorSemana).forEach(([wk, info]) => {
    const winStart = new Date(info.fecha); winStart.setUTCDate(winStart.getUTCDate() + 14);
    const winEnd = new Date(info.fecha); winEnd.setUTCDate(winEnd.getUTCDate() + 63);
    if (winEnd > new Date()) return; // ventana aún no cierra
    if (!inRange(info.fecha.toISOString(), start, end)) return;
    cohortesConsideradas++;
    den += info.count;
    const llegadas = casas.filter(c => c.fecha && c.hubo_visitantes_nuevos && inRange(c.fecha, winStart, winEnd))
      .reduce((s, c) => s + c.visitantes_nuevos_count, 0);
    num += llegadas;
  });
  return { value: cohortesConsideradas > 0 ? Math.min(pct(num, den), 100) : null, num, den, cohortesConsideradas };
}

function renderKpi(label, desc, metricValue, spark, sparkColor) {
  const valHtml = metricValue === null
    ? `<div class="kpi-value na">Datos insuficientes</div>`
    : `<div class="kpi-value">${metricValue}%</div>`;
  return `
    <div class="kpi-card">
      <div class="kpi-label">${label}</div>
      ${valHtml}
      <div class="kpi-desc">${desc}</div>
      ${spark ? sparkline(spark, sparkColor) : ''}
    </div>`;
}

function casaHealthColor(trend, reportedRecently) {
  if (trend === 'down' && !reportedRecently) return 'rojo';
  if (trend === 'down' || !reportedRecently) return 'ambar';
  return 'verde';
}

function renderCasas(casas, insights, start, end) {
  const insightByCasa = {};
  (insights || []).forEach(i => { if (i.casa) insightByCasa[i.casa] = i; });
  const byName = {};
  casas.forEach(c => {
    if (!c.casa) return;
    if (!byName[c.casa]) byName[c.casa] = [];
    byName[c.casa].push(c);
  });

  const names = Object.keys(byName).sort();
  if (!names.length) {
    return `<div class="empty-state">Aún no hay reportes de Casas de Esperanza en este periodo.</div>`;
  }

  return names.map(name => {
    const all = byName[name].filter(c => c.fecha).sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
    const enRango = all.filter(c => inRange(c.fecha, start, end));
    if (!enRango.length) return '';

    const asistencias = enRango.map(c => c.total_asistentes);
    const mitad = Math.floor(enRango.length / 2) || 1;
    const promInicio = enRango.slice(0, mitad).reduce((s, c) => s + c.total_asistentes, 0) / mitad;
    const promFin = enRango.slice(-mitad).reduce((s, c) => s + c.total_asistentes, 0) / mitad;
    let trend = 'flat';
    if (promFin > promInicio * 1.1) trend = 'up';
    else if (promFin < promInicio * 0.9) trend = 'down';

    const ultimoReporte = new Date(all[all.length - 1].fecha);
    const diasDesdeUltimo = (Date.now() - ultimoReporte.getTime()) / 86400000;
    const reportedRecently = diasDesdeUltimo <= 12;

    const num = enRango.reduce((s, c) => s + c.segunda_vez_count, 0);
    const den = enRango.reduce((s, c) => s + (c.hubo_visitantes_nuevos ? c.visitantes_nuevos_count : 0), 0);
    const ratio = pct(num, den);

    const color = casaHealthColor(trend, reportedRecently);
    const trendLabel = { up: 'Creciendo', down: 'Bajando', flat: 'Estable' }[trend];

    let nota;
    if (!reportedRecently) {
      nota = `Sin reporte en ${Math.round(diasDesdeUltimo)} días — dar seguimiento con el líder.`;
    } else if (trend === 'down') {
      nota = `Asistencia a la baja en las últimas reuniones.`;
    } else if (den > 0 && ratio !== null && ratio < 30) {
      nota = `Pocos visitantes regresan una segunda vez — considerar seguimiento más cercano.`;
    } else {
      nota = `Reportando con regularidad, asistencia ${trendLabel.toLowerCase()}.`;
    }

    let notaTag = '';
    const insight = insightByCasa[name];
    if (insight && insight.insight_text) {
      const diasDesdeInsight = insight.generated_at ? (Date.now() - new Date(insight.generated_at).getTime()) / 86400000 : Infinity;
      if (diasDesdeInsight <= 9) {
        nota = insight.insight_text;
        notaTag = '<span class="ia-tag">✦ IA</span> ';
      }
    }

    return `
      <div class="casa-card semaforo-${color}" data-casa="${escapeHtml(name)}">
        <div class="casa-header">
          <div class="casa-nombre">${name}</div>
          <div class="dot semaforo-${color}"></div>
        </div>
        <div class="casa-metrics">
          <span>Asistencia: <b>${trendLabel}</b></span>
          <span>Últ. reporte: <b>${reportedRecently ? 'a tiempo' : Math.round(diasDesdeUltimo) + 'd'}</b></span>
          <span>2ª vez: <b>${fmtPct(ratio)}</b></span>
        </div>
        <div class="casa-nota">${notaTag}${nota}</div>
      </div>`;
  }).join('');
}

function computeNoHanVuelto(visitasPersonas, windowDays) {
  // One row per person = their MOST RECENT known visit (dedupe by casa+nombre_normalizado).
  const ultimaVisita = {};
  (visitasPersonas || []).forEach(v => {
    if (!v.casa || !v.nombre_normalizado || !v.fecha) return;
    const key = v.casa + '|' + v.nombre_normalizado;
    if (!ultimaVisita[key] || new Date(v.fecha) > new Date(ultimaVisita[key].fecha)) {
      ultimaVisita[key] = { casa: v.casa, nombre: v.nombre, telefono: v.telefono, fecha: v.fecha };
    }
  });
  const now = Date.now();
  const grouped = {};
  Object.values(ultimaVisita).forEach(p => {
    const dias = Math.round((now - new Date(p.fecha).getTime()) / 86400000);
    if (dias < windowDays) return;
    if (!grouped[p.casa]) grouped[p.casa] = [];
    grouped[p.casa].push({ ...p, dias });
  });
  Object.values(grouped).forEach(list => list.sort((a, b) => b.dias - a.dias));
  return grouped;
}

function waLink(telefono) {
  if (!telefono) return null;
  let n = String(telefono).replace(/\D/g, '');
  if (!n) return null;
  if (n.length === 10) n = '52' + n;
  return `https://wa.me/${n}`;
}

function renderNoHanVuelto(grouped) {
  const nombres = Object.keys(grouped).sort();
  if (!nombres.length) {
    return `<div class="empty-state">Nadie pendiente de seguimiento por ahora 🎉</div>`;
  }
  return nombres.map(casa => {
    const personas = grouped[casa];
    return `
      <div class="casa-card" data-casa="${escapeHtml(casa)}">
        <div class="casa-header"><div class="casa-nombre">${casa}</div></div>
        ${personas.map(p => {
          const link = waLink(p.telefono);
          return `<div class="persona-row">
            <span>${escapeHtml(p.nombre)}</span>
            <span class="persona-dias">${p.dias}d${link ? ` · <a href="${link}" target="_blank">WhatsApp</a>` : ''}</span>
          </div>`;
        }).join('')}
      </div>`;
  }).join('');
}

function renderMensajes(mensajes) {
  if (!mensajes || !mensajes.length) {
    return `<div class="empty-state">No hay mensajes nuevos esta semana.</div>`;
  }

  return mensajes.map(m => {
    const fecha = m.generated_at ? new Date(m.generated_at).toLocaleDateString('es-MX', { month: 'short', day: 'numeric', year: '2-digit' }) : '';
    const waLink = m.wa_link ? `<a href="${m.wa_link}" target="_blank">Abrir WhatsApp</a>` : '';
    return `
      <div class="mensaje-card">
        <div class="mensaje-nombre">${m.nombre || 'Visitante'}</div>
        <div class="mensaje-actions">
          ${waLink}
          <button class="copy-btn" data-mensaje="${escapeHtml(m.mensaje)}">Copiar mensaje</button>
        </div>
        <div class="mensaje-texto">${escapeHtml(m.mensaje)}</div>
        <div class="mensaje-fecha">${fecha}</div>
      </div>`;
  }).join('');
}

function escapeHtml(text) {
  if (!text) return '';
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, c => map[c]);
}

function showCopyFeedback() {
  let feedback = document.getElementById('copyFeedback');
  if (!feedback) {
    feedback = document.createElement('div');
    feedback.id = 'copyFeedback';
    feedback.className = 'copy-feedback';
    feedback.textContent = 'Copiado ✓';
    document.body.appendChild(feedback);
  }
  feedback.style.display = 'block';
  setTimeout(() => { feedback.style.display = 'none'; }, 1500);
}

function renderCasaDetail(name, casas, insights, visitasPersonas, start, end) {
  const reports = casas.filter(c => c.casa === name && c.fecha).sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
  const enRango = reports.filter(c => inRange(c.fecha, start, end));
  const asistPersonas = (visitasPersonas || []).filter(v => v.casa === name && v.fecha && inRange(v.fecha, start, end)).sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
  const insight = (insights || []).find(i => i.casa === name);

  if (!enRango.length) {
    return `
      <a class="back-link" onclick="closeCasaDetail()">&larr; Volver a Casas de Esperanza</a>
      <div class="detail-header"><h1>${escapeHtml(name)}</h1></div>
      <div class="empty-state">Sin reportes de esta Casa en el periodo seleccionado.</div>`;
  }

  const ultimo = enRango[enRango.length - 1];
  const mitad = Math.floor(enRango.length / 2) || 1;
  const promInicio = enRango.slice(0, mitad).reduce((s, c) => s + c.total_asistentes, 0) / mitad;
  const promFin = enRango.slice(-mitad).reduce((s, c) => s + c.total_asistentes, 0) / mitad;
  let trend = 'flat';
  if (promFin > promInicio * 1.1) trend = 'up';
  else if (promFin < promInicio * 0.9) trend = 'down';
  const trendLabel = { up: 'Creciendo', down: 'Bajando', flat: 'Estable' }[trend];

  const ultimoReporte = new Date(reports[reports.length - 1].fecha);
  const diasDesdeUltimo = Math.round((Date.now() - ultimoReporte.getTime()) / 86400000);
  const reportedRecently = diasDesdeUltimo <= 12;
  const color = casaHealthColor(trend, reportedRecently);

  const numRet = enRango.reduce((s, c) => s + (c.segunda_vez_count || 0), 0);
  const denRet = enRango.reduce((s, c) => s + (c.hubo_visitantes_nuevos ? c.visitantes_nuevos_count : 0), 0);
  const ratio = pct(numRet, denRet);
  const totalNuevos = enRango.reduce((s, c) => s + (c.visitantes_nuevos_count || 0), 0);
  const promAsistencia = Math.round(enRango.reduce((s, c) => s + c.total_asistentes, 0) / enRango.length);

  let saludTexto;
  if (!reportedRecently) {
    saludTexto = `Sin reporte en ${diasDesdeUltimo} días — dar seguimiento con el líder.`;
  } else if (trend === 'down') {
    saludTexto = 'Asistencia a la baja en las últimas reuniones.';
  } else if (denRet > 0 && ratio !== null && ratio < 30) {
    saludTexto = 'Pocos visitantes regresan una segunda vez — considerar seguimiento más cercano.';
  } else {
    saludTexto = `Reportando con regularidad, asistencia ${trendLabel.toLowerCase()}.`;
  }
  const saludLabel = { verde: 'Saludable', ambar: 'Atención', rojo: 'Requiere seguimiento' }[color];

  const conteoSpark = sparkline(enRango.map(c => c.total_asistentes), '#AB9256');
  const conteoRows = enRango.slice().reverse().map(c => `
    <div class="persona-row">
      <span>${new Date(c.fecha).toLocaleDateString('es-MX', { month: 'short', day: 'numeric', year: '2-digit' })}</span>
      <span><b>${c.total_asistentes}</b> asistentes</span>
    </div>`).join('');

  const hombres = ultimo.hombres || 0, mujeres = ultimo.mujeres || 0, ninos = ultimo.ninos || 0;
  const totalComp = hombres + mujeres + ninos || 1;

  const asistTableRows = asistPersonas.map(p => {
    const link = waLink(p.telefono);
    return `<tr>
      <td>${escapeHtml(p.nombre)}</td>
      <td>${link ? `<a href="${link}" target="_blank">${escapeHtml(p.telefono)}</a>` : (p.telefono ? escapeHtml(p.telefono) : '—')}</td>
      <td>${new Date(p.fecha).toLocaleDateString('es-MX', { month: 'short', day: 'numeric', year: '2-digit' })}</td>
    </tr>`;
  }).join('');

  const insightDias = insight && insight.generated_at ? Math.round((Date.now() - new Date(insight.generated_at).getTime()) / 86400000) : null;
  const insightHtml = (insight && insight.insight_text)
    ? `<div class="insight-box">
        <span class="ia-tag">✦ IA</span>
        <div>
          <div class="insight-texto">${escapeHtml(insight.insight_text)}</div>
          <div class="insight-fecha">${insightDias !== null ? `Generado hace ${insightDias}d` : ''}</div>
        </div>
      </div>`
    : `<div class="empty-state">Sin sugerencia de IA disponible todavía para esta Casa.</div>`;

  return `
    <a class="back-link" onclick="closeCasaDetail()">&larr; Volver a Casas de Esperanza</a>
    <div class="detail-header"><h1>${escapeHtml(name)}</h1><div class="dot semaforo-${color}"></div></div>
    <div class="detail-subtitle">${enRango.length} reporte${enRango.length === 1 ? '' : 's'} en el periodo seleccionado</div>

    <div class="detail-section">
      <h3>Salud</h3>
      <div class="salud-banner semaforo-${color}">
        <div class="dot semaforo-${color}"></div>
        <div>
          <div class="salud-label">${saludLabel}</div>
          <div class="salud-texto">${saludTexto}</div>
        </div>
      </div>
    </div>

    <div class="detail-section">
      <h3>Sugerencias de IA</h3>
      ${insightHtml}
    </div>

    <div class="detail-section">
      <h3>Conteo</h3>
      ${conteoSpark}
      ${conteoRows}
    </div>

    <div class="detail-section">
      <h3>Composición <span style="font-weight:400;color:var(--warm-gray);font-size:12px;">(último reporte, ${new Date(ultimo.fecha).toLocaleDateString('es-MX', { month: 'short', day: 'numeric' })})</span></h3>
      <div class="comp-bar">
        <span class="comp-hombres" style="width:${(hombres / totalComp) * 100}%"></span>
        <span class="comp-mujeres" style="width:${(mujeres / totalComp) * 100}%"></span>
        <span class="comp-ninos" style="width:${(ninos / totalComp) * 100}%"></span>
      </div>
      <div class="comp-legend">
        <span><span class="swatch" style="background:var(--bronze)"></span>Hombres: <b>${hombres}</b></span>
        <span><span class="swatch" style="background:var(--coral)"></span>Mujeres: <b>${mujeres}</b></span>
        <span><span class="swatch" style="background:var(--gold)"></span>Niños: <b>${ninos}</b></span>
      </div>
    </div>

    <div class="detail-section">
      <h3>Estadísticas</h3>
      <div class="stat-grid">
        <div class="stat-item"><div class="stat-value">${promAsistencia}</div><div class="stat-label">Asistencia promedio</div></div>
        <div class="stat-item"><div class="stat-value">${fmtPct(ratio)}</div><div class="stat-label">Retención 2ª vez</div></div>
        <div class="stat-item"><div class="stat-value">${totalNuevos}</div><div class="stat-label">Visitantes nuevos</div></div>
        <div class="stat-item"><div class="stat-value">${diasDesdeUltimo}d</div><div class="stat-label">Desde último reporte</div></div>
      </div>
    </div>

    <div class="detail-section">
      <h3>Tabla de asistencia <span style="font-weight:400;color:var(--warm-gray);font-size:12px;">(${asistPersonas.length} registros en el periodo)</span></h3>
      ${asistTableRows ? `
        <table class="asistencia-table">
          <thead><tr><th>Nombre</th><th>Teléfono</th><th>Fecha</th></tr></thead>
          <tbody>${asistTableRows}</tbody>
        </table>` : `<div class="empty-state">No hay registros de asistencia por persona en este periodo.</div>`}
    </div>
  `;
}

let currentCasa = null;

function casaFromHash() {
  const m = location.hash.match(/^#casa\/(.+)$/);
  return m ? decodeURIComponent(m[1]) : null;
}

function openCasaDetail(name) {
  if (location.hash !== '#casa/' + encodeURIComponent(name)) {
    location.hash = 'casa/' + encodeURIComponent(name);
  }
  currentCasa = name;
  renderCasaDetailView();
}

function closeCasaDetail() {
  currentCasa = null;
  if (location.hash) location.hash = '';
  renderCasaDetailView();
}

function renderCasaDetailView() {
  const content = document.getElementById('content');
  const detail = document.getElementById('casaDetail');
  if (currentCasa && RAW) {
    const weeks = parseInt(document.getElementById('rango').value, 10);
    const end = new Date();
    const start = weeksAgo(weeks);
    const { casas = [], insights = [], visitasPersonas = [] } = RAW;
    detail.innerHTML = renderCasaDetail(currentCasa, casas, insights, visitasPersonas, start, end);
    content.style.display = 'none';
    detail.style.display = 'block';
    window.scrollTo(0, 0);
  } else {
    detail.style.display = 'none';
    content.style.display = 'block';
  }
}

document.addEventListener('click', function(e) {
  const card = e.target.closest('[data-casa]');
  if (card) openCasaDetail(card.getAttribute('data-casa'));
});

window.addEventListener('hashchange', function() {
  currentCasa = casaFromHash();
  renderCasaDetailView();
});

function render() {
  const weeks = parseInt(document.getElementById('rango').value, 10);
  const end = new Date();
  const start = weeksAgo(weeks);
  const prevStart = weeksAgo(weeks * 2);
  const prevEnd = start;

  const { visitantes = [], casas = [], asistencia = [], insights = [], mensajes = [], visitasPersonas = [] } = RAW;

  const retencion = computeRetencion(casas, start, end);
  const retencionPrev = computeRetencion(casas, prevStart, prevEnd);
  const participacion = computeParticipacion(casas, asistencia, start, end);
  const conexion = computeConexion(visitantes, casas, start, end);

  const retSpark = weeklyBuckets(casas.filter(c => c.hubo_visitantes_nuevos), 'fecha', c => c.visitantes_nuevos_count ? (c.segunda_vez_count / c.visitantes_nuevos_count) * 100 : 0, start, end);
  const partSpark = weeklyBuckets(casas, 'fecha', c => c.total_asistentes, start, end);

  document.getElementById('kpiRow').innerHTML =
    renderKpi('Retención', 'Segunda vez ÷ visitante nuevo (Casas de Esperanza)', retencion.value, retSpark, '#C87F7F') +
    renderKpi('Participación en Casas', 'Asistencia total a Casas ÷ asistencia dominical total', participacion.value, partSpark, '#E0C34B') +
    renderKpi('Conexión en 6 semanas', 'Nuevos visitantes que llegaron a una Casa en 2–9 semanas', conexion.value, null, '#7BA88A');

  document.getElementById('casasGrid').innerHTML = renderCasas(casas, insights, start, end);
  document.getElementById('noHanVueltoGrid').innerHTML = renderNoHanVuelto(computeNoHanVuelto(visitasPersonas || [], 21));
  document.getElementById('mensajesGrid').innerHTML = renderMensajes(mensajes);

  // Attach copy button listeners
  document.querySelectorAll('.copy-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const texto = this.getAttribute('data-mensaje');
      navigator.clipboard.writeText(texto).then(() => {
        showCopyFeedback();
      }).catch(err => {
        console.error('Error al copiar:', err);
      });
    });
  });

  if (currentCasa) renderCasaDetailView();
}

async function init() {
  try {
    const res = await fetch(API);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    RAW = await res.json();
    document.getElementById('loading').style.display = 'none';
    document.getElementById('content').style.display = 'block';
    currentCasa = casaFromHash();
    render();
    renderCasaDetailView();
    document.getElementById('rango').addEventListener('change', render);
  } catch (err) {
    document.getElementById('loading').style.display = 'none';
    const box = document.getElementById('errorBox');
    box.style.display = 'block';
    box.textContent = 'No se pudieron cargar los datos. Intenta recargar la página.';
    console.error(err);
  }
}

init();
</script>

</body>
</html>
