FROM php:8.2-fpm

# Install system dependencies and Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql pdo_mysql zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && sed -i 's/pm.max_children = 5/pm.max_children = 20/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/pm.start_servers = 2/pm.start_servers = 5/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/pm.min_spare_servers = 1/pm.min_spare_servers = 2/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/pm.max_spare_servers = 3/pm.max_spare_servers = 8/g' /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Nginx configuration
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

RUN rm /etc/nginx/sites-enabled/default
# Expose port 80
EXPOSE 80

# Set permissions at runtime, then start services
CMD ["sh", "-c", "chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true \
    && php-fpm -D \
    && sleep 2 \
    && nginx -g 'daemon off;'"]