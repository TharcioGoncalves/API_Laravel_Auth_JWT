#!/usr/bin/env bash
echo "Rodando composer..."
composer install --no-dev --working-dir=/var/www/html

echo "Gerando app key (se ainda não tiver)..."
php artisan key:generate --force

echo "Cacheando config..."
php artisan config:cache

echo "Cacheando rotas..."
php artisan route:cache

echo "Rodando migrations..."
php artisan migrate --force
