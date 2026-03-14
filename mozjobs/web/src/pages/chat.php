<!doctype html>
<html lang="pt"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Chat - MozJobs</title><link rel="stylesheet" href="/assets.css"/></head>
<body>
<header class="header"><div class="wrap"><div class="brand">Chat de projeto</div><nav class="nav"><a href="/dashboard.php">Dashboard</a></nav></div></header>
<main class="container">
  <section class="card">
    <h2>Mensagens por pedido</h2>
    <div class="two">
      <label>ID Pedido<input id="order_id" value="1"/></label>
      <label>ID Remetente<input id="sender_id" value="1"/></label>
    </div>
    <div id="messages" class="card" style="margin-top:12px;max-height:260px;overflow:auto;background:#f8fbff"></div>
    <form id="sendForm" class="two" style="margin-top:12px">
      <input id="message" placeholder="Escreva a mensagem" required/>
      <button class="btn">Enviar</button>
    </form>
  </section>
</main>
<script src="/app.js"></script>
<script>
async function loadMessages(){
  const orderId = document.getElementById('order_id').value;
  const data = await api(`/chat?order_id=${orderId}`,{headers:{...authHeaders()}});
  document.getElementById('messages').innerHTML = (data.items||[]).map(m=>`<p><strong>#${m.sender_id}:</strong> ${m.message}</p>`).join('') || '<p class="muted">Sem mensagens.</p>';
}

document.getElementById('sendForm').addEventListener('submit', async (e)=>{
  e.preventDefault();
  const payload = {order_id:+document.getElementById('order_id').value,sender_id:+document.getElementById('sender_id').value,message:document.getElementById('message').value};
  const data = await api('/chat',{method:'POST',headers:{'Content-Type':'application/json',...authHeaders()},body:JSON.stringify(payload)});
  if(data.saved){ document.getElementById('message').value=''; loadMessages(); }
});
loadMessages();
</script>
</body></html>
