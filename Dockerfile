FROM webdevops/php:8.4

# Install additional PHP extensions or system packages if needed
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Optionally install Composer globally if not included (usually included in webdevops/php)

WORKDIR /laravel

# Copy your application (optional because you mount volume in docker-compose)
# COPY . .

# Run Composer install (optional - can be done manually later)
# RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data /laravel/storage /laravel/bootstrap/cache

CMD ["php-fpm"]
