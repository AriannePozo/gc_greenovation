#!/bin/bash

echo "🚀 Iniciando aplicación Laravel con Docker..."

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker no está instalado. Por favor instala Docker primero."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose no está instalado. Por favor instala Docker Compose primero."
    exit 1
fi

# Crear archivo .env si no existe
if [ ! -f .env ]; then
    echo "📝 Copiando configuración de entorno para Docker..."
    cp .env.docker .env
    echo "⚠️  IMPORTANTE: Genera una nueva APP_KEY ejecutando: php artisan key:generate"
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
chmod -R 775 storage bootstrap/cache

echo "🏗️  Construyendo contenedores..."
docker-compose build

echo "🚀 Levantando servicios..."
docker-compose up -d

# Copiar archivo .env al contenedor
echo "📝 Copiando archivo de configuración..."
docker-compose exec app cp .env.docker .env

# Esperar a que la base de datos esté lista
echo "⏳ Esperando a que la base de datos esté lista..."
echo "   Verificando conexión a MySQL..."
until docker-compose exec db mysql -u root -p1234 -e "SELECT 1" >/dev/null 2>&1; do
    echo "   MySQL aún no está listo, esperando 3 segundos..."
    sleep 3
done
echo "   ✅ MySQL está listo!"

echo "🔑 Generando clave de aplicación..."
docker-compose exec app php artisan key:generate --force

echo "📋 Ejecutando migraciones..."
if docker-compose exec app php artisan migrate --force; then
    echo "   ✅ Migraciones ejecutadas correctamente"
else
    echo "   ❌ Error en las migraciones"
    exit 1
fi

echo "🌱 Ejecutando seeders..."
if docker-compose exec app php artisan db:seed --force; then
    echo "   ✅ Seeders ejecutados correctamente"
else
    echo "   ⚠️  Error en seeders (puede ser normal si ya existen datos)"
fi

echo "🔗 Creando enlace simbólico para storage..."
docker-compose exec app php artisan storage:link

echo "🧹 Limpiando caché..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

echo ""
echo "✅ ¡Aplicación iniciada exitosamente!"
echo ""
echo "🌐 Aplicación web: http://localhost:8000"
echo "🗄️  PHPMyAdmin: http://localhost:8080"
echo "📡 MQTT Broker: localhost:1883"
echo "🔍 MQTT Explorer: http://localhost:4000"
echo ""
echo "📊 Para ver los logs:"
echo "   docker-compose logs -f app"
echo ""
echo "🔌 Para conectar por MQTT:"
echo "   Host: localhost"
echo "   Puerto: 1883"
echo "   Tópico: smartbin/measurements"
echo ""
echo "🛑 Para detener los servicios:"
echo "   docker-compose down"