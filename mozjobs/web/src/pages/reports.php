<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Relatórios - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Relatórios & Insights</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/admin.php">Admin</a><a href="/hub.php">Hub</a></nav></div></header>
<main class="container">
  <section class="card">
    <h2>Resumo executivo</h2>
    <p class="muted">Veja indicadores de crescimento, GMV e projeção de receita para tomada de decisão.</p>
    <button class="btn" onclick="loadOverview()">Carregar métricas</button>
    <button class="btn secondary" onclick="loadCsv()">Gerar CSV</button>
    <pre id="output" class="card" style="margin-top:12px;background:#f8fbff;white-space:pre-wrap"></pre>
  </section>
</main>
<script src="/app.js"></script>
<script>
async function loadOverview(){
  const data = await api('/reports/overview',{headers:{...authHeaders()}});
  document.getElementById('output').textContent = JSON.stringify(data,null,2);
}
async function loadCsv(){
  const data = await api('/reports/export-csv',{headers:{...authHeaders()}});
  document.getElementById('output').textContent = data.content || 'sem conteúdo';
}
</script>
</body></html>
