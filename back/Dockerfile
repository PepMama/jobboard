# 1. Base PHP + Apache
FROM php:8.3-apache

# 2. Extensions système + PHP
RUN apt-get update \
 && apt-get install -y libicu-dev libzip-dev libpng-dev libonig-dev git unzip \
 && docker-php-ext-install intl pdo pdo_mysql zip

# 3. Mod rewrite + override .htaccess
RUN a2enmod rewrite \
 && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 4. Copier le code dans /var/www/html
WORKDIR /var/www/html
COPY . .

# 5. Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN composer dump-env prod

# 6. Permissions
# 6a) S'assurer que var/ existe (sinon chown plante)
RUN mkdir -p var
# 6b) Donner les droits à www-data sur var/ et vendor/
RUN chown -R www-data:www-data var vendor

# 7. Exposer et lancer Apache
EXPOSE 80
CMD ["apache2-foreground"]
