# Utilise l'image officielle PHP
FROM php:8.3-cli

# Installe les extensions PHP nécessaires
RUN apt-get update \
    && apt-get install -y libicu-dev libzip-dev libpng-dev libonig-dev git unzip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Copie le code source dans le conteneur
COPY . /var/www/html

# Définit le répertoire de travail
WORKDIR /var/www/html

# Installe Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Installe les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Donne les droits nécessaires au dossier var
RUN mkdir -p var && chown -R www-data:www-data var

# Expose le port 8000
EXPOSE 8000

# Démarre le serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"] 