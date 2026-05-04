#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SQL_FILE="$ROOT_DIR/database/pharma.sql"

if [[ ! -f "$SQL_FILE" ]]; then
  echo "Schema file not found: $SQL_FILE" >&2
  exit 1
fi

CONTAINER_NAME="${PHARMA_DB_CONTAINER:-pharmacare-mysql}"
MYSQL_IMAGE="${PHARMA_MYSQL_IMAGE:-mysql:8.4}"
LOCAL_PORT="${PHARMA_DB_PORT:-3306}"
DB_NAME="${PHARMA_DB_NAME:-pharmacare}"
DB_USER="${PHARMA_DB_USER:-root}"
DB_PASS="${PHARMA_DB_PASS:-}"

if ! command -v docker >/dev/null 2>&1; then
  echo "Docker is required but not installed." >&2
  exit 1
fi

container_exists() {
  docker ps -a --format '{{.Names}}' | grep -Fxq "$CONTAINER_NAME"
}

container_running() {
  docker ps --format '{{.Names}}' | grep -Fxq "$CONTAINER_NAME"
}

if container_exists; then
  if ! container_running; then
    echo "Starting existing MySQL container: $CONTAINER_NAME"
    docker start "$CONTAINER_NAME" >/dev/null
  else
    echo "MySQL container already running: $CONTAINER_NAME"
  fi
else
  echo "Creating MySQL container: $CONTAINER_NAME"
  docker run -d \
    --name "$CONTAINER_NAME" \
    -e MYSQL_ALLOW_EMPTY_PASSWORD=yes \
    -e MYSQL_DATABASE="$DB_NAME" \
    -p "$LOCAL_PORT:3306" \
    "$MYSQL_IMAGE" >/dev/null
fi

echo "Waiting for MySQL to become ready..."
for i in {1..60}; do
  if docker exec "$CONTAINER_NAME" mysqladmin ping -uroot --silent >/dev/null 2>&1; then
    break
  fi
  sleep 2
  if [[ "$i" -eq 60 ]]; then
    echo "MySQL did not become ready in time." >&2
    echo "Check container logs with: docker logs $CONTAINER_NAME" >&2
    exit 1
  fi
done

echo "Importing schema from database/pharma.sql"
docker exec -i "$CONTAINER_NAME" mysql -uroot "$DB_NAME" < "$SQL_FILE"

echo
echo "MySQL is ready for PharmaCare."
echo "Use these environment variables when running PHP:"
echo "  export PHARMA_DB_HOST=127.0.0.1"
echo "  export PHARMA_DB_NAME=$DB_NAME"
echo "  export PHARMA_DB_USER=$DB_USER"
echo "  export PHARMA_DB_PASS=$DB_PASS"
echo
echo "Then start your app (example):"
echo "  php -S 0.0.0.0:8080"
