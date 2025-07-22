# 1. Base PHP + Apache
FROM php:8.3-apache

# 2. Install libs système + extensions PHP
RUN apt-get update \
 && apt-get install -y \
      libicu-dev \
      libzip-dev \
      libpng-dev \
      libonig-dev \
      git \
      unzip \
 && docker-php-ext-install intl pdo pdo_mysql zip

# 3. Rewrite + headers + DocumentRoot → public/
RUN a2enmod rewrite headers \
 && sed -ri 's!/var/www/html!/var/www/html/public!g' \
     /etc/apache2/sites-available/*.conf \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' \
     /etc/apache2/apache2.conf

# 4. Copier le code source
WORKDIR /var/www/html
COPY . .

# 4a. Créer un .env minimal pour le build
RUN printf "APP_ENV=prod\nAPP_DEBUG=0\n" > .env

# 4b. Copier & activer la conf CORS
# COPY cors.conf /etc/apache2/conf-available/cors.conf
# RUN a2enconf cors

# 5. Installer Composer et autoriser les plugins en root
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# 6. Installer les dépendances PHP SANS auto‑scripts (--no-scripts)
#    pour éviter le cache:clear en build qui nécessite DATABASE_URL
RUN composer install \
      --no-interaction \
      --prefer-dist \
      --optimize-autoloader \
      --no-scripts

# 7. Générer un autoload optimisé
RUN composer dump-autoload --classmap-authoritative

# 8. Préparer les dossiers et permissions
RUN mkdir -p var public/uploads \
 && chown -R www-data:www-data var vendor public/uploads

# 9. Exposer et lancer Apache
EXPOSE 80
CMD ["apache2-foreground"]
