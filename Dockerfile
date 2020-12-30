FROM nginx/unit:1.21.0-php7.3

# Disable log output
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

# Run update
RUN apt -q update && apt -y -q install unzip procps wget
##	LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php

RUN apt-get update -y && apt-get install -y -q php7.3-cli php7.3-mysqli php7.3-json php7.3-curl \
    php7.3-xml php7.3-phar php7.3-intl php7.3-simplexml php7.3-zip php7.3-dom \
    php7.3-mbstring php7.3-xmlwriter php7.3-bcmath php7.3-tokenizer \
    php7.3-fileinfo php7.3-posix php7.3-ctype php7.3-exif php7.3-gd php7.3-iconv \
    php7.3-xmlreader php7.3-json php7.3-sqlite3


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Copy Unit config file for initialization on start
COPY unit_config.json /docker-entrypoint.d/conf.json

# Copy code to container
RUN mkdir /application
COPY . /application/
# Fix permisions
RUN chown www-data:www-data /application -R

# Set working dir and nobody user
WORKDIR /application

# Switch to user nobody and run composer
USER www-data

# Create SQL DB
RUN touch /application/database/database.sqlite

# Adjust env file
COPY .env.docker .env

# Install vendor packages
RUN composer --quiet install

# Run laravel migration
RUN php artisan migrate && php artisan db:seed

# Switch back to root
USER root

# Prepare accesslog redirect to stdout
# Forward request and error logs to docker log collector
RUN mkdir -p /var/log/unit/ \
    && touch /var/log/unit/access.log \
    && ln -sf /dev/stdout /var/log/unit/access.log

EXPOSE 8080

# ENTRYPOINT and CMD default from image


