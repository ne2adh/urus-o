# 1. Modelo + migración + controlador
php artisan make:model Participante -m -c

# 2. Request para validar el registro
php artisan make:request StoreParticipanteRequest

# 3. Seeder para datos de prueba
php artisan make:seeder ParticipantesTableSeeder

# 4. Generar la Factory
php artisan make:factory ParticipanteFactory --model=Participante

# Si quieres borrar y recrear todo (tablas + datos de prueba)
php artisan migrate:fresh --seed


# Regenera el autoload
composer dump-autoload


# Limpia la caché de rutas
php artisan route:clear


# 1. Instalar el paquete
composer require tymon/jwt-auth

# 2. Publicar configuración
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

# 3. Generar la clave secreta
php artisan jwt:secret


# (Solo esa migración)
php artisan migrate:rollback --path=database/migrations/2025_08_10_000000_create_participantes_table.php
php artisan migrate --path=database/migrations/2025_08_10_000000_create_participantes_table.php
php artisan db:seed --class=ParticipanteSeeder

# O borrar todo y sembrar todo (dev)
php artisan migrate:fresh --seed

# Optimiza autoload
composer dump-autoload -o
php artisan config:cache
php artisan route:cache
php artisan view:cache

composer dump-autoload -o && php artisan route:clear && php artisan config:clear && php artisan cache:clear
