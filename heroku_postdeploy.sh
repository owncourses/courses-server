#!/usr/bin/env bash
#/app/.heroku/php/bin/php bin/console app:user:create test@example.com testPassword Test User &&
#/app/.heroku/php/bin/php bin/console app:user:promote test@example.com ROLE_ADMIN &&
openssl genrsa -passout env:JWT_PASSPHRASE -out config/jwt/private.pem -aes256 4096 &&
openssl rsa -pubout -passin env:JWT_PASSPHRASE -in config/jwt/private.pem -out config/jwt/public.pem
