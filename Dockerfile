FROM dunglas/frankenphp

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Jalankan aplikasi menggunakan PHP built-in web server (menghindari redirect HTTPS bawaan frankenphp)
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]