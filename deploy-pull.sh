#!/bin/bash
set -e

# ============================================
# Manual Deploy Script — Novi Agro
# Usage: bash deploy-pull.sh
# ============================================
BRANCH="main"
PHP_BIN="/usr/bin/php8.4"
COMPOSER_BIN="/usr/local/bin/composer"

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_DIR="${LOG_DIR:-$PROJECT_DIR/storage/logs}"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

mkdir -p "$LOG_DIR"
LOG_FILE="$LOG_DIR/deploy.log"
exec > >(tee -a "$LOG_FILE") 2>&1

echo ""
echo "=== Deploy started at $TIMESTAMP ==="
cd "$PROJECT_DIR"

echo "[1/7] Stashing local changes..."
git stash 2>/dev/null || true

echo "[2/7] Pulling from $BRANCH..."
git pull origin "$BRANCH"

echo "[3/7] Composer (only when composer.lock changed)..."
if git diff HEAD@{1} --name-only 2>/dev/null | grep -q "composer.lock"; then
    "$COMPOSER_BIN" install --no-dev --no-interaction --optimize-autoloader
else
    echo "      No composer.lock change, skipping."
fi

echo "[4/7] Running migrations..."
"$PHP_BIN" artisan migrate --force --no-interaction

echo "[5/7] Rebuilding caches..."
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan optimize

echo "[6/7] Optimizing Filament..."
"$PHP_BIN" artisan filament:optimize
"$PHP_BIN" artisan icons:cache

echo "[7/7] Restarting queue workers..."
"$PHP_BIN" artisan queue:restart

echo "=== Deploy completed at $(date '+%Y-%m-%d %H:%M:%S') ==="
echo ""
