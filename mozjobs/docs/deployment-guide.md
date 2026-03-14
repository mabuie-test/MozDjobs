# Deployment Guide

## Local (Docker)
```bash
cd infrastructure/docker
docker compose up --build
```

Serviços:
- Web: `http://localhost:8081`
- API: `http://localhost:8080/api`
- MySQL: `localhost:3306`

## Rotina de operação
```bash
# build + start stack
./infrastructure/scripts/deploy.sh

# backup database
./infrastructure/scripts/backup-db.sh

# restore database
./infrastructure/scripts/restore-db.sh backup_YYYYMMDD_HHMMSS.sql
```

## Produção (AWS referência)
1. Configurar variáveis em `infrastructure/terraform/variables.tf`.
2. Provisionar rede/cluster/rds com Terraform.
3. Publicar imagens em registry privado.
4. Atualizar task definitions ECS.
5. Definir secrets (DB/JWT/provedores pagamento) no ambiente.
6. Executar migrations no MySQL gerenciado.
7. Monitorar logs e métricas de erro/latência.

## Checklist de release
- [ ] `./scripts/lint.sh`
- [ ] `./scripts/test.sh`
- [ ] Backup válido gerado
- [ ] Healthcheck `/api/health` OK
- [ ] Readiness `/api/ready` OK (sem `fail`)
- [ ] Segredos fortes definidos (`JWT_SECRET`, `APP_ENCRYPTION_KEY`)
- [ ] `APP_ALLOWED_ORIGIN` configurado para o domínio de produção
