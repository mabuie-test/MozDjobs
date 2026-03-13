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
- `GET /feed?user_id=&sort=recent|popular&offset=&limit=` (auth)
- `GET /feed/trending?limit=` (auth)
- `POST /feed/posts` (auth) `{author_id,author_name,content,media_url?,post_type?}`
- `POST /feed/posts/update` (auth) `{id,content,post_type?,media_url?}`
- `POST /feed/posts/delete` (auth) `{id}`
- `POST /feed/reactions` (auth) `{post_id,user_id,type}` (upsert por utilizador/post)
- `POST /feed/reactions/remove` (auth) `{post_id,user_id}`
- `POST /feed/comments` (auth) `{post_id,user_id,comment}`
- `POST /feed/comments/update` (auth) `{id,comment}`
- `POST /feed/comments/delete` (auth) `{id}`

## Stories & Social Graph
- `GET /stories` (auth)
- `POST /stories` (auth) `{user_id,user_name,text,bg?}`
- `GET /follows?follower_id={id}` (auth)
- `POST /follows` (auth) `{follower_id,followed_id}`
- `POST /follows/unfollow` (auth) `{follower_id,followed_id}`
- `GET /follows/suggestions?follower_id={id}` (auth)

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


## Ownership enforcement
- Ações de escrita social (posts, comentários, reações e follows) só podem ser executadas pelo próprio utilizador autenticado (`auth_user.id`) ou por `admin`.
- Tentativas fora destas regras retornam `forbidden`.
