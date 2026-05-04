#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PHP_BIN="${PHARMA_PHP_BIN:-$(command -v php8.3 || command -v php)}"

# Prepare MySQL container and schema.
"$ROOT_DIR/scripts/setup-mysql-test.sh"

# Export defaults expected by config/db.php.
export PHARMA_DB_HOST="${PHARMA_DB_HOST:-127.0.0.1}"
export PHARMA_DB_NAME="${PHARMA_DB_NAME:-pharmacare}"
export PHARMA_DB_USER="${PHARMA_DB_USER:-root}"
export PHARMA_DB_PASS="${PHARMA_DB_PASS:-}"

APP_PORT="${PHARMA_APP_PORT:-8080}"

echo "Starting PharmaCare on 0.0.0.0:${APP_PORT}"
echo "Press Ctrl+C to stop."

cd "$ROOT_DIR"
exec "$PHP_BIN" -S "0.0.0.0:${APP_PORT}"
