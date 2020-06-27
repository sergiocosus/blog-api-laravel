#!/bin/bash
composer install --no-interaction --prefer-dist --optimize-autoloader
php artisan db:seed --class DeploySeeder
php artisan db:seed --class SettingsSeeder
php artisan helpers:front-end
php artisan helpers:permission-php
php artisan cache:clear
