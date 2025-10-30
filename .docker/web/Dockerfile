FROM php:8.2-apache

RUN apt update

RUN apt install -y vim git default-mysql-client nano cron wget

#bcmath
RUN docker-php-ext-install bcmath

#mbstring
RUN apt-get install -y libonig-dev
RUN docker-php-ext-install mbstring

RUN docker-php-ext-install pdo_mysql

#zip
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

#mysql 
RUN docker-php-ext-install mysqli
RUN echo -e "[mysqld]\nskip-ssl" >> /etc/mysql/my.cnf

#intl
RUN apt-get install -y libicu-dev
RUN docker-php-ext-install intl

# supervisor
RUN apt install -y supervisor
ADD ./.docker/web/supervisor/conf.d/laravel-scheduler.conf /etc/supervisor/conf.d/

# NODE
ARG INSTALL_NODE=true
RUN if [ ${INSTALL_NODE} = true ]; then \
   curl -fsSL https://deb.nodesource.com/setup_21.x | bash - &&\
   apt install -y nodejs \
;fi


# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# configure PHP
RUN sed -i -e "s/;\?max_input_vars = .*/max_input_vars = 5000/g"  /usr/local/etc/php/php.ini-production
RUN sed -i -e "s/post_max_size = 8M/post_max_size = 800M/g" /usr/local/etc/php/php.ini-production
RUN sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 2000M/g" /usr/local/etc/php/php.ini-production
RUN sed -i -e "s/max_file_uploads = 20/max_file_uploads = 2000/g" /usr/local/etc/php/php.ini-production

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt -y install locales-all

#apache
RUN a2enmod rewrite
RUN a2enmod headers

RUN sed -ri -e 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN rm -r /var/www/html

# Opcache

RUN docker-php-ext-install opcache

COPY .docker/web/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

ARG USERNAME=root
ARG UID=0
ARG GROUP=root
ARG ROOTPASS=l3tm31n

RUN usermod --password $(echo ${ROOTPASS} | openssl passwd -1 -stdin) root

RUN if [ ${USERNAME} != "root" ]; then \
   useradd -ms /bin/bash ${USERNAME} -u ${UID} \
;fi

USER ${USERNAME}:${GROUP}

# crontab
RUN (crontab -l 2>/dev/null; echo "* * * * * su -l www-data -s /bin/bash -c 'cd /app/ && /usr/local/bin/php artisan schedule:run >> /app/storage/logs/cron-log 2>&1'") | crontab -
