<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Admin - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Admin Console</div><nav class="nav"><a href="/dashboard.php">Dashboard</a></nav></div></header>
<main class="container">
  <section class="grid">
    <article class="card"><h3>KPIs</h3><p id="kpis" class="muted">A carregar...</p></article>
    <article class="card"><h3>Aprovar vaga</h3><form id="approveJob"><input name="id" placeholder="Job ID"/><button class="btn">Aprovar</button></form></article>
    <article class="card"><h3>Aprovar serviço</h3><form id="approveService"><input name="id" placeholder="Service ID"/><button class="btn">Aprovar</button></form></article>
    <article class="card"><h3>Banir utilizador</h3><form id="banUser"><input name="id" placeholder="User ID"/><button class="btn">Banir</button></form></article>
  </section>
  <section class="card" style="margin-top:12px"><h3>Resolver disputa</h3><form id="resolveDispute" class="two"><input name="dispute_id" placeholder="Dispute ID"/><input name="resolution" placeholder="Decisão"/><button class="btn">Resolver</button></form><p id="msg" class="muted"></p></section>
</main>
<script src="/app.js"></script>
<script>
async function refresh(){
 const data=await api('/admin/metrics',{headers:{...authHeaders()}});
 document.getElementById('kpis').textContent = data.error ? data.error : JSON.stringify(data);
}
function bind(formId,path){
 document.getElementById(formId).addEventListener('submit',async(e)=>{e.preventDefault();const payload=Object.fromEntries(new FormData(e.target).entries()); const data=await api(path,{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)}); document.getElementById('msg').textContent=data.message||data.error||'ok'; refresh();});
}
bind('approveJob','/admin/jobs/approve');
bind('approveService','/admin/services/approve');
bind('banUser','/admin/users/ban');
bind('resolveDispute','/admin/disputes/resolve');
refresh();
</script>
</body></html>
