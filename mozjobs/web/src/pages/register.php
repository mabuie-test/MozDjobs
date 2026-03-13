<!doctype html>
<html lang="pt">
<head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/><title>Registo - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">MozJobs</div><nav class="nav"><a href="/index.php">Home</a><a href="/login.php">Entrar</a></nav></div></header>
<main class="container"><section class="card" style="max-width:680px;margin:0 auto"><h2>Criar conta</h2>
<form id="registerForm" class="form-grid">
<div class="two"><label>Nome<input name="name" required/></label><label>Email<input type="email" name="email" required/></label></div>
<div class="two"><label>Senha<input type="password" name="password" required minlength="6"/></label><label>Perfil<select name="role"><option value="professional">Profissional</option><option value="client">Cliente</option><option value="company">Empresa</option></select></label></div>
<button class="btn" type="submit">Registar</button><p id="result" class="muted"></p>
</form></section></main>
<script src="/app.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', async(e)=>{
 e.preventDefault();
 const payload = Object.fromEntries(new FormData(e.target).entries());
 const data = await api('/auth/register',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
 document.getElementById('result').textContent = data.message ? 'Conta criada. Faça login.' : (data.error || 'Erro no registo');
});
</script>
</body>
</html>
