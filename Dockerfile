FROM php:8.3-cli

# Instalace závislostí
RUN apt-get update && apt-get install -y unzip curl libsqlite3-dev sqlite3

# Instalace Composeru
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Nastavení pracovního adresáře
WORKDIR /app

# Kopírování souborů do kontejneru
COPY . .

# Instalace závislostí
RUN composer install

# Nastavení práv pro úložiště a cache
RUN chmod -R 777 storage bootstrap/cache

# Nastavení správných oprávnění pro entrypoint.sh
RUN chmod +x /app/entrypoint.sh

# Definice entrypointu
ENTRYPOINT ["/app/entrypoint.sh"]

# Výchozí příkaz pro spuštění aplikace
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
