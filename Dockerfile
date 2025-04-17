FROM dunglas/frankenphp

# Install dependensi dasar + ekstensi PostgreSQL
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql pgsql pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Optional: install Composer jika belum ada
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer