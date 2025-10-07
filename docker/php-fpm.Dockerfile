FROM php:8.1-fpm

# system deps
RUN apt-get update && apt-get install -y git unzip libzip-dev zip libpng-dev libonig-dev libxml2-dev     && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY src/composer.json src/composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction || true

# copy app files
COPY src/ ./

# Generate key after vendor installed (during container start in entrypoint)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
