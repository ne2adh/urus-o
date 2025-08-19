# syntax=docker/dockerfile:1

############################
# Etapa deps: extensiones + vendor sin scripts
############################
FROM php:8.2-fpm-alpine AS deps

RUN apk add --no-cache git unzip libzip-dev oniguruma-dev icu-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev libxml2-dev $PHPIZE_DEPS \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo_mysql zip intl gd opcache

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

# instala dependencias sin scripts (evita @php artisan ... en build)
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --no-scripts \
 && composer dump-autoload -o --no-dev --no-interaction --no-scripts

# ahora copia el resto del código (ya existe artisan)
COPY . .

############################
# Etapa runtime: php-fpm + nginx
############################
FROM php:8.2-fpm-alpine AS runtime

RUN apk add --no-cache nginx tzdata icu libzip libpng libjpeg-turbo libwebp libxml2 bash curl

# copiar extensiones
COPY --from=deps /usr/local/etc/php/conf.d/docker-php-ext-* /usr/local/etc/php/conf.d/
COPY --from=deps /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/

WORKDIR /var/www/html
COPY --from=deps /var/www/html /var/www/html

# configs
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/php.ini /usr/local/etc/php/php.ini
COPY .docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY .docker/start.sh /start.sh
RUN chmod +x /start.sh \
 && mkdir -p /var/tmp/nginx /var/log/nginx \
 && chown -R www-data:www-data /var/www/html /var/tmp/nginx /var/log/nginx

HEALTHCHECK --interval=30s --timeout=5s --retries=5 CMD curl -fsS http://127.0.0.1/ || exit 1

EXPOSE 80
CMD ["/start.sh"]
