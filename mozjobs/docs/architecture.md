# Architecture

## Camadas
- `Controllers`: entradas HTTP e orquestração de casos de uso.
- `Services`: regras de auth, notificações e pagamentos (M-Pesa/e-Mola/mKesh).
- `Helpers`: validação e persistência simplificada (`JsonStore`) para MVP.
- `Middleware`: autenticação, autorização e rate-limit.
- `Models`: shape de domínio para evolução para persistência relacional.

## Fluxos principais
1. Registro/Login: cria utilizador e emite token assinado.
2. Marketplace: empresa cria vaga, profissional cria serviço, cliente abre pedido.
3. Escrow: pagamento entra como `held`, admin libera (`released`).
4. Chat: mensagens vinculadas ao `order_id`.
5. Disputas: cliente/profissional abre disputa e admin resolve.
6. Favoritos e notificações: personalização e retenção de utilizador.
7. Feed social: publicações, reações e comentários em timeline.
8. Stories e social graph (seguir perfis) para retenção e descoberta.
9. Admin: métricas, moderação (ban/approve) e governança de disputas.

## Interface intuitiva
- Navegação direta por tarefa (Vagas, Serviços, Chat, Hub, Admin, Perfil).
- Formulários curtos por ação e feedback imediato de sucesso/erro.
- Fluxo principal guiado: descobrir → contratar → pagar em escrow → comunicar → avaliar.

## Escalabilidade
A persistência em ficheiro é apenas para MVP local. Evolução natural:
- substituir `JsonStore` por repositórios MySQL,
- separar API/Auth/Payments em serviços independentes,
- adicionar fila para notificações,
- publicar eventos para analytics em pipeline dedicado.


## Relatórios e BI
- Endpoint de overview para KPIs operacionais e projeção de take rate.
- Exportação CSV para integração manual com ferramentas externas.
