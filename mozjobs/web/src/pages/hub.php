<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Hub - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Hub MozJobs</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/jobs/index.php">Vagas</a><a href="/services/index.php">Serviços</a></nav></div></header>
<main class="container">
  <section class="grid">
    <article class="card"><h3>Favoritos</h3><p class="muted">Guarde vagas e serviços para acompanhar depois.</p><form id="favForm" class="form-grid"><div class="two"><input name="user_id" placeholder="ID user"/><select name="entity_type"><option value="job">Vaga</option><option value="service">Serviço</option></select></div><input name="entity_id" placeholder="ID entidade"/><button class="btn">Guardar favorito</button></form></article>
    <article class="card"><h3>Notificações</h3><p class="muted">Centro de alertas da conta.</p><form id="notifForm" class="form-grid"><input name="user_id" placeholder="ID user"/><input name="title" placeholder="Título"/><textarea name="body" placeholder="Mensagem"></textarea><button class="btn success">Criar notificação</button></form></article>
  </section>
  <section class="card" style="margin-top:14px"><h3>Feed</h3><button class="btn secondary" onclick="loadFeed()">Atualizar feed</button><div id="feed" class="grid" style="margin-top:10px"></div></section>
</main>
<script src="/app.js"></script>
<script>
async function loadFeed(){
  const userId = prompt('ID do utilizador para feed', '1');
  if(!userId) return;
  const [favorites, notifications] = await Promise.all([
    api(`/favorites?user_id=${userId}`,{headers:{...authHeaders()}}),
    api(`/notifications?user_id=${userId}`,{headers:{...authHeaders()}})
  ]);
  const favCards = (favorites.items||[]).map(f=>`<article class='card'><h4>⭐ Favorito</h4><p class='muted'>${f.entity_type} #${f.entity_id}</p></article>`).join('');
  const notifCards = (notifications.items||[]).map(n=>`<article class='card'><h4>${n.title}</h4><p class='muted'>${n.body}</p><p>${n.read ? 'Lida' : 'Não lida'}</p></article>`).join('');
  document.getElementById('feed').innerHTML = favCards + notifCards || '<article class="card">Sem dados</article>';
}

document.getElementById('favForm').addEventListener('submit', async (e)=>{
 e.preventDefault();
 const payload = Object.fromEntries(new FormData(e.target).entries());
 const data = await api('/favorites',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
 alert(data.saved ? 'Favorito guardado!' : (data.error||'Erro'));
});

document.getElementById('notifForm').addEventListener('submit', async (e)=>{
 e.preventDefault();
 const payload = Object.fromEntries(new FormData(e.target).entries());
 const data = await api('/notifications',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
 alert(data.saved ? 'Notificação criada!' : (data.error||'Erro'));
});
</script>
</body></html>
