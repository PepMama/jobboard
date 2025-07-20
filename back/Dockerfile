# Utilise l'image officielle PHP avec Apache
FROM php:8.3-apache

# Installe les extensions PHP nécessaires
RUN apt-get update \
    && apt-get install -y libicu-dev libzip-dev libpng-dev libonig-dev git unzip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Active le module Apache rewrite
RUN a2enmod rewrite

# Désactive la configuration par défaut et copie notre configuration
RUN a2dissite 000-default
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default

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

# Expose le port 80 (déjà fait par l'image de base)
EXPOSE 80 