# FROM php:8.3-cli

# # Install system packages and PHP extensions
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     curl \
#     libzip-dev \
#     libpng-dev \
#     libonig-dev \
#     libcurl4-openssl-dev \
#     && docker-php-ext-install pdo pdo_mysql zip curl \
#     && rm -rf /var/lib/apt/lists/*

# # Install Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# WORKDIR /app

# # Copy composer files first for better caching
# COPY composer.json composer.lock ./

# # Install ALL dependencies (including dev for testing)
# RUN composer install --optimize-autoloader --no-interaction

# # Copy the rest of the application
# COPY . .

# # Create necessary directories
# RUN mkdir -p storage/logs bootstrap/cache allure-results \
#     && chmod -R 775 storage bootstrap/cache allure-results

# # Set environment
# ENV APP_ENV=production
# ENV LOG_CHANNEL=stderr

# # Expose port for Laravel server (optional - for web interface)
# EXPOSE 8080

# # Default command runs the tests
# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]



FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip curl \
    libzip-dev libpng-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip curl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Cache deps layer
COPY composer.json composer.lock ./

# Install deps but DO NOT run artisan scripts yet
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# Now copy the whole Laravel app (includes routes/)
COPY . .

# Now scripts are safe to run
RUN php artisan package:discover --ansi

RUN mkdir -p storage/logs bootstrap/cache allure-results \
    && chmod -R 775 storage bootstrap/cache allure-results

ENV APP_ENV=production
ENV LOG_CHANNEL=stderr

EXPOSE 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
