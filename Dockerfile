FROM php:7.1-apache

# Instala as extensões necessárias do PHP
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) iconv pdo pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# Habilitar o mod_rewrite do Apache
RUN a2enmod rewrite

# Instalar Composer
RUN php -r "copy('https://getcomposer.org/download/1.10.26/composer.phar', 'composer.phar');" \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer

# Copia o projeto Yii2 para o container
COPY . /var/www/html

# Configura o documento root
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
