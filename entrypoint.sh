#!/bin/bash
set -e

# Kontrola, zda existuje .env soubor, pokud ne, vytvoříme ho
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Instalace Composer závislostí
if [ ! -d "vendor" ]; then
    composer install --no-interaction --prefer-dist
fi

# Generování klíče aplikace
php artisan key:generate

# Kontrola, zda existuje SQLite databázový soubor
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chmod -R 777 database
fi

# Spuštění migrací a seedování
php artisan migrate:fresh --seed

# Generování Swagger dokumentace
php artisan l5-swagger:generate

# Spuštění aplikace
exec php artisan serve --host=0.0.0.0 --port=8000
