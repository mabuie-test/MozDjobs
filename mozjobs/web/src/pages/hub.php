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
      <li>🏠 Página inicial</li>
      <li>👥 Grupos de profissionais</li>
      <li>💼 Vagas seguidas</li>
      <li>⭐ Serviços guardados</li>
    </ul>
    <h4 style="margin-top:14px">Sugestões</h4>
    <form id="followForm" class="form-grid">
      <input name="follower_id" value="1" placeholder="Seu ID"/>
      <input name="followed_id" placeholder="ID para seguir"/>
      <button class="btn secondary">Seguir</button>
    </form>
  </aside>

  <section class="feed-center">
    <article class="card stories-wrap">
      <div class="stories-head">
        <h3>Stories</h3>
        <button class="btn secondary" onclick="loadStories()">Atualizar</button>
      </div>
      <form id="storyForm" class="two" style="margin-bottom:10px">
        <input name="user_id" value="1" placeholder="ID user"/>
        <input name="user_name" value="Utilizador MozJobs" placeholder="Nome"/>
        <input name="text" placeholder="Texto curto da story"/>
        <input name="bg" placeholder="#1d4ed8"/>
        <button class="btn">Publicar story</button>
      </form>
      <div id="stories" class="stories-row"></div>
    </article>

    <article class="card composer">
      <h3>Criar publicação</h3>
      <form id="postForm" class="form-grid">
        <div class="two">
          <input name="author_id" placeholder="ID autor" value="1"/>
          <input name="author_name" placeholder="Nome" value="Utilizador MozJobs"/>
        </div>
        <div class="two">
          <select name="post_type"><option value="status">Status</option><option value="job">Vaga</option><option value="service">Serviço</option></select>
          <input name="media_url" placeholder="URL de imagem (opcional)"/>
        </div>
        <textarea name="content" placeholder="Partilhe atualização, dica de carreira ou oportunidade..."></textarea>
        <button class="btn">Publicar</button>
      </form>
    </article>

    <section id="feedPosts" class="feed-stream"></section>
  </section>

  <aside class="card feed-right">
    <h3>Tendências</h3>
    <p class="muted">#Programacao #Design #Freelance #RemoteWork #Mozjobs</p>
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
async function loadStories(){
  const data = await api('/stories',{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('stories').innerHTML = items.map(s=>`<article class="story-card" style="background:${s.bg || '#1d4ed8'}"><strong>${s.user_name || 'User'}</strong><p>${s.text || ''}</p></article>`).join('') || '<p class="muted">Sem stories.</p>';
}

async function loadPosts(){
  const data = await api('/feed',{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('feedPosts').innerHTML = items.map(post => {
    const badge = post.post_type ? `<span class="pill">${post.post_type}</span>` : '';
    return `
      <article class="card post-card">
        <div class="post-header">
          <div><strong>${post.author_name || 'Utilizador'}</strong><p class="muted">${post.created_at || ''}</p></div>
          <div>${badge}</div>
        </div>
        <p>${post.content || ''}</p>
        ${post.media_url ? `<img src="${post.media_url}" alt="media" class="post-media"/>` : ''}
        <div class="post-actions">
          <button class="btn secondary" onclick="reactPost(${post.id}, 'like')">👍 Like (${post.reactions_count || 0})</button>
          <button class="btn secondary" onclick="reactPost(${post.id}, 'love')">❤️ Love</button>
          <button class="btn secondary" onclick="commentPost(${post.id})">💬 Comentário (${post.comments_count || 0})</button>
        </div>
        <div class="post-comments">${(post.comments||[]).map(c=>`<p class='muted'>• ${c.comment}</p>`).join('') || ''}</div>
      </article>
    `;
  }).join('') || '<article class="card">Sem publicações ainda.</article>';
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

document.getElementById('storyForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target).entries());
  const data = await api('/stories',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
  if(data.saved){ e.target.reset(); }
  loadStories();
});

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

document.getElementById('followForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target).entries());
  const data = await api('/follows',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
  alert(data.saved ? 'Agora estás a seguir este perfil!' : (data.error||'Erro'));
});

loadStories();
loadPosts();
</script>
</body>
</html>
