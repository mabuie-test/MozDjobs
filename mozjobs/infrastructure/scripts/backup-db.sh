#!/usr/bin/env bash
set -euo pipefail

BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
CONTAINER_ID="$(docker ps -qf name=mysql | head -n1)"

if [[ -z "$CONTAINER_ID" ]]; then
  echo "[backup] mysql container not running"
  exit 1
fi

docker exec "$CONTAINER_ID" sh -c 'exec mysqldump -uroot -proot mozjobs' > "$BACKUP_FILE"
echo "[backup] Created $BACKUP_FILE"
