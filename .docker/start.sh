#!/bin/sh
set -e

echo "==> bootstrap"
mkdir -p /var/www/html/storage \
         /var/www/html/storage/app/public \
         /var/www/html/storage/framework/{cache,sessions,testing,views} \
         /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html

# (opcional) si alguien dejó Pail en prod
sed -i '/Laravel\\Pail\\PailServiceProvider::class/d' /var/www/html/config/app.php || true

# symlink storage
if [ ! -e /var/www/html/public/storage ]; then
  ln -s /var/www/html/storage/app/public /var/www/html/public/storage || true
fi

# asegurar path de vistas compiladas (evita "Please provide a valid cache path")
export VIEW_COMPILED_PATH=/var/www/html/storage/framework/views

echo "==> limpiar caches"
rm -f /var/www/html/bootstrap/cache/*.php || true
php /var/www/html/artisan config:clear  || true
php /var/www/html/artisan cache:clear   || true
php /var/www/html/artisan route:clear   || true
[ -d /var/www/html/resources/views ] && php /var/www/html/artisan view:clear || true
php /var/www/html/artisan package:discover --ansi || true

# ----------- esperar DB antes de migrar/sembrar -----------
echo "==> esperando DB $DB_HOST:$DB_PORT ..."
TRIES=0
until php -r '
$h=getenv("DB_HOST"); $p=getenv("DB_PORT")?:3306; $d=getenv("DB_DATABASE");
$u=getenv("DB_USERNAME"); $pw=getenv("DB_PASSWORD");
try { new PDO("mysql:host=$h;port=$p;dbname=$d",$u,$pw); exit(0);} catch(Exception $e){exit(1);}';
do
  TRIES=$((TRIES+1))
  if [ $TRIES -ge 30 ]; then
    echo "No se pudo conectar a la DB tras $TRIES intentos." >&2
    break
  fi
  sleep 2
done

# ----------- migraciones (opcional por ENV) -----------
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo "==> php artisan migrate --force"
  php /var/www/html/artisan migrate --force || true
fi
# ----------- seeders (según flag) -----------
if [ "${RUN_SEEDER}" = "true" ]; then
  echo "==> php artisan db:seed --force"
  php /var/www/html/artisan db:seed --force || true
fi

# recompilar caches para prod
echo "==> recompilando caches"
php /var/www/html/artisan config:cache  || true
php /var/www/html/artisan route:cache   || true
[ -d /var/www/html/resources/views ] && php /var/www/html/artisan view:cache || true

echo "==> arrancando php-fpm + nginx"
php-fpm -D
exec nginx -g "daemon off;"
