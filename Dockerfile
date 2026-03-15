FROM php:8.1-apache-bullseye

WORKDIR /var/www/html

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required Linux libraries (with retries for unstable networks)
RUN apt-get update -o Acquire::Retries=3 && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip \
    zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install \
    gettext \
    intl \
    pdo_mysql \
    gd

# Set Apache document root to Laravel public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# in Dockerfile
CMD [ "bash", "-c", "if [ ! -f vendor/autoload.php ]; then composer install; fi && apache2-foreground" ]