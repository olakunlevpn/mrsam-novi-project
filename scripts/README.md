# Deployment Scripts

Production deploy scripts for the Novi Agro CMS (Laravel 13 + Filament 5 + MySQL).

These scripts live in the repo so any operator can clone, configure, and ship.
They contain no hardcoded paths, hosts, or secrets — every server-specific
value is supplied via environment variables.

## Files

| Script | Purpose |
| --- | --- |
| `deploy-pull.sh` | One-shot manual deploy. Operator runs this on the VPS when shipping a release by hand. |
| `auto-deploy.sh` | Cron wrapper. Polls the tracked branch; calls `deploy-pull.sh` only when new commits exist. |

## Setup

1. Clone the repo onto the server, copy `.env`, and run an initial
   `composer install` + `php artisan migrate` + `php artisan key:generate`
   the manual way. The scripts assume the project is already bootstrapped.

2. Make the scripts executable:

   ```sh
   chmod +x scripts/*.sh
   ```

3. Decide where the project lives and which branch is production, then export
   the env vars for the operator's shell (or set them in the cron line):

   ```sh
   export DEPLOY_PATH=/var/www/novi-agro
   export DEPLOY_BRANCH=main
   export PHP_BINARY=/usr/bin/php8.4
   ```

## Environment Variables

| Variable | Required | Default | Notes |
| --- | --- | --- | --- |
| `DEPLOY_PATH` | recommended | `$(dirname script)/..` | Absolute path to the Laravel project root. The default resolves to the parent of `scripts/`, which works if the repo is checked out where you intend to deploy from. Set explicitly in cron. |
| `DEPLOY_BRANCH` | no | `main` | Git branch to deploy. |
| `PHP_BINARY` | no | `/usr/bin/php` | Path to the PHP CLI binary. Set to e.g. `/usr/bin/php8.4` if the system has multiple PHP versions. |

## Manual Deploy

Run on the VPS after SSHing in:

```sh
DEPLOY_PATH=/var/www/novi-agro \
DEPLOY_BRANCH=main \
PHP_BINARY=/usr/bin/php8.4 \
  bash /var/www/novi-agro/scripts/deploy-pull.sh
```

Steps executed (in order):

1. `git fetch origin`
2. `git reset --hard origin/$DEPLOY_BRANCH` (hard reset — any local edits on the server are discarded)
3. `composer install --no-dev --optimize-autoloader --no-interaction`
4. `php artisan migrate --force`
5. `php artisan storage:link` (idempotent; tolerated if symlink exists)
6. `php artisan config:cache`, `route:cache`, `view:cache`, `event:cache`
7. `php artisan queue:restart` (signals running workers to gracefully exit)
8. `php artisan optimize`

All output is appended to `storage/logs/deploy.log` with timestamps. The
script exits non-zero on the first failing command (`set -euo pipefail`).

## Auto Deploy (cron)

Add a cron entry to poll for new commits every five minutes:

```cron
*/5 * * * * /var/www/novi-agro/scripts/auto-deploy.sh
```

If env vars need to differ from the defaults, set them inline:

```cron
*/5 * * * * DEPLOY_PATH=/var/www/novi-agro DEPLOY_BRANCH=main PHP_BINARY=/usr/bin/php8.4 /var/www/novi-agro/scripts/auto-deploy.sh
```

Behavior:

- Acquires an exclusive `flock` on `storage/logs/auto-deploy.lock`. Overlapping
  cron runs exit immediately so two deploys can never collide.
- `git fetch` then compares local `HEAD` to `origin/$DEPLOY_BRANCH`. If they
  match, exits silently (cron stays quiet).
- If the remote moved, logs the SHA delta and invokes `deploy-pull.sh`.
- All activity logged to `storage/logs/auto-deploy.log`.

## Required Server Configuration

- **PHP 8.4+** with extensions Laravel 13 needs (`mbstring`, `xml`, `bcmath`,
  `pdo_mysql`, `curl`, `intl`, `zip`, `gd` or `imagick`).
- **MySQL 8+** (or MariaDB 10.11+).
- **Composer 2.x** on `$PATH`.
- **Git** with read access to the repo (SSH deploy key recommended).
- **Queue worker** running as a long-lived process under **systemd** or
  **supervisord**. The deploy script calls `queue:restart`, which only signals
  workers — it does not start them. A typical `supervisord` block:

  ```ini
  [program:novi-agro-worker]
  process_name=%(program_name)s_%(process_num)02d
  command=/usr/bin/php8.4 /var/www/novi-agro/artisan queue:work --sleep=3 --tries=3 --max-time=3600
  autostart=true
  autorestart=true
  user=www-data
  numprocs=2
  redirect_stderr=true
  stdout_logfile=/var/www/novi-agro/storage/logs/worker.log
  stopwaitsecs=3600
  ```

- **Redis** (optional, recommended for cache and queue connection at scale).
- **Web server** (nginx + php-fpm typical) with document root pointing at
  `$DEPLOY_PATH/public`.

## `.env` Reminders

Before the first deploy, confirm the production `.env` has:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://novi-agro.com

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
MAIL_FROM_NAME="Novi Agro"

QUEUE_CONNECTION=database
# or:
# QUEUE_CONNECTION=redis
```

`config:cache` is run by the deploy script, so any `.env` change requires a
fresh deploy (or a manual `php artisan config:cache`) to take effect.

## Logs

| File | Contents |
| --- | --- |
| `storage/logs/deploy.log` | Manual deploy output, timestamped per line. |
| `storage/logs/auto-deploy.log` | Cron-driven auto-deploy activity. Silent when no commits to ship. |
| `storage/logs/auto-deploy.lock` | flock file. Safe to leave on disk; the kernel manages the lock. |

Tail to watch a deploy in real time:

```sh
tail -f storage/logs/deploy.log
```

## Troubleshooting

- **`composer: command not found` in cron.** Cron has a minimal `PATH`. Add
  `PATH=/usr/local/bin:/usr/bin:/bin` at the top of the crontab or call the
  scripts via a shell that loads the operator's profile.
- **Migrations fail mid-deploy.** The script exits non-zero and stops before
  caches are rebuilt — fix the migration, then re-run `deploy-pull.sh`.
- **Auto-deploy never fires.** Check `storage/logs/auto-deploy.log`. If the
  file is empty, the cron job isn't firing — verify `crontab -l` and the user
  it runs under. If the file has entries but no deploys, the local HEAD is
  already at the remote (nothing to ship).
