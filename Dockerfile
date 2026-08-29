FROM node:24-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

COPY resources ./resources
COPY postcss.config.js tailwind.config.js vite.config.mjs ./
RUN mkdir -p public && npm run build

FROM php:8.3-apache

ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /var/www/html/cms

RUN apt-get update && apt-get install -y --no-install-recommends \
        cron \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libzip-dev \
        unzip \
        zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" exif gd mbstring pcntl pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader \
    --prefer-dist

COPY . .
COPY --from=frontend /app/public/build ./public/build
COPY docker/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/local.ini /usr/local/etc/php/conf.d/99-monster-cms.ini
COPY docker/crontab /etc/cron.d/monster-cms
COPY docker/start.sh /usr/local/bin/monster-cms-start

RUN printf 'ServerName cms.local\n' > /etc/apache2/conf-available/servername.conf \
    && a2enmod rewrite \
    && a2enconf servername \
    && chmod 0644 /etc/cron.d/monster-cms \
    && chmod +x /usr/local/bin/monster-cms-start \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["monster-cms-start"]
