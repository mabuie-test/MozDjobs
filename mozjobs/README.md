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

## Scripts
```bash
./scripts/start-dev.sh
./scripts/lint.sh
./scripts/test.sh
```

## Migrations
Arquivos SQL em `database/migrations`.

## Segurança incluída
- Hash de senha (`password_hash`)
- Token JWT simplificado em `AuthService`
- Rate limit por IP em middleware
- Validação de payload

## Monetização
Ver `docs/product-roadmap.md` para projeção de 3.000.000 MZN/mês.
