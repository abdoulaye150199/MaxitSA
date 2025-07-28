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

# Créer les répertoires nécessaires et configurer les permissions
RUN mkdir -p /var/log/nginx /run/php /var/www/html/public/images/uploads && \
    chown -R www-data:www-data /var/log/nginx && \
    chown -R www-data:www-data /run/php && \
    chown -R www-data:www-data /var/www/html

# Définition du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers du projet
COPY . .

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader

# Script de démarrage
COPY docker/start.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]

