#!/bin/bash

echo "🚀 Iniciando aplicación Laravel..."

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    echo "📝 Copiando configuración de entorno..."
    cp .env.docker .env
fi

# Crear directorios necesarios si no existen
mkdir -p storage/app/public
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Configurar permisos
echo "🔧 Configurando permisos..."
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache

# Esperar a que la base de datos esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
until mysql -h db -u root -p1234 --skip-ssl -e "SELECT 1" >/dev/null 2>&1; do
    echo "   MySQL aún no está listo, esperando 3 segundos..."
    sleep 3
done
echo "   ✅ MySQL está listo!"

# Generar clave de aplicación si no existe
if grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Clave de aplicación ya existe"
else
    echo "🔑 Generando clave de aplicación..."
    php artisan key:generate --force
fi

# Ejecutar migraciones
echo "📋 Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders
echo "🌱 Ejecutando seeders..."
php artisan db:seed --force || echo "⚠️  Seeders ya ejecutados o error (normal)"

# Crear enlace simbólico para storage
echo "🔗 Creando enlace simbólico para storage..."
php artisan storage:link

# Limpiar caché
echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "✅ Laravel configurado correctamente!"

# Iniciar MQTT subscriber en background
echo "📡 Iniciando MQTT subscriber..."
php artisan mqtt:subscribe > storage/logs/mqtt-subscriber.log 2>&1 &
echo "   ✅ MQTT subscriber ejecutándose en background"

# Iniciar Apache
echo "🌐 Iniciando servidor web..."
apache2-foreground