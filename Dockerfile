FROM php:8.4-fpm

# Install system dependencies & extension packages
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libpq-dev \
    nginx

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_pgsql pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Script entrypoint untuk mengganti port Nginx sesuai Environment PORT Railway
RUN echo '#!/bin/sh\n\
PORT=${PORT:-80}\n\
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/sites-available/default\n\
php-fpm -D\n\
nginx -g "daemon off;"' > /entrypoint.sh && chmod +x /entrypoint.sh

# Nginx config template
RUN echo 'server {\n\
    listen 80;\n\
    index index.php index.html;\n\
    error_log  /var/log/nginx/error.log;\n\
    access_log /var/log/nginx/access.log;\n\
    root /var/www/public;\n\
    location ~ \.php$ {\n\
        try_files $uri =404;\n\
        fastcgi_split_path_info ^(.+\.php)(/.+)$;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        include fastcgi_params;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        fastcgi_param PATH_INFO $fastcgi_path_info;\n\
    }\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
        gzip_static on;\n\
    }\n\
}' > /etc/nginx/sites-available/default

CMD ["/entrypoint.sh"]