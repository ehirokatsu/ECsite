FROM php:8.3-apache-bookworm AS php-base
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libonig-dev libzip-dev libsqlite3-dev libpq-dev \
    && docker-php-ext-install -j2 mbstring pdo_mysql pdo_sqlite pdo_pgsql zip opcache \
    && a2enmod rewrite \
    && printf 'ServerName localhost\n' > /etc/apache2/conf-enabled/server-name.conf \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

FROM php-base AS dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction --no-progress
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts \
    && php artisan package:discover --ansi

FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --include=dev --no-audit --no-fund
COPY . .
COPY --from=dependencies /var/www/html/vendor ./vendor
RUN npm run build

FROM php-base AS production
ENV APP_ENV=production APP_DEBUG=false LOG_CHANNEL=stderr \
    DEMO_MODE=true PORT=10000
COPY --from=dependencies /var/www/html /var/www/html
COPY --from=assets /app/public/build ./public/build
COPY docker/render/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/render/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
COPY docker/render/start.sh /usr/local/bin/render-start
RUN chmod +x /usr/local/bin/render-start \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data storage bootstrap/cache
EXPOSE 10000
CMD ["render-start"]
