#!/bin/sh

echo "🎬 entrypoint.sh"
echo "🎬 symfony commands"
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console ckeditor:instal
php bin/console assets:install --no-interaction

echo "🎬 set rights on cache"
chmod -R 777 /var/www/var/cache/*
chmod -R 777 /var/www/var/log/*

echo "🎬 nginx start"
php-fpm &
nginx -g "daemon off;"
