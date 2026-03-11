<!doctype html>
<html lang="pt">
<head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/><title>Entrar - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">MozJobs</div><nav class="nav"><a href="/index.php">Home</a><a href="/register.php">Criar conta</a></nav></div></header>
<main class="container"><section class="card" style="max-width:560px;margin:0 auto"><h2>Entrar</h2>
<form id="loginForm" class="form-grid">
  <div><label>Email<input type="email" name="email" required value="admin@mozjobs.mz"/></label></div>
  <div><label>Senha<input type="password" name="password" required value="123456"/></label></div>
  <button class="btn" type="submit">Entrar</button>
  <p id="result" class="muted"></p>
</form></section></main>
<script src="/app.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const f = new FormData(e.target);
  const payload = Object.fromEntries(f.entries());
  const data = await api('/auth/login',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
  if(data.token){ saveToken(data.token); location.href='/dashboard.php'; return; }
  document.getElementById('result').textContent = data.error || 'Falha no login';
});
</script>
</body>
</html>
