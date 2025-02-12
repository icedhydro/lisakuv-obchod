#!/bin/bash
set -e

# Kontrola, zda existuje .env soubor, pokud ne, vytvoříme
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generování klíče aplikace
php artisan key:generate

# Instalace Composer balíčků
composer install --no-interaction --prefer-dist

# Kontrola, zda existuje databázový soubor, pokud ne, vytvoří
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chmod -R 777 database
fi

# Spuštění migrací a seedování
php artisan migrate:fresh --seed

# Swagger dokumentace
php artisan l5-swagger:generate

# Spuštění aplikace
exec php artisan serve --host=0.0.0.0 --port=8000
