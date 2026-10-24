#!/bin/sh
set -eu
cd /var/www/html
export PORT="${PORT:-10000}"
export APP_URL="${RENDER_EXTERNAL_URL:-${APP_URL:-http://localhost}}"
if [ "${RENDER:-false}" = true ]; then
    export SESSION_SECURE_COOKIE=true
fi
case "$PORT" in ''|*[!0-9]*) echo 'PORT must be numeric' >&2; exit 1;; esac
printf 'Listen %s\n' "$PORT" > /etc/apache2/ports.conf

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/app/public/tmp
php artisan config:clear

if [ "${DEMO_MODE:-false}" = true ]; then
    # Never connect to a previously configured production database in demo mode.
    unset DATABASE_URL
    export DB_CONNECTION=sqlite DB_DATABASE=/var/www/html/storage/demo.sqlite
    export CACHE_DRIVER=file SESSION_DRIVER=file QUEUE_CONNECTION=sync MAIL_MAILER=array
    export IMAGE_SAVE_FOLDER=public/ IMAGE_TMP_SAVE_FOLDER=public/tmp/
    if [ -z "${APP_KEY:-}" ]; then
        APP_KEY="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"
        export APP_KEY
    fi
    touch "$DB_DATABASE"
    php artisan migrate --force
    php artisan db:seed --class=RenderDemoSeeder --force
fi

php artisan storage:link
php artisan config:cache
# Existing routes contain duplicate names, so route:cache is intentionally omitted.
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache
exec apache2-foreground
