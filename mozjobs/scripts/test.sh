#!/usr/bin/env bash
set -euo pipefail
php backend/tests/auth.test.php
php backend/tests/jobs.test.php
php backend/tests/users.test.php
