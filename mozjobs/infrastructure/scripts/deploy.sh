#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DOCKER_COMPOSE_FILE="${SCRIPT_DIR}/../docker/docker-compose.yml"

echo "[deploy] Building and starting MozJobs stack..."
docker compose -f "$DOCKER_COMPOSE_FILE" up -d --build

echo "[deploy] Services status:"
docker compose -f "$DOCKER_COMPOSE_FILE" ps
