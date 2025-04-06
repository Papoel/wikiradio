FROM dunglas/frankenphp:latest

# Install pdo_mysql extension
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip
