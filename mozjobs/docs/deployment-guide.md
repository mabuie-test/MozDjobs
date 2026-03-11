# Deployment Guide
## Local
```bash
cd infrastructure/docker
docker compose up --build
```

## Produção
1. Provisionar VPC/ECS/RDS via Terraform.
2. Build/push imagens Docker.
3. Configurar variáveis seguras.
4. Rodar migrations em RDS.
5. Executar health-checks.
