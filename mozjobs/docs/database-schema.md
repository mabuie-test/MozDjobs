# Database Schema
Tabelas: users, profiles, jobs, services, orders, payments, reviews, chats, applications, disputes.

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
