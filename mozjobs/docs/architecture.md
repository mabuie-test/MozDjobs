# Architecture

## Camadas
- `Controllers`: entradas HTTP e orquestração de casos de uso.
- `Services`: regras de auth e pagamentos (M-Pesa/e-Mola/mKesh).
- `Helpers`: validação e persistência simplificada (`JsonStore`) para MVP.
- `Middleware`: autenticação e rate-limit.

## Fluxos principais
1. Registro/Login: cria utilizador e emite token assinado.
2. Marketplace: empresa cria vaga, profissional cria serviço, cliente abre pedido.
3. Escrow: pagamento entra como `held`, admin libera (`released`).
4. Chat: mensagens vinculadas ao `order_id`.
5. Admin: métricas e moderação (ban/approve).

## Escalabilidade
A persistência em ficheiro é apenas para MVP local. Evolução natural:
- substituir `JsonStore` por repositórios MySQL,
- separar API/Auth/Payments em serviços independentes,
- adicionar fila para notificações.
