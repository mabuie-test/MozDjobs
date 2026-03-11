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
5. Disputas: cliente/profissional abre disputa e admin resolve.
6. Admin: métricas, moderação (ban/approve) e governança de disputas.

## Interface intuitiva
- Navegação direta por tarefa (Vagas, Serviços, Chat, Admin, Perfil).
- Formulários curtos por ação e feedback imediato de sucesso/erro.
- Fluxo principal guiado: descobrir → contratar → pagar em escrow → comunicar → resolver disputa se necessário.

## Escalabilidade
A persistência em ficheiro é apenas para MVP local. Evolução natural:
- substituir `JsonStore` por repositórios MySQL,
- separar API/Auth/Payments em serviços independentes,
- adicionar fila para notificações.


## Recursos adicionados
- Favoritos para vagas/serviços.
- Centro de notificações in-app para eventos do utilizador.
