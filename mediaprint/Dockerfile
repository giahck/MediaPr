# Usa l'immagine PHP ufficiale con Apache
FROM php:8.1-apache

# Installa le estensioni PDO e pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql

# Abilita mod_rewrite di Apache
RUN a2enmod rewrite

# Copia la configurazione personalizzata di Apache
COPY custom-apache-config.conf /etc/apache2/sites-available/000-default.conf

# Riavvia Apache per applicare le modifiche
RUN service apache2 restart
