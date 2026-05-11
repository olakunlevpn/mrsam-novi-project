#!/usr/bin/env bash
#
# auto-deploy.sh - Cron-friendly wrapper around deploy-pull.sh
#
# Polls the tracked remote branch for new commits. If new commits exist,
# invokes deploy-pull.sh. Otherwise exits silently. A file lock prevents
# overlapping cron runs from colliding.
#
# Required environment variables (operator-supplied):
#   DEPLOY_PATH   - Absolute path to the Laravel project root on the server.
#                   Defaults to the parent directory of this script.
#   DEPLOY_BRANCH - Git branch to track. Defaults to "main".
#   PHP_BINARY    - Path to the PHP binary. Defaults to "/usr/bin/php".
#
# Example cron line:
#   */5 * * * * /var/www/novi-agro/scripts/auto-deploy.sh
#

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DEPLOY_PATH="${DEPLOY_PATH:-$(cd "$SCRIPT_DIR/.." && pwd)}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
export DEPLOY_PATH DEPLOY_BRANCH
export PHP_BINARY="${PHP_BINARY:-/usr/bin/php}"

cd "$DEPLOY_PATH"

LOG_DIR="$DEPLOY_PATH/storage/logs"
mkdir -p "$LOG_DIR"
LOG_FILE="$LOG_DIR/auto-deploy.log"
LOCK_FILE="$LOG_DIR/auto-deploy.lock"

log() {
    printf '[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$1" >> "$LOG_FILE"
}

# Acquire an exclusive non-blocking lock on FD 9. If another run holds it,
# exit silently so cron doesn't pile up jobs.
exec 9>"$LOCK_FILE"
if ! flock -n 9; then
    exit 0
fi

# Fetch quietly and compare local vs remote HEAD on the tracked branch.
git fetch origin "$DEPLOY_BRANCH" --quiet

LOCAL_REV="$(git rev-parse HEAD)"
REMOTE_REV="$(git rev-parse "origin/$DEPLOY_BRANCH")"

if [ "$LOCAL_REV" = "$REMOTE_REV" ]; then
    # No new commits - exit silently. Cron stays quiet.
    exit 0
fi

log "New commits detected on $DEPLOY_BRANCH"
log "Local:  $LOCAL_REV"
log "Remote: $REMOTE_REV"
log "Invoking deploy-pull.sh..."

if bash "$SCRIPT_DIR/deploy-pull.sh" >> "$LOG_FILE" 2>&1; then
    log "Auto-deploy completed successfully"
else
    EXIT_CODE=$?
    log "Auto-deploy FAILED with exit code $EXIT_CODE"
    exit "$EXIT_CODE"
fi
