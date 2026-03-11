#!/usr/bin/env bash
set -euo pipefail
cd infrastructure/docker
docker compose up --build
