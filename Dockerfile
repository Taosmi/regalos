FROM php:8-apache-buster

COPY . /var/www/html

EXPOSE 80
# Enable rewrite module
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
# Enable gettext php extension
RUN docker-php-ext-install gettext
