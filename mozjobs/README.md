# MozJobs MVP

Plataforma de empregos e serviços para Moçambique com backend PHP, frontend web PHP, app Flutter e MySQL.

## Requisitos
- Docker + Docker Compose

## Executar
```bash
cd infrastructure/docker
docker compose up --build
```

- API backend: `http://localhost:8080/api`
- Web: `http://localhost:8081`
- MySQL: `localhost:3306`

## Interface Web (intuitiva)
Páginas com fluxo simples para uso imediato:
- Home: proposta de valor e navegação direta.
- Login/Registo: autenticação rápida.
- Dashboard: atalhos para vagas, serviços, feed social, chat e admin.
- Vagas: busca + candidatura.
- Serviços: busca + contratação com escrow.
- Chat: mensagens por pedido em tempo real por polling.
- Admin: métricas, aprovação, banimento e resolução de disputas.
- Perfil: edição de dados profissionais.
- Hub: feed social com layout de três colunas, stories, sugestões de perfis e composição inline de comentários.
- Feed estilo social (próximo ao Facebook): publicações, reações e comentários.
- Stories e seguir perfis (social graph) para discovery de talento, com sugestões e unfollow.
- Favoritos e notificações: novas APIs e feed pessoal no Hub.
- Reputação: resumo de avaliação média por profissional.
- Relatórios: insights e export CSV para gestão.

## Scripts
```bash
./scripts/start-dev.sh
./scripts/lint.sh
./scripts/test.sh
```

O runner executa testes de fluxos, migrations, favoritos/notificações, social graph, feed avançado (paginação/upsert de reação) e shapes de models.

## Migrations
Arquivos SQL em `database/migrations`.

## Segurança incluída
- Hash de senha (`password_hash`)
- Token JWT assinado com `iat/nbf/exp/jti` em `AuthService`
- Rate limit por IP em middleware
- Validação de payload
- Regras de autorização por role em rotas

## Monetização
Ver `docs/product-roadmap.md` para projeção de 3.000.000 MZN/mês.


## Criptografia
- Encryption at rest para coleções do `JsonStore` com AES-256-GCM.
- Segredo recomendado via `APP_ENCRYPTION_KEY` e `JWT_SECRET` fortes no `.env`.
