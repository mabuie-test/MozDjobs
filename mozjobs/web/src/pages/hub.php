<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Hub Feed - MozJobs</title>
  <link rel="stylesheet" href="/assets.css"/>
</head>
<body>
<header class="header"><div class="wrap"><div class="brand">MozJobs Feed</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/jobs/index.php">Vagas</a><a href="/services/index.php">Serviços</a><a href="/reports.php">Relatórios</a></nav></div></header>

<main class="container feed-layout">
  <aside class="card feed-left">
    <h3>Atalhos</h3>
    <ul class="list-clean muted">
      <li>📌 Favoritos</li>
      <li>💬 Mensagens recentes</li>
      <li>💼 Vagas seguidas</li>
      <li>⭐ Serviços guardados</li>
    </ul>
  </aside>

  <section class="feed-center">
    <article class="card composer">
      <h3>Criar publicação</h3>
      <form id="postForm" class="form-grid">
        <div class="two">
          <input name="author_id" placeholder="ID autor" value="1"/>
          <input name="author_name" placeholder="Nome" value="Utilizador MozJobs"/>
        </div>
        <textarea name="content" placeholder="Partilhe atualização, dica de carreira ou oportunidade..."></textarea>
        <input name="media_url" placeholder="URL de imagem (opcional)"/>
        <button class="btn">Publicar</button>
      </form>
    </article>

    <section id="feedPosts" class="feed-stream"></section>
  </section>

  <aside class="card feed-right">
    <h3>Tendências</h3>
    <p class="muted">#Programacao #Design #Freelance #RemoteWork</p>
    <h4>Notificações rápidas</h4>
    <form id="notifForm" class="form-grid">
      <input name="user_id" placeholder="ID user" value="1"/>
      <input name="title" placeholder="Título" value="Atualização de projeto"/>
      <textarea name="body" placeholder="Mensagem">Nova proposta recebida.</textarea>
      <button class="btn success">Criar notificação</button>
    </form>
  </aside>
</main>

<script src="/app.js"></script>
<script>
async function loadPosts(){
  const data = await api('/feed',{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('feedPosts').innerHTML = items.map(post => `
    <article class="card post-card">
      <div class="post-header">
        <div><strong>${post.author_name || 'Utilizador'}</strong><p class="muted">${post.created_at || ''}</p></div>
      </div>
      <p>${post.content || ''}</p>
      ${post.media_url ? `<img src="${post.media_url}" alt="media" class="post-media"/>` : ''}
      <div class="post-actions">
        <button class="btn secondary" onclick="reactPost(${post.id}, 'like')">👍 Like (${post.reactions_count || 0})</button>
        <button class="btn secondary" onclick="commentPost(${post.id})">💬 Comentário (${post.comments_count || 0})</button>
      </div>
      <div class="post-comments">${(post.comments||[]).map(c=>`<p class='muted'>• ${c.comment}</p>`).join('') || ''}</div>
    </article>
  `).join('') || '<article class="card">Sem publicações ainda.</article>';
}

async function reactPost(postId, type){
  const userId = prompt('Seu ID para reação', '1');
  if(!userId) return;
  await api('/feed/reactions',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({post_id:+postId,user_id:+userId,type})});
  loadPosts();
}

async function commentPost(postId){
  const userId = prompt('Seu ID', '1');
  const comment = prompt('Comentário');
  if(!userId || !comment) return;
  await api('/feed/comments',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({post_id:+postId,user_id:+userId,comment})});
  loadPosts();
}

document.getElementById('postForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target).entries());
  const data = await api('/feed/posts',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
  if(data.saved){ e.target.reset(); }
  loadPosts();
});

document.getElementById('notifForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target).entries());
  const data = await api('/notifications',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
  alert(data.saved ? 'Notificação criada!' : (data.error||'Erro'));
});

loadPosts();
</script>
</body>
</html>
