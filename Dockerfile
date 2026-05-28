FROM php:8.2-apache

# Installation des dépendances système (PostgreSQL + outils indispensables à Composer)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    zip \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activation du module de réécriture Apache
RUN a2enmod rewrite

# Récupération de l'exécutable Composer depuis son image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail (équivalent d'un 'cd /var/www/html')
WORKDIR /var/www/html

# ASTUCE CACHE : On copie d'abord uniquement les fichiers de configuration de Composer
COPY composer.json composer.lock* ./

# Installation des dépendances PHP (sans les outils de dev, idéal pour la production)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copie du reste de l'application dans le conteneur
COPY . .

# Génération de l'autoloader optimisé de Composer
RUN composer dump-autoload --optimize

# Configuration des bonnes permissions pour Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80