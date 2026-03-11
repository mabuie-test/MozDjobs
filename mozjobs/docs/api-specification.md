# API Specification
Base URL: `/api`

## Auth
- `POST /auth/register` {name,email,password,role}
- `POST /auth/login` {email,password}

## Users
- `GET /users`
- `GET /users/{id}`

## Profiles
- `GET /profiles/{userId}`
- `POST /profiles`

## Jobs
- `GET /jobs`
- `POST /jobs`

## Services
- `GET /services`
- `POST /services`

## Orders
- `POST /orders`
- `GET /orders/{id}`

## Payments
- `POST /payments/escrow`
- `POST /payments/release`

## Reviews
- `POST /reviews`
- `GET /reviews/{professionalId}`

## Chat
- `GET /chat/{orderId}`
- `POST /chat/{orderId}`

## Admin
- `GET /admin/metrics`
- `POST /admin/users/{id}/ban`
- `POST /admin/jobs/{id}/approve`
- `POST /admin/services/{id}/approve`
