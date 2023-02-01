FROM php:8.1-apache


RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zlib1g-dev \
    libzip-dev \
    nano\
    zip \
    unzip 

# Add and Enable PHP-PDO Extenstions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-enable pdo_mysql

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www 
RUN chmod 755 /var/www

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

RUN a2enmod rewrite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN service apache2 restart

