<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Detalhe da vaga</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Detalhe da vaga</div><nav class="nav"><a href="/jobs/index.php">Voltar</a></nav></div></header>
<main class="container"><section class="card"><h2>Candidatar-se</h2><form id="apply" class="form-grid"><div class="two"><label>ID da vaga<input name="job_id" required/></label><label>ID profissional<input name="professional_id" required/></label></div><label>Mensagem<textarea name="cover_letter" required></textarea></label><button class="btn">Enviar candidatura</button><p id="result" class="muted"></p></form></section></main>
<script src="/app.js"></script>
<script>
document.getElementById('apply').addEventListener('submit',async(e)=>{e.preventDefault(); const payload=Object.fromEntries(new FormData(e.target).entries()); const data=await api('/jobs/apply',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); document.getElementById('result').textContent=data.saved?'Candidatura enviada!':(data.error||'Erro');});
</script>
</body></html>
