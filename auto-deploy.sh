#!/bin/bash
set -e

# ============================================
# Auto Deploy Script (runs via cron) — Novi Agro
# Checks GitHub every 2 minutes, deploys only on new commits.
# ============================================
BRANCH="main"
PHP_BIN="/usr/bin/php8.4"
COMPOSER_BIN="/usr/local/bin/composer"

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_DIR="${LOG_DIR:-$PROJECT_DIR/storage/logs}"

mkdir -p "$LOG_DIR"
LOG_FILE="$LOG_DIR/auto-deploy.log"

cd "$PROJECT_DIR"

git fetch origin "$BRANCH" --quiet

LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse "origin/$BRANCH")

if [ "$LOCAL" = "$REMOTE" ]; then
    exit 0
fi

echo "" >> "$LOG_FILE"
echo "=== Auto-deploy started at $(date '+%Y-%m-%d %H:%M:%S') ===" >> "$LOG_FILE"
echo "Local:  $LOCAL" >> "$LOG_FILE"
echo "Remote: $REMOTE" >> "$LOG_FILE"

exec >> "$LOG_FILE" 2>&1

git stash 2>/dev/null || true
git pull origin "$BRANCH"

if git diff HEAD@{1} --name-only 2>/dev/null | grep -q "composer.lock"; then
    "$COMPOSER_BIN" install --no-dev --no-interaction --optimize-autoloader
fi

# Apply any new database migrations shipped in this push (forward-only).
"$PHP_BIN" artisan migrate --force --no-interaction
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan optimize
"$PHP_BIN" artisan filament:optimize
"$PHP_BIN" artisan icons:cache
"$PHP_BIN" artisan queue:restart

echo "=== Auto-deploy completed at $(date '+%Y-%m-%d %H:%M:%S') ===" >> "$LOG_FILE"
