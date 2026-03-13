<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Perfil</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Meu Perfil</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/services/index.php">Serviços</a></nav></div></header>
<main class="container">
  <section class="card">
    <h2>Completar perfil profissional</h2>
    <form class="form-grid" id="profileForm">
      <div class="two"><label>ID Utilizador<input name="user_id" required/></label><label>Localização<input name="location" placeholder="Maputo"/></label></div>
      <label>Skills<input name="skills" placeholder="PHP, Flutter, UI/UX"/></label>
      <label>Portfolio URL<input name="portfolio_url"/></label>
      <button class="btn">Guardar perfil</button>
      <p id="result" class="muted"></p>
    </form>
  </section>

  <section class="card" style="margin-top:14px">
    <h3>Reputação</h3>
    <div class="two">
      <label>ID profissional para resumo<input id="reviewed_id" placeholder="Ex: 2"/></label>
      <button class="btn success" onclick="loadSummary()">Consultar avaliação</button>
    </div>
    <p id="summary" class="muted">Sem dados.</p>
  </section>
</main>
<script src="/app.js"></script>
<script>
document.getElementById('profileForm').addEventListener('submit',async(e)=>{e.preventDefault(); const payload=Object.fromEntries(new FormData(e.target).entries()); const data=await api('/profiles',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); document.getElementById('result').textContent=data.saved?'Perfil guardado!':(data.error||'Erro');});
async function loadSummary(){ const id = document.getElementById('reviewed_id').value; if(!id){return;} const data = await api(`/reviews/summary?reviewed_id=${id}`); document.getElementById('summary').textContent = data.error ? data.error : `Média: ${data.average_rating} (${data.reviews_count} avaliações)`; }
</script>
</body></html>
