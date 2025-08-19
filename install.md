# 1. Crear el proyecto
composer create-project --prefer-dist laravel/laravel urus

# 2. Entrar al directorio del proyecto
cd urus

# 3. Copiar el .env de ejemplo y generar la clave de aplicación
cp .env.example .env
php artisan key:generate

# 4. (Opcional) Ajusta en .env tus credenciales de base de datos antes de migrar

# 5. Ejecutar migraciones
php artisan migrate

# 6. Iniciar el servidor de desarrollo
php artisan serve

