# SUPER PROMPT PARA CODEX — MOZJOBS

## Como usar
Copie e cole **todo o prompt abaixo** no Codex para gerar um MVP funcional completo da plataforma MozJobs.

---

Você é um engenheiro full-stack sênior. Sua tarefa é criar a plataforma **MozJobs** completa, funcional, modular e pronta para deploy com Docker.

## Contexto do produto
MozJobs é uma plataforma de empregos, freelancers e serviços locais para Moçambique e África. O modelo de negócio combina:
- comissão por serviços e contratos,
- publicação de vagas,
- planos premium,
- anúncios,
- destaque de perfis.

Meta de negócio: capacidade arquitetural para suportar crescimento que permita atingir **3.000.000 MZN/mês**.

## Requisitos funcionais
1. Registro, autenticação e gestão de usuários (profissional, cliente, empresa, admin).
2. Perfis profissionais com foto, skills, localização, portfólio, reputação e histórico.
3. Publicação de vagas e candidaturas.
4. Criação de serviços freelance e contratação.
5. Chat interno (cliente ↔ profissional).
6. Pagamentos com M-Pesa, e-Mola e mKesh, com lógica de escrow.
7. Avaliações e reputação.
8. Painel admin com:
   - gestão de usuários,
   - aprovação de serviços e vagas,
   - gestão de pagamentos,
   - relatórios e métricas,
   - disputas e banimentos.
9. Web app (PHP + HTML/CSS/JS), backend PHP MVC + MySQL e app Flutter.
10. Logs, validações, segurança, rate limit, scripts de deploy, backup/restore e testes básicos.

## Stack obrigatória
- Backend: PHP 8+ (arquitetura MVC ou Laravel-style sem framework obrigatório).
- Banco: MySQL 8.
- Frontend Web: PHP + HTML + CSS + JS modular.
- Mobile: Flutter (Dart).
- Infra local: Docker + docker-compose.

## Estrutura de diretórios obrigatória
Crie exatamente a estrutura abaixo e **não omita arquivos**:

```txt
mozjobs/
├── docs/
│   ├── architecture.md
│   ├── api-specification.md
│   ├── database-schema.md
│   ├── product-roadmap.md
│   └── deployment-guide.md
├── infrastructure/
│   ├── docker/
│   │   ├── Dockerfile.php
│   │   ├── Dockerfile.web
│   │   └── docker-compose.yml
│   ├── nginx/nginx.conf
│   ├── terraform/
│   │   ├── aws-network.tf
│   │   ├── aws-ecs.tf
│   │   ├── aws-rds-mysql.tf
│   │   └── variables.tf
│   └── scripts/
│       ├── deploy.sh
│       ├── backup-db.sh
│       └── restore-db.sh
├── backend/
│   ├── app/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── UserController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── JobController.php
│   │   │   ├── ServiceController.php
│   │   │   ├── OrderController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── ReviewController.php
│   │   │   └── ChatController.php
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Profile.php
│   │   │   ├── Job.php
│   │   │   ├── Service.php
│   │   │   ├── Order.php
│   │   │   ├── Payment.php
│   │   │   └── Review.php
│   │   ├── Services/
│   │   │   ├── AuthService.php
│   │   │   ├── MpesaService.php
│   │   │   ├── EmolaService.php
│   │   │   ├── NotificationService.php
│   │   │   └── EmailService.php
│   │   ├── Middleware/
│   │   │   ├── AuthMiddleware.php
│   │   │   ├── RateLimitMiddleware.php
│   │   │   └── ErrorHandler.php
│   │   └── Helpers/
│   │       ├── Validator.php
│   │       └── Logger.php
│   ├── config/
│   │   ├── database.php
│   │   ├── auth.php
│   │   └── app.php
│   ├── routes/
│   │   ├── api.php
│   │   └── web.php
│   ├── storage/
│   │   ├── logs/
│   │   └── uploads/
│   ├── public/index.php
│   ├── tests/
│   │   ├── auth.test.php
│   │   ├── jobs.test.php
│   │   └── users.test.php
│   └── composer.json
├── web/
│   ├── public/
│   │   ├── images/
│   │   └── icons/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Navbar/
│   │   │   ├── Footer/
│   │   │   ├── JobCard/
│   │   │   ├── ServiceCard/
│   │   │   └── ChatBox/
│   │   ├── pages/
│   │   │   ├── index.php
│   │   │   ├── login.php
│   │   │   ├── register.php
│   │   │   ├── dashboard.php
│   │   │   ├── jobs/index.php
│   │   │   ├── jobs/job-detail.php
│   │   │   ├── services/index.php
│   │   │   ├── services/service-detail.php
│   │   │   └── profile/profile.php
│   │   ├── services/
│   │   │   ├── api.js
│   │   │   └── auth.service.js
│   │   └── styles/globals.css
│   └── package.json
├── mobile/
│   ├── lib/
│   │   ├── main.dart
│   │   ├── screens/login_screen.dart
│   │   ├── screens/register_screen.dart
│   │   ├── screens/dashboard_screen.dart
│   │   ├── screens/job_list_screen.dart
│   │   ├── screens/service_list_screen.dart
│   │   └── screens/chat_screen.dart
│   │   ├── widgets/job_card.dart
│   │   ├── widgets/service_card.dart
│   │   ├── services/api_service.dart
│   │   ├── services/auth_service.dart
│   │   └── models/user.dart
│   │       models/job.dart
│   │       models/service.dart
│   └── pubspec.yaml
├── database/
│   ├── migrations/
│   │   ├── create_users.sql
│   │   ├── create_profiles.sql
│   │   ├── create_jobs.sql
│   │   ├── create_services.sql
│   │   ├── create_orders.sql
│   │   └── create_reviews.sql
│   └── seeds/sample-data.sql
├── analytics/
│   ├── dashboards/growth-metrics.json
│   └── tracking/events.js
├── scripts/start-dev.sh
├── scripts/lint.sh
├── scripts/test.sh
├── .env.example
├── README.md
└── composer.json
```

## Regras de implementação
- Criar migrations SQL completas para: `users`, `profiles`, `jobs`, `services`, `orders`, `payments`, `reviews`, `chats`.
- Implementar autenticação segura (JWT ou sessão) com hash de senha e controle de permissões por papel.
- Implementar validação de entrada, tratamento centralizado de erros e rate limiting.
- Implementar endpoints REST e documentar todos no `docs/api-specification.md`.
- Incluir seed de dados de exemplo funcional.
- Criar scripts de execução local, testes, deploy, backup e restore.
- Garantir que `docker-compose up --build` suba backend, web e mysql.
- Estruturar o painel admin em rotas, controllers e páginas.
- Integrar provedores de pagamento com camada de serviço e modo sandbox configurável.

## Entregáveis obrigatórios
1. Código de todos os arquivos da árvore.
2. Documentação técnica completa em `docs/`.
3. README com instruções claras de setup local e produção.
4. Scripts executáveis (`chmod +x`) para deploy/backup/restore/start/lint/test.
5. Testes básicos funcionando no backend.
6. Explicação objetiva do modelo de monetização para atingir 3.000.000 MZN/mês no `docs/product-roadmap.md`.

## Critérios de qualidade
- Código limpo, modular e comentado apenas quando necessário.
- Separação de responsabilidades (Controller/Service/Model).
- Segurança mínima aplicada (auth, validação, sanitização, rate limit).
- Preparado para escalar (config por ambiente, logs, estrutura de serviços).

## Formato da resposta do Codex
1. Mostrar árvore final criada.
2. Mostrar conteúdo de cada arquivo criado.
3. Mostrar comandos para subir o ambiente e rodar testes.
4. Incluir checklist final de conformidade com cada requisito.

