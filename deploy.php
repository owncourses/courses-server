<?php

namespace Deployer;

require 'recipe/symfony4.php';

set('application', 'owncourses');
set('default_stage', 'demo');

set('env', static function () {
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

task('deploy:assets:install', static function () {
    run('{{bin/console}} ckeditor:install');
    run('{{bin/console}} assets:install {{release_path}}/public');
})->desc('Install bundle assets');
after('deploy:symlink', 'deploy:assets:install');

task('deploy:composer:dump-env', static function () {
    run('cd {{release_path}} && {{bin/composer}} symfony:dump-env prod');
})->desc('Install bundle assets');
after('deploy:symlink', 'deploy:composer:dump-env');

after('deploy:failed', 'deploy:unlock');
before('deploy:symlink', 'database:migrate');
