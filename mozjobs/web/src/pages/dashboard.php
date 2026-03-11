<!doctype html>
<html lang="pt">
<head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"/><title>Dashboard - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">MozJobs Dashboard</div><nav class="nav"><a href="/jobs/index.php">Vagas</a><a href="/services/index.php">Serviços</a><a href="/profile/profile.php">Perfil</a></nav></div></header>
<main class="container">
<section class="grid">
  <article class="card"><h3>Publicar vaga</h3><p class="muted">Crie uma vaga e receba candidaturas.</p><a href="/jobs/index.php" class="btn">Gerir vagas</a></article>
  <article class="card"><h3>Criar serviço</h3><p class="muted">Venda seus serviços com preço fixo.</p><a href="/services/index.php" class="btn">Gerir serviços</a></article>
  <article class="card"><h3>Chat e pedidos</h3><p class="muted">Acompanhe mensagens e entregas.</p><a href="/services/service-detail.php" class="btn">Abrir chat</a></article>
</section>
<section class="card" style="margin-top:16px"><h3>Resumo rápido</h3><div id="summary" class="muted">A carregar...</div></section>
</main>
<script src="/app.js"></script>
<script>
(async()=>{
  const jobs = await api('/jobs');
  const services = await api('/services');
  document.getElementById('summary').textContent = `Vagas: ${(jobs.items||[]).length} | Serviços: ${(services.items||[]).length}`;
})();
</script>
</body>
</html>
