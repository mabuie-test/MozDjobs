#!/usr/bin/env bash
set -euo pipefail
cat backup.sql | docker exec -i $(docker ps -qf name=mysql) sh -c 'exec mysql -uroot -proot mozjobs'
