<!doctype html>
<html lang="pt">
<head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Vagas - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Vagas</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/services/index.php">Serviços</a><a href="/hub.php">Hub</a></nav></div></header>
<main class="container">
<section class="card"><h2>Pesquisar vagas</h2><div class="two"><input id="q" placeholder="Ex: Programador PHP"/><button class="btn" onclick="loadJobs()">Buscar</button></div></section>
<section id="list" class="grid" style="margin-top:14px"></section>
</main>
<script src="/app.js"></script>
<script>
async function saveFavorite(jobId){
  const userId = prompt('ID de utilizador para favoritar', '1');
  if(!userId) return;
  const data = await api('/favorites',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({user_id:+userId,entity_type:'job',entity_id:+jobId})});
  alert(data.saved ? 'Vaga guardada nos favoritos!' : (data.error || 'Erro ao favoritar'));
}
async function loadJobs(){
  const q = document.getElementById('q').value.trim();
  const data = await api('/jobs'+(q?`?q=${encodeURIComponent(q)}`:''));
  document.getElementById('list').innerHTML = (data.items||[]).map(j=>`<article class='card'><h3>${j.title||'Sem título'}</h3><p class='muted'>${j.description||''}</p><p><span class='pill'>status: ${j.status||'open'}</span></p><a class='btn secondary' href='/jobs/job-detail.php?id=${j.id}'>Detalhes</a> <button class='btn' onclick='saveFavorite(${j.id||0})'>Favoritar</button></article>`).join('') || '<div class="card">Sem vagas no momento.</div>';
}
loadJobs();
</script>
</body>
</html>
