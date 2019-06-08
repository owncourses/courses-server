#!/usr/bin/env bash
/app/.heroku/php/bin/php bin/console app:user:create test@example.com testPassword Test User &&
/app/.heroku/php/bin/php bin/console app:user:promote test@example.com ROLE_ADMIN
