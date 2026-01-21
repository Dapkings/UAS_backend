# Gunakan image PHP 8.2
FROM php:8.2-cli

# Install library system
WORKDIR /app
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy semua file project
COPY . .

# Install dependency Laravel
RUN composer install

# --- BAGIAN PERBAIKAN ---
# 1. Salin file contoh environment ke .env
RUN cp .env.example .env

# 2. Generate Application Key (Wajib agar Laravel jalan)
RUN php artisan key:generate

# 3. Bersihkan config cache agar tidak error
RUN php artisan config:clear
# ------------------------

# Buka port 3000
EXPOSE 3000

# Jalankan server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=3000"]