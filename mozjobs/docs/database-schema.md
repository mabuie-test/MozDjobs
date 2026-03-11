# Database Schema
Tabelas: users, profiles, jobs, services, orders, payments, reviews, chats.
Relações principais:
- profiles.user_id -> users.id
- jobs.company_id -> users.id
- services.professional_id -> users.id
- orders.client_id -> users.id
- orders.professional_id -> users.id
- payments.order_id -> orders.id
- reviews.order_id -> orders.id
- chats.order_id -> orders.id
