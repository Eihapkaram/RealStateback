FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /app

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# إنشاء المجلدات المطلوبة
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache

# صلاحيات كاملة للوصول والكتابة
RUN chmod -R 777 storage bootstrap/cache

# إنشاء storage link
RUN php artisan storage:link || true

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
