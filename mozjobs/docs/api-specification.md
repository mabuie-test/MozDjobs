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

## Chat
- `GET /chat?order_id={id}` (auth)
- `POST /chat` (auth)

## Disputes
- `GET /disputes` (auth)
- `POST /disputes` (auth) `{order_id,opened_by,reason}`

## Reviews
- `GET /reviews`
- `GET /reviews/summary?reviewed_id={id}`
- `POST /reviews` (auth)

## Feed
- `GET /feed` (auth)
- `POST /feed/posts` (auth) `{author_id,author_name,content,media_url?}`
- `POST /feed/reactions` (auth) `{post_id,user_id,type}`
- `POST /feed/comments` (auth) `{post_id,user_id,comment}`

## Favorites
- `GET /favorites?user_id={id}` (auth)
- `POST /favorites` (auth) `{user_id,entity_type,entity_id}`

## Notifications
- `GET /notifications?user_id={id}` (auth)
- `POST /notifications` (auth) `{user_id,title,body}`
- `POST /notifications/read` (auth) `{notification_id}`

## Reports
- `GET /reports/overview` (auth role: admin)
- `GET /reports/export-csv` (auth role: admin)

## Admin Panel API
- `GET /admin/metrics` (auth role: admin)
- `POST /admin/users/ban` (auth role: admin)
- `POST /admin/jobs/approve` (auth role: admin)
- `POST /admin/services/approve` (auth role: admin)
- `POST /admin/disputes/resolve` (auth role: admin)

## Segurança
- Bearer Token em `Authorization`.
- Limite por IP em janela de 1 minuto.
- Controle de permissões por papel.
