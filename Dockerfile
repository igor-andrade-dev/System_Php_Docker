FROM php:8.1-apache

# Instala extensões necessárias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia os arquivos para o container
COPY . /var/www/html

# Define permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
