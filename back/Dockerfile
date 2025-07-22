# 1. Base PHP + Apache
FROM php:8.3-apache

# 2. Extensions système + PHP
RUN apt-get update \
 && apt-get install -y \
      libicu-dev \
      libzip-dev \
      libpng-dev \
      libonig-dev \
      git \
      unzip \
 && docker-php-ext-install intl pdo pdo_mysql zip

# 3. Activer rewrite + headers et basculer DocumentRoot sur public/
RUN a2enmod rewrite headers \
 && sed -ri 's!/var/www/html!/var/www/html/public!g' \
     /etc/apache2/sites-available/*.conf \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' \
     /etc/apache2/apache2.conf

# 4. Copier le code dans /var/www/html
WORKDIR /var/www/html
COPY . .

# 4a. Créer dynamiquement un .env pour le build
RUN printf "APP_ENV=prod\nAPP_DEBUG=0\n" > .env

# 4b. Copier et activer la conf Apache pour CORS
COPY cors.conf /etc/apache2/conf-available/cors.conf
RUN a2enconf cors

# 5. Installer Composer et autoriser les plugins en root
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# 6. Installer les dépendances PHP (avec auto-scripts)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# 7. Générer les variables d’environnement prod et vider le cache
RUN composer dump-env prod \
 && php bin/console cache:clear --no-warmup --env=prod

# 8. S’assurer que var/ et public/uploads existent et appartiennent à www-data
RUN mkdir -p var public/uploads \
 && chown -R www-data:www-data var vendor public/uploads

# 9. Exposer le port 80 et lancer Apache
EXPOSE 80
CMD ["apache2-foreground"]
