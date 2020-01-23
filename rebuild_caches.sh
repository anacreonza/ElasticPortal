#!/bin/bash

# Rebuild site caches

# Recache configs
php artisan config:clear
php artisan config:cache

# Recache routes
php artisan route:clear
php artisan route:cache

# Classmap optimisation
php artisan optimize --force

# Composer Optimize Autoload
composer dumpautoload -o