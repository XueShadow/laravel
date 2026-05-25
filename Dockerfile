FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    postgresql-client \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build frontend
RUN npm install && npm run build

# Create necessary directories
RUN mkdir -p storage/logs bootstrap/cache && chmod -R 755 storage bootstrap/cache

# Expose port
EXPOSE 10000

# Run migrations and start server
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan seed --force && \
    php -S 0.0.0.0:10000 -t public/
