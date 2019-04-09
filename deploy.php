<?php

namespace Deployer;

require 'recipe/symfony4.php';

set('application', 'owncourses');
set('default_stage', 'demo');

set('env', function () {
    return [
        'APP_ENV' => has('symfony_env') ? get('symfony_env') : 'prod',
    ];
});

set('repository', 'git@github.com:owncourses/courses-server.git');
set('git_tty', true);
set('shared_files', [
    '.env.local',
    '.env.local.php',
    'config/jwt/private.pem',
    'config/jwt/public.pem',
]); // reset sharing .env

inventory('hosts.yml');

after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'database:migrate');
