FROM dunglas/frankenphp

# Install dependensi dasar + ekstensi MySQL
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Optional: install Composer jika belum ada
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]