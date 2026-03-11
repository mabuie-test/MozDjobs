#!/usr/bin/env bash
set -euo pipefail
php backend/tests/auth.test.php
php backend/tests/jobs.test.php
php backend/tests/users.test.php
php backend/tests/auth_flow.test.php
php backend/tests/payment.test.php
php backend/tests/job_apply.test.php
php backend/tests/order_status.test.php
php backend/tests/dispute_flow.test.php
php backend/tests/admin_metrics.test.php
php backend/tests/migrations_presence.test.php
php backend/tests/review_summary.test.php
php backend/tests/favorites_notifications.test.php
