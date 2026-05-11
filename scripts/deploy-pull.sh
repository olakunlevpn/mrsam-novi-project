#!/usr/bin/env bash
#
# deploy-pull.sh - One-shot production deploy for Novi Agro CMS
#
# Operator runs this on the production VPS to pull the latest code,
# install dependencies, run migrations, and rebuild caches.
#
# Required environment variables (operator-supplied):
#   DEPLOY_PATH   - Absolute path to the Laravel project root on the server.
#                   Defaults to the parent directory of this script.
#   DEPLOY_BRANCH - Git branch to deploy from. Defaults to "main".
#   PHP_BINARY    - Path to the PHP binary. Defaults to "/usr/bin/php".
#
# Example:
#   DEPLOY_PATH=/var/www/novi-agro DEPLOY_BRANCH=main PHP_BINARY=/usr/bin/php8.4 \
#     bash scripts/deploy-pull.sh
#

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DEPLOY_PATH="${DEPLOY_PATH:-$(cd "$SCRIPT_DIR/.." && pwd)}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
PHP_BINARY="${PHP_BINARY:-/usr/bin/php}"

cd "$DEPLOY_PATH"

LOG_DIR="$DEPLOY_PATH/storage/logs"
mkdir -p "$LOG_DIR"
LOG_FILE="$LOG_DIR/deploy.log"

# Mirror stdout/stderr into the log file with timestamps.
exec > >(while IFS= read -r line; do printf '[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$line"; done | tee -a "$LOG_FILE") 2>&1

echo ""
echo "=== Deploy started ==="
echo "Path:   $DEPLOY_PATH"
echo "Branch: $DEPLOY_BRANCH"
echo "PHP:    $PHP_BINARY"

echo "[1/9] Fetching from origin..."
git fetch origin

echo "[2/9] Resetting working tree to origin/$DEPLOY_BRANCH..."
git reset --hard "origin/$DEPLOY_BRANCH"

echo "[3/9] Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "[4/9] Running database migrations..."
"$PHP_BINARY" artisan migrate --force

echo "[5/9] Linking storage..."
"$PHP_BINARY" artisan storage:link || true

echo "[6/9] Caching config, routes, views, events..."
"$PHP_BINARY" artisan config:cache
"$PHP_BINARY" artisan route:cache
"$PHP_BINARY" artisan view:cache
"$PHP_BINARY" artisan event:cache

echo "[7/9] Restarting queue workers..."
"$PHP_BINARY" artisan queue:restart

echo "[8/9] Running optimize..."
"$PHP_BINARY" artisan optimize

echo "[9/9] Done."
echo "=== Deploy completed ==="
echo ""
