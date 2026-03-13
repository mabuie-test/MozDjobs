#!/usr/bin/env bash
set -euo pipefail
docker exec $(docker ps -qf name=mysql) sh -c 'exec mysqldump -uroot -proot mozjobs' > backup.sql
