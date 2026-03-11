<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Perfil</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Meu Perfil</div><nav class="nav"><a href="/dashboard.php">Dashboard</a></nav></div></header>
<main class="container"><section class="card"><h2>Completar perfil profissional</h2><form class="form-grid" id="profileForm"><div class="two"><label>ID Utilizador<input name="user_id" required/></label><label>Localização<input name="location" placeholder="Maputo"/></label></div><label>Skills<input name="skills" placeholder="PHP, Flutter, UI/UX"/></label><label>Portfolio URL<input name="portfolio_url"/></label><button class="btn">Guardar perfil</button><p id="result" class="muted"></p></form></section></main>
<script src="/app.js"></script>
<script>
document.getElementById('profileForm').addEventListener('submit',async(e)=>{e.preventDefault(); const payload=Object.fromEntries(new FormData(e.target).entries()); const data=await api('/profiles',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); document.getElementById('result').textContent=data.saved?'Perfil guardado!':(data.error||'Erro');});
</script>
</body></html>
