<!doctype html>
<html lang="pt">
<head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Vagas - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Vagas</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/services/index.php">Serviços</a></nav></div></header>
<main class="container">
<section class="card"><h2>Pesquisar vagas</h2><div class="two"><input id="q" placeholder="Ex: Programador PHP"/><button class="btn" onclick="loadJobs()">Buscar</button></div></section>
<section id="list" class="grid" style="margin-top:14px"></section>
</main>
<script src="/app.js"></script>
<script>
async function loadJobs(){
  const q = document.getElementById('q').value.trim();
  const data = await api('/jobs'+(q?`?q=${encodeURIComponent(q)}`:''));
  document.getElementById('list').innerHTML = (data.items||[]).map(j=>`<article class='card'><h3>${j.title||'Sem título'}</h3><p class='muted'>${j.description||''}</p><a class='btn secondary' href='/jobs/job-detail.php?id=${j.id}'>Detalhes</a></article>`).join('') || '<div class="card">Sem vagas no momento.</div>';
}
loadJobs();
</script>
</body>
</html>
