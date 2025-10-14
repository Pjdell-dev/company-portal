# backend/Dockerfile
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Expose port
EXPOSE 8000

# Start server (will be overridden by docker-compose command)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]