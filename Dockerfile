FROM php:8.2-apache

# Installation des dépendances système et des extensions PHP pour PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activation du module de réécriture Apache (très utile pour la gestion des redirections et des routes)
RUN a2enmod rewrite

# Copie de tout le contenu du projet dans le répertoire web d'Apache
COPY . /var/www/html/

# Configuration des bonnes permissions pour Apache
RUN chown -R www-data:www-data /var/www/html

# Le serveur Apache écoute sur le port 80 par défaut
EXPOSE 80