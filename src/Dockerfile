FROM php:8.1-apache
COPY /apache/default.conf /etc/apache2/sites-enabled/000-default.conf
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
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
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application source code
COPY /src ./
# Update Composer dependencies
#RUN /usr/local/bin/composer update --no-dev --no-interaction
RUN curl -sSO https://files.appoptics.com/appoptics-php.sh
RUN sh appoptics-php.sh --service-key=wXZdRRX7yFZxnjOuXt7_xZpd2YpVfBhZi1j1Mz4GtiPCKX3mACAiNRR65KTl32dw8mHkRnI:sarepay-kyc-3


RUN apt-get install debian-archive-keyring
RUN apt-get install apt-transport-https
RUN curl -L -sS -o ./packagecloud.key https://packagecloud.io/solarwinds/swisnap/gpgkey
RUN apt-key add ./packagecloud.key
RUN export SOLARWINDS_TOKEN="wXZdRRX7yFZxnjOuXt7_xZpd2YpVfBhZi1j1Mz4GtiPCKX3mACAiNRR65KTl32dw8mHkRnI"
RUN apt-get update
RUN apt-get upgrade -y 
RUN apt-get install -y solarwinds-snap-agent

   

RUN service apache2 restart

EXPOSE 80