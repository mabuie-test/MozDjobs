# API Specification
Base URL: `/api`

## Auth
- `POST /auth/register` `{name,email,password,role}`
- `POST /auth/login` `{email,password}`

## Profiles
- `GET /profiles` (auth)
- `POST /profiles` (auth)

## Jobs & Candidaturas
- `GET /jobs?q=&approved=`
- `POST /jobs` (auth role: company/admin)
- `POST /jobs/apply` (auth role: professional/admin)

## Services
- `GET /services?q=`
- `POST /services` (auth role: professional/admin)

## Orders
- `GET /orders` (auth)
- `POST /orders` (auth)
- `POST /orders/status` (auth)

## Payments (Escrow)
- `GET /payments` (auth role: admin)
- `POST /payments/escrow` (auth) `{order_id,provider,amount}`
- `POST /payments/release` (auth role: admin) `{payment_id}`

## Reviews
- `GET /reviews`
- `POST /reviews` (auth)

## Chat
- `GET /chat?order_id={id}` (auth)
- `POST /chat` (auth)

## Admin Panel API
- `GET /admin/metrics` (auth role: admin)
- `POST /admin/users/ban` (auth role: admin)
- `POST /admin/jobs/approve` (auth role: admin)
- `POST /admin/services/approve` (auth role: admin)

## Segurança
- Bearer Token em `Authorization`.
- Limite por IP em janela de 1 minuto.
- Controle de permissões por papel.
