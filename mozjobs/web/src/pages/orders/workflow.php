<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Workflow de Pedidos - MozJobs</title>
  <link rel="stylesheet" href="/assets.css"/>
</head>
<body>
<header class="header"><div class="wrap"><div class="brand">Workflow de Pedidos</div><nav class="nav"><a href="/dashboard.php">Dashboard</a><a href="/services/service-detail.php">Contratar</a><a href="/admin.php">Admin</a></nav></div></header>
<main class="container grid">
  <section class="card">
    <h3>1) Submeter entrega</h3>
    <form id="submitDelivery" class="form-grid">
      <input name="order_id" placeholder="Order ID" required/>
      <input name="professional_id" placeholder="Professional ID" required/>
      <textarea name="notes" placeholder="Notas da entrega" required></textarea>
      <button class="btn">Submeter</button>
    </form>
  </section>

  <section class="card">
    <h3>2) Rever entrega</h3>
    <form id="reviewDelivery" class="form-grid">
      <input name="order_id" placeholder="Order ID" required/>
      <input name="client_id" placeholder="Client ID" required/>
      <input type="hidden" name="decision" id="decisionInput" value="accept"/>
      <div class="decision-segmented">
        <button type="button" class="is-active" data-decision="accept">Aceitar</button>
        <button type="button" data-decision="reject">Rejeitar</button>
      </div>
      <textarea name="notes" placeholder="Notas de revisão"></textarea>
      <button class="btn">Rever</button>
    </form>
  </section>

  <section class="card">
    <h3>3) Libertar escrow (admin)</h3>
    <form id="releaseEscrow" class="form-grid">
      <input name="payment_id" placeholder="Payment ID" required/>
      <button class="btn success">Libertar</button>
    </form>
  </section>

  <section class="card">
    <h3>4) Timeline do pedido</h3>
    <form id="timelineForm" class="two">
      <input name="order_id" placeholder="Order ID" required/>
      <button class="btn secondary">Carregar timeline</button>
    </form>
    <pre id="timeline" class="muted" style="white-space:pre-wrap"></pre>
  </section>
</main>
<div id="toast" class="toast"></div>
<script src="/app.js"></script>
<script>
function notify(message){
  const el = document.getElementById('toast');
  el.textContent = message;
  el.classList.add('show');
  setTimeout(() => el.classList.remove('show'), 1800);
}

function bindSubmit(id, path){
  document.getElementById(id).addEventListener('submit', async (e)=>{
    e.preventDefault();
    const payload = Object.fromEntries(new FormData(e.target).entries());
    const data = await api(path, {method:'POST', headers:{'Content-Type':'application/json', ...authHeaders()}, body:JSON.stringify(payload)});
    notify(data.error || 'Operação executada com sucesso');
  });
}

bindSubmit('submitDelivery', '/orders/delivery/submit');
bindSubmit('reviewDelivery', '/orders/delivery/review');
bindSubmit('releaseEscrow', '/payments/release');

document.querySelectorAll('[data-decision]').forEach((btn)=>{
  btn.addEventListener('click', ()=>{
    document.querySelectorAll('[data-decision]').forEach((b)=>b.classList.remove('is-active'));
    btn.classList.add('is-active');
    document.getElementById('decisionInput').value = btn.dataset.decision;
  });
});

document.getElementById('timelineForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target).entries());
  const data = await api('/orders/timeline?order_id='+encodeURIComponent(payload.order_id), {headers:{...authHeaders()}});
  document.getElementById('timeline').textContent = JSON.stringify(data.items || data, null, 2);
});
</script>
</body>
</html>
