# Example: Alpine + PHP + Composer for Laravel
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add \
    bash \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    oniguruma-dev \
    zip \
    unzip \
    npm \
    nodejs

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# ✅ Install Composer (option 1: from official image)
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# OR option 2: download manually
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /app

COPY composer.json composer.lock ./

# ✅ Always use lowercase for commands!
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . /app

RUN composer run-script post-autoload-dump

RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link

RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 9000

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]
