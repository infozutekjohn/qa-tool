FROM php:8.3-fpm

# Install system packages, PHP extensions, and Node.js
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy your Laravel project into the image
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
