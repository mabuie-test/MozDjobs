#!/usr/bin/env bash
set -euo pipefail

BACKUP_FILE="${1:-backup.sql}"
CONTAINER_ID="$(docker ps -qf name=mysql | head -n1)"

if [[ ! -f "$BACKUP_FILE" ]]; then
  echo "[restore] Backup file not found: $BACKUP_FILE"
  exit 1
fi

if [[ -z "$CONTAINER_ID" ]]; then
  echo "[restore] mysql container not running"
  exit 1
fi

cat "$BACKUP_FILE" | docker exec -i "$CONTAINER_ID" sh -c 'exec mysql -uroot -proot mozjobs'
echo "[restore] Restored from $BACKUP_FILE"
