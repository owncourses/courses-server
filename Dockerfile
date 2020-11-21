FROM ahilles107/php-docker:7.4

ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN usermod -u 1000 www-data
# Copy existing application directory
COPY . /var/www/
RUN ls /var/www

WORKDIR /var/www

RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader && rm -rf /root/.composer
RUN composer dump-autoload --no-interaction --no-dev --optimize

COPY ./docker/nginx.conf /etc/nginx/conf.d/nginx.conf
RUN ls /etc/nginx/conf.d

RUN rm -rf /etc/nginx/sites-enabled
RUN mkdir -p /etc/nginx/sites-enabled
RUN chmod -R 777 /var/www/public

RUN mkdir -p /var/www/var/log/
RUN chmod -R 777 /var/www/var/log/
RUN mkdir -p /var/www/var/cache/prod/
RUN chmod -R 777 /var/www/var/cache/*

EXPOSE 80

COPY ./docker/entrypoint.sh /
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/bin/bash", "/entrypoint.sh"]
