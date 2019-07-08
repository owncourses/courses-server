#!/usr/bin/env bash
/app/.heroku/php/bin/php bin/console doctrine:fixtures:load --no-interaction &&
/app/.heroku/php/bin/php bin/console app:user:create test@example.com testPassword Super Test --courses="From courses maker ZERO to HERO" &&
/app/.heroku/php/bin/php bin/console app:user:promote test@example.com ROLE_ADMIN
