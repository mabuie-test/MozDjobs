# Database Schema
Tabelas: users, profiles, jobs, services, orders, payments, reviews, chats, applications, disputes, feed_posts, feed_reactions, feed_comments, stories, follows.

Relações principais:
- profiles.user_id -> users.id
- jobs.company_id -> users.id
- services.professional_id -> users.id
- applications.job_id -> jobs.id
- applications.professional_id -> users.id
- orders.client_id -> users.id
- orders.professional_id -> users.id
- payments.order_id -> orders.id
- reviews.order_id -> orders.id
- chats.order_id -> orders.id
- disputes.order_id -> orders.id
- disputes.opened_by -> users.id


Tabelas sociais:
- feed_posts (publicações de status/vaga/serviço/update).
- feed_reactions (uma reação por par post/utilizador).
- feed_comments (comentários em posts).
- stories (stories efémeras para destaque no topo do hub).
- follows (grafo seguidor->seguido para personalização de feed).
