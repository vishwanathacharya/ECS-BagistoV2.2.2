FROM php:8.3-fpm-alpine AS builder

WORKDIR /var/www/html

# Install build dependencies
RUN apk add --no-cache --virtual .build-deps \
    autoconf g++ make pkgconf re2c \
    zlib-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    icu-dev oniguruma-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd intl mbstring xml soap bcmath opcache calendar \
    && apk del .build-deps

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js for asset compilation
RUN apk add --no-cache nodejs npm

# Copy application files
COPY . .

# Install dependencies and build assets
RUN composer install --no-dev --optimize-autoloader \
    && npm install && npm run build && npm cache clean --force \
    && composer clear-cache

# Download Adminer
RUN curl -L https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php -o public/adminer.php

FROM php:8.3-fpm-alpine AS production

WORKDIR /var/www/html

# Install runtime dependencies
RUN apk add --no-cache \
    nginx supervisor \
    libzip libpng libjpeg-turbo freetype \
    icu oniguruma libxml2 mysql-client

# Install PHP extensions
RUN apk add --no-cache --virtual .build-deps \
    autoconf g++ make pkgconf re2c \
    zlib-dev libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    icu-dev oniguruma-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd intl mbstring xml soap bcmath opcache calendar \
    && apk del .build-deps

# Copy application from builder
COPY --from=builder /var/www/html /var/www/html

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
