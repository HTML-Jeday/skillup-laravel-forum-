FROM webdevops/php:8.4

WORKDIR /laravel

# Install packages or extensions (if needed)
RUN apt-get update && apt-get install -y unzip zip

# Copy code
COPY . .

# Set correct permissions
RUN mkdir -p /laravel/storage /laravel/bootstrap/cache && \
    chown -R www-data:www-data /laravel/storage /laravel/bootstrap/cache

CMD ["php-fpm"]
