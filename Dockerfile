FROM php:8.4-fpm

# Install system dependencies & extensions
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

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_pgsql pgsql

# PHP custom config: increase upload limit for profile photos
RUN echo "upload_max_filesize = 10M\npost_max_size = 12M\nmemory_limit = 256M\nmax_execution_time = 60" \
    > /usr/local/etc/php/conf.d/uploads.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Permission folder storage & cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Buat symbolic link storage public
RUN php artisan storage:link || true

# Konfigurasi Nginx Natively
RUN echo 'server {\n\
    listen 80 default_server;\n\
    listen [::]:80 default_server;\n\
    root /var/www/public;\n\
    index index.php index.html;\n\
    server_name _;\n\
    client_max_body_size 15M;\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
    location ~ \.php$ {\n\
        try_files $uri =404;\n\
        fastcgi_split_path_info ^(.+\.php)(/.+)$;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        include fastcgi_params;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        fastcgi_param PATH_INFO $fastcgi_path_info;\n\
    }\n\
}' > /etc/nginx/sites-available/default

EXPOSE 80

# Jalankan storage link, PHP-FPM di background dan Nginx di foreground
CMD php artisan storage:link && php-fpm -D && nginx -g "daemon off;"