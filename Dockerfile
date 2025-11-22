# ---------- build stage ----------
FROM composer:2 AS builder

WORKDIR /app

# copy composer.json
COPY composer.json composer.lock /app 

# Install dependencies TANPA jalankan scripts, agar skip post-autoload-dump yang butuh artisan
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction --prefer-dist --no-scripts

# Copy seluruh code app
COPY . .
COPY .env /app/.env

# Jalankan scripts manual setelah code dicopy (re-create autoload dan discover packages)
RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi

# Stage 2: Runtime dengan FrankenPHP minimal
FROM dunglas/frankenphp:php8.4-alpine

# Install extensions using FrankenPHP's helper script (handles build deps and cleanup)
RUN install-php-extensions zip pdo_pgsql pcntl

# Install extensions PHP yang diperlukan Laravel (jika belum ada di base image)
# Tambah postgresql-dev untuk pdo_pgsql, dan build-deps temp untuk compile
RUN apk add --no-cache \
    libzip-dev \
    unzip \
    postgresql-libs  # Runtime lib untuk PostgreSQL client \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev \
    && docker-php-ext-install zip pdo_pgsql pcntl \
    && apk del .build-deps  # Hapus build deps untuk kurangi ukuran imag

WORKDIR /app

# Copy custom PHP configuration
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Copy artifacts dari builder stage
COPY --from=builder /app /app

# Copy Caddyfile untuk FrankenPHP (config sederhana untuk worker mode)
COPY ./docker/caddy/Caddyfile /etc/caddy/Caddyfile

# Pastikan direktori storage & cache ada
RUN mkdir -p /app/storage/framework/{cache,sessions,views} \
    && mkdir -p /app/bootstrap/cache \
    # Buat preload file dummy agar PHP tidak error saat build
    && touch /app/bootstrap/cache/preload.php \
    && chmod -R 777 /app/bootstrap/cache/preload.php \
    # Set ownership agar Laravel bisa menulis ke storage
    && chown -R www-data:www-data /app \
    # Jalankan artisan basic setup (aman untuk build)
    && php artisan storage:link || true \
    && php artisan optimize:clear || true \
    && php artisan config:clear || true \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan optimize

# Enable worker mode via ENV (ini ganti CMD custom)
# ENV FRANKENPHP_CONFIG="worker /app/public/index.php"

# Copy entrypoint custom agar bisa auto-detect CPU
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x ./docker/entrypoint.sh

# Hapus yang ga kepake di linux-aplinenya
RUN rm -rf /var/cache/apk/* \
    && rm -rf  /var/lib/apt/lists/*

# Expose port
EXPOSE 80 443

# Run FrankenPHP di worker mode dengan command custom
# CMD ["frankenphp", "php-server", "--root", "/app/public", "--workers", "4"]  # Sesuaikan jumlah workers berdasarkan CPU

ENTRYPOINT ["./docker/entrypoint.sh"]
