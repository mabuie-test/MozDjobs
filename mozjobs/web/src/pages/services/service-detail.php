<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Contratar serviço</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Contratar serviço</div><nav class="nav"><a href="/services/index.php">Voltar</a></nav></div></header>
<main class="container"><section class="card"><h2>Abrir pedido + escrow</h2><form id="orderForm" class="form-grid"><div class="two"><label>ID Cliente<input name="client_id" required/></label><label>ID Profissional<input name="professional_id" required/></label></div><div class="two"><label>Valor (MZN)<input name="amount" required/></label><label>Provedor<select name="provider"><option value="mpesa">M-Pesa</option><option value="emola">e-Mola</option><option value="mkesh">mKesh</option></select></label></div><button class="btn">Criar pedido e pagar</button><p id="result" class="muted"></p></form></section></main>
<script src="/app.js"></script>
<script>
document.getElementById('orderForm').addEventListener('submit',async(e)=>{e.preventDefault();const p=Object.fromEntries(new FormData(e.target).entries());
const order=await api('/orders',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({client_id:+p.client_id,professional_id:+p.professional_id,amount:+p.amount})});
if(!order.saved){document.getElementById('result').textContent=order.error||'Falha no pedido';return;}
const pay=await api('/payments/escrow',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify({order_id:order.saved.id,provider:p.provider,amount:+p.amount})});
document.getElementById('result').textContent=pay.saved?`Escrow criado. Ref: ${pay.saved.transaction_ref}`:(pay.error||'Falha no pagamento');
});
</script>
</body></html>
