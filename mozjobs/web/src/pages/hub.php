<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Hub Feed - MozJobs</title>
  <link rel="stylesheet" href="/assets.css"/>
</head>
<body>
<header class="header">
  <div class="wrap">
    <div class="brand">MozJobs Feed</div>
    <div class="hub-top-actions">
      <input id="feedSearch" placeholder="Pesquisar no feed (#tag, nome, texto)"/>
      <nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/jobs/index.php">Vagas</a><a href="/services/index.php">Serviços</a><a href="/reports.php">Relatórios</a></nav>
    </div>
  </div>
</header>

<main class="container feed-layout">
  <aside class="card feed-left">
    <h3>Atalhos</h3>
    <ul class="list-clean muted">
      <li>🏠 Página inicial</li>
      <li>👥 Grupos de profissionais</li>
      <li>💼 Vagas seguidas</li>
      <li>⭐ Serviços guardados</li>
    </ul>
    <div class="kpi-mini"><strong id="totalPosts">0</strong><span>Posts no feed</span></div>
    <div class="kpi-mini"><strong id="totalFollows">0</strong><span>Ligações sociais</span></div>
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
        <input type="hidden" name="post_type" id="postTypeInput" value="status"/>
        <div class="chips" role="tablist" aria-label="Tipo de publicação">
          <button type="button" class="chip is-active" data-type="status">Status</button>
          <button type="button" class="chip" data-type="job">Vaga</button>
          <button type="button" class="chip" data-type="service">Serviço</button>
          <button type="button" class="chip" data-type="update">Atualização</button>
        </div>
        <input name="media_url" placeholder="URL de imagem (opcional)"/>
        <textarea name="content" placeholder="Partilhe atualização, dica de carreira ou oportunidade..."></textarea>
        <button class="btn">Publicar</button>
      </form>
    </article>

    <article class="card feed-controls">
      <div class="segmented">
        <button id="sortRecent" class="btn secondary active" onclick="setSort('recent')">Mais recentes</button>
        <button id="sortPopular" class="btn secondary" onclick="setSort('popular')">Mais populares</button>
      </div>
      <button class="btn secondary" onclick="refreshFeed()">Atualizar feed</button>
    </article>

    <section id="feedPosts" class="feed-stream"></section>
    <div class="feed-more-wrap"><button id="loadMoreBtn" class="btn secondary" onclick="loadMore()">Carregar mais</button></div>
  </section>

  <aside class="card feed-right">
    <h3>Tendências</h3>
    <ul id="trendingTags" class="list-clean muted"><li>A carregar tendências...</li></ul>
    <h4>Pessoas para seguir</h4>
    <ul id="followSuggestions" class="list-clean muted"></ul>
    <h4>Notificações rápidas</h4>
    <form id="notifForm" class="form-grid">
      <input name="user_id" placeholder="ID user" value="1"/>
      <input name="title" placeholder="Título" value="Atualização de projeto"/>
      <textarea name="body" placeholder="Mensagem">Nova proposta recebida.</textarea>
      <button class="btn success">Criar notificação</button>
    </form>
  </aside>
</main>

<div id="toast" class="toast"></div>

<script src="/app.js"></script>
<script>
const state = { commentsOpen: {}, sort: 'recent', offset: 0, limit: 10, allPosts: [] };

function notify(message){
  const el = document.getElementById('toast');
  el.textContent = message;
  el.classList.add('show');
  setTimeout(() => el.classList.remove('show'), 1800);
}

function setSort(sort){
  state.sort = sort;
  document.getElementById('sortRecent').classList.toggle('active', sort === 'recent');
  document.getElementById('sortPopular').classList.toggle('active', sort === 'popular');
  refreshFeed();
}

function applySearch(items){
  const q = (document.getElementById('feedSearch').value || '').trim().toLowerCase();
  if(!q) return items;
  return items.filter(post => (`${post.author_name || ''} ${post.content || ''} ${post.post_type || ''}`).toLowerCase().includes(q));
}

async function loadStories(){
  const data = await api('/stories',{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('stories').innerHTML = items.map(s=>`<article class="story-card" style="background:${s.bg || '#1d4ed8'}"><strong>${s.user_name || 'User'}</strong><p>${s.text || ''}</p></article>`).join('') || '<p class="muted">Sem stories.</p>';
}

function commentComposer(postId){
  return `<form class="comment-form" onsubmit="event.preventDefault();submitInlineComment(${postId}, this)"><input name="user_id" value="1" placeholder="Seu ID" required /><input name="comment" placeholder="Escreva um comentário..." required /><button class="btn secondary">Enviar</button></form>`;
}

function renderPosts(items){
  document.getElementById('feedPosts').innerHTML = items.map(post => {
    const badge = `<span class="pill">${(post.post_type || 'status').toUpperCase()}</span>`;
    const commentsHtml = (post.comments || []).map(c => `<p><strong>${c.user_name || 'Utilizador'}:</strong> ${c.comment || ''}</p>`).join('');
    const showComposer = state.commentsOpen[post.id] ? commentComposer(post.id) : '';

    return `<article class="card post-card"><div class="post-header"><div><strong>${post.author_name || 'Utilizador'}</strong><p class="muted">${post.created_at || ''}</p></div><div>${badge}</div></div><p>${post.content || ''}</p>${post.media_url ? `<img src="${post.media_url}" alt="media" class="post-media"/>` : ''}<div class="post-actions"><button class="btn secondary" onclick="reactPost(${post.id}, 'like')">👍 Like (${post.reactions_count || 0})</button><button class="btn secondary" onclick="reactPost(${post.id}, 'love')">❤️ Love</button><button class="btn secondary" onclick="toggleCommentComposer(${post.id})">💬 Comentário (${post.comments_count || 0})</button><button class="btn secondary" onclick="removeMyReaction(${post.id})">↩️ Remover reação</button></div><div class="post-comments">${commentsHtml || ''}${showComposer}</div></article>`;
  }).join('') || '<article class="card">Sem publicações para o filtro atual.</article>';
}

async function loadPosts(append=false){
  const data = await api(`/feed?limit=${state.limit}&offset=${state.offset}&sort=${state.sort}`,{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('totalPosts').textContent = data.meta?.total ?? items.length;

  if(append) state.allPosts = state.allPosts.concat(items); else state.allPosts = items;
  renderPosts(applySearch(state.allPosts));

  document.getElementById('loadMoreBtn').style.display = data.meta?.has_more ? 'inline-block' : 'none';
}

function refreshFeed(){
  state.offset = 0;
  loadPosts(false);
}

function loadMore(){
  state.offset += state.limit;
  loadPosts(true);
}

function toggleCommentComposer(postId){
  state.commentsOpen[postId] = !state.commentsOpen[postId];
  renderPosts(applySearch(state.allPosts));
}

async function submitInlineComment(postId, form){
  const payload = Object.fromEntries(new FormData(form).entries());
  await api('/feed/comments',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({post_id:+postId,user_id:+payload.user_id,comment:payload.comment})});
  state.commentsOpen[postId] = false;
  notify('Comentário publicado');
  refreshFeed();
}

async function reactPost(postId, type){
  await api('/feed/reactions',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({post_id:+postId,user_id:1,type})});
  notify('Reação enviada');
  refreshFeed();
}

async function removeMyReaction(postId){
  await api('/feed/reactions/remove',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({post_id:+postId,user_id:1})});
  notify('Reação removida');
  refreshFeed();
}

async function loadFollowInsights(){
  const data = await api('/follows?follower_id=1',{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('totalFollows').textContent = items.length;

  const suggestionsData = await api('/follows/suggestions?follower_id=1',{headers:{...authHeaders()}});
  const suggestions = suggestionsData.items || [];
  document.getElementById('followSuggestions').innerHTML = suggestions.map(u => `<li>${u.name || ('Perfil #'+u.id)} <span><button class="btn secondary" onclick="quickFollow(${u.id})">Seguir</button> <button class="btn secondary" onclick="quickUnfollow(${u.id})">Deixar</button></span></li>`).join('') || '<li>Sem sugestões.</li>';
}

async function quickFollow(id){ await api('/follows',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({follower_id:1,followed_id:id})}); notify('Agora segues este perfil'); loadFollowInsights(); }
async function quickUnfollow(id){ await api('/follows/unfollow',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({follower_id:1,followed_id:id})}); notify('Deixaste de seguir'); loadFollowInsights(); }

async function loadTrending(){
  const data = await api('/feed/trending?limit=6',{headers:{...authHeaders()}});
  const items = data.items || [];
  document.getElementById('trendingTags').innerHTML = items.map(t => `<li>${t.tag} <small>(${t.mentions})</small></li>`).join('') || '<li>Sem tendências ainda.</li>';
}

document.querySelectorAll('.chip[data-type]').forEach((chip)=>{
  chip.addEventListener('click', ()=>{
    document.querySelectorAll('.chip[data-type]').forEach(c=>c.classList.remove('is-active'));
    chip.classList.add('is-active');
    document.getElementById('postTypeInput').value = chip.dataset.type;
  });
});

document.getElementById('feedSearch').addEventListener('input', () => renderPosts(applySearch(state.allPosts)));

document.getElementById('storyForm').addEventListener('submit', async (e)=>{ e.preventDefault(); const payload = Object.fromEntries(new FormData(e.target).entries()); const data = await api('/stories',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); if(data.saved){ e.target.reset(); notify('Story publicada'); } loadStories(); });

document.getElementById('postForm').addEventListener('submit', async (e)=>{ e.preventDefault(); const payload = Object.fromEntries(new FormData(e.target).entries()); const data = await api('/feed/posts',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); if(data.saved){ e.target.reset(); notify('Publicação criada'); } refreshFeed(); loadTrending(); });

document.getElementById('notifForm').addEventListener('submit', async (e)=>{ e.preventDefault(); const payload = Object.fromEntries(new FormData(e.target).entries()); const data = await api('/notifications',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); notify(data.saved ? 'Notificação criada' : (data.error||'Erro')); });

document.getElementById('followForm').addEventListener('submit', async (e)=>{ e.preventDefault(); const payload = Object.fromEntries(new FormData(e.target).entries()); const data = await api('/follows',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); notify(data.saved ? 'Agora estás a seguir este perfil' : (data.error||'Erro')); loadFollowInsights(); });

loadStories();
refreshFeed();
loadFollowInsights();
loadTrending();
</script>
</body>
</html>
