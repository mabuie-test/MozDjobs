<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Serviços</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Serviços</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/jobs/index.php">Vagas</a></nav></div></header>
<main class="container"><section class="card"><h2>Marketplace de serviços</h2><div class="two"><input id="q" placeholder="Ex: Design, Marketing, Programação"/><button class="btn" onclick="loadServices()">Buscar</button></div></section><section id="list" class="grid" style="margin-top:14px"></section></main>
<script src="/app.js"></script>
<script>
async function loadServices(){ const q=document.getElementById('q').value.trim(); const data=await api('/services'+(q?`?q=${encodeURIComponent(q)}`:'')); document.getElementById('list').innerHTML=(data.items||[]).map(s=>`<article class='card'><h3>${s.title||'Serviço'}</h3><p class='muted'>${s.description||''}</p><p><strong>${s.price||0} MZN</strong></p><a class='btn secondary' href='/services/service-detail.php?id=${s.id}'>Contratar</a></article>`).join('')||'<div class="card">Sem serviços.</div>'; }
loadServices();
</script>
</body></html>
