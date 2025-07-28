FROM php:8.1-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration de Nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
RUN rm -f /etc/nginx/sites-enabled/default

# Créer les répertoires nécessaires
RUN mkdir -p /var/log/nginx /run/php /var/www/html/public/images/uploads && \
    chown -R www-data:www-data /var/log/nginx && \
    chown -R www-data:www-data /run/php

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers du projet
COPY . .

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader

# Permissions pour les dossiers
RUN chown -R www-data:www-data /var/www/html

# Script de démarrage
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set environment variables
ENV DB_HOST=aws-0-eu-west-3.pooler.supabase.com \
    DB_PORT=5432 \
    DB_NAME=postgres \
    DB_USER=postgres.koplwfnyoqkslijoxmkq \
    DB_PASS=laye1234

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]