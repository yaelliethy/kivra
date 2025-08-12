# syntax=docker/dockerfile:1
FROM composer:2 AS composer_base
WORKDIR /app

COPY composer.* ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build

FROM php:8.2-cli-bookworm AS app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    zlib1g-dev \
    libicu-dev \
    libsqlite3-dev \
    g++ \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j"$(nproc)" \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    zip \
    gd \
    intl \
    bcmath \
    opcache

RUN pecl install redis \
  && docker-php-ext-enable redis

## Apache is not used; we will run via `php artisan serve`

WORKDIR /var/www/html

COPY . .

COPY --from=composer_base /usr/bin/composer /usr/bin/composer
COPY --from=composer_base /app/vendor /var/www/html/vendor

COPY --from=frontend /app/public/build /var/www/html/public/build

RUN { \
  echo "opcache.enable=1"; \
  echo "opcache.enable_cli=0"; \
  echo "opcache.validate_timestamps=0"; \
  echo "opcache.max_accelerated_files=20000"; \
  echo "opcache.memory_consumption=256"; \
  echo "opcache.interned_strings_buffer=16"; \
} > /usr/local/etc/php/conf.d/opcache.ini

RUN chown -R www-data:www-data storage bootstrap/cache \
  && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000

# Healthcheck against built-in server
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD curl -fsS http://localhost:8000/robots.txt || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
