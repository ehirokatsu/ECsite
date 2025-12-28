# ./Dockerfile
FROM php:8.2-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html