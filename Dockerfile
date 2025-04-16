FROM dunglas/frankenphp

# Install dependencies + PHP extensions for MySQL and Excel support
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Jalankan aplikasi menggunakan PHP built-in web server
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]