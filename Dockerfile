FROM php:8.3-fpm

# same uid has the local user, this facilite handling file w/ artisant for example
ARG uid=1000
ARG user=a2n
# hostname -i | awk '{print $1}'
ARG _IP_HOST=192.168.1.199

RUN echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<"
RUN echo "PLEASE CHECK THE IP VALUE FOR THE XDEBUG CONFIG (actually set to: $_IP_HOST)"
RUN echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>"

# setup php and package needed for composer, laravel and so on
RUN apt-get update
RUN apt-get install -y \
    git curl zip unzip iproute2 \
    libpng-dev libjpeg-dev \
    libfreetype6-dev \
    libonig-dev libxml2-dev \
    libpq-dev libzip-dev \
    libcurl4-openssl-dev \
    default-mysql-client

# Use the default dev configuration
# I have splint the comman for a better reading in the console when dcup --build for example
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
#RUN echo "xdebug.mode=develop,coverage,debug,profile" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=$_IP_HOST" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=a2nphpstorm" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log=/dev/stdout" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# php module required by laravel or other
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip curl intl opcache

# same but from pecl
RUN pecl install redis-6.1.0 \
	&& pecl install xdebug-3.4.1 \
	&& docker-php-ext-enable redis xdebug

# system user for composer & artisan
# we use the arg for giving the same uid and name for avoiding problem w/ files permissions
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# install composer from the latest docker image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# set working dir
WORKDIR /

# change to the new syst user
USER $user