# 🐳 Docker Setup para GreenOvation

Esta configuración Docker incluye todos los servicios necesarios para ejecutar tu aplicación Laravel con MQTT.

## 🏗️ Servicios Incluidos

- **Laravel App**: Tu aplicación principal con PHP 8.2 + Apache
- **MySQL**: Base de datos principal
- **Mosquitto**: Broker MQTT para recibir mensajes de sensores
- **PHPMyAdmin**: Interface web para administrar la base de datos
- **Redis**: Cache y gestión de colas (opcional)

## 🚀 Inicio Rápido

### 1. Ejecutar el script de inicio automático:
```bash
chmod +x docker-start.sh
./docker-start.sh
```

### 2. O manualmente:
```bash
# Copiar configuración de entorno
cp .env.docker .env

# Levantar servicios
docker-compose up -d

# Generar clave de aplicación
docker-compose exec app php artisan key:generate

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Ejecutar seeders
docker-compose exec app php artisan db:seed
```

## 🌐 URLs de Acceso

- **Aplicación Laravel**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
- **MQTT Broker**: localhost:1883

## 📡 Configuración MQTT

El broker Mosquitto está configurado para aceptar conexiones anónimas:

```bash
Host: localhost
Puerto: 1883
Tópico: smartbin/measurements
```

### Ejemplo de mensaje MQTT:
```json
{
    "sensor_id": 1,
    "value": 75.5,
    "container_id": 1,
    "reading_date": "2024-12-17 10:30:00"
}
```

## 🛠️ Comandos Útiles

### Gestión de contenedores:
```bash
# Ver logs de la aplicación
docker-compose logs -f app

# Ver logs del MQTT subscriber
docker-compose logs -f app | grep mqtt

# Entrar al contenedor de la aplicación
docker-compose exec app bash

# Detener servicios
docker-compose down

# Detener y eliminar volúmenes
docker-compose down -v

# Reconstruir contenedores
docker-compose build --no-cache
```

### Comandos Laravel en Docker:
```bash
# Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan queue:work

# Composer
docker-compose exec app composer install
docker-compose exec app composer update

# NPM (si necesitas compilar assets)
docker-compose exec app npm install
docker-compose exec app npm run build
```

## 🗄️ Base de Datos

### Credenciales MySQL:
- **Host**: localhost:3306
- **Database**: laravel
- **Usuario**: laravel
- **Contraseña**: password
- **Root password**: root_password

### PHPMyAdmin:
- **URL**: http://localhost:8080
- **Usuario**: laravel
- **Contraseña**: password

## 📊 Monitoreo

### Ver estado de contenedores:
```bash
docker-compose ps
```

### Ver logs específicos:
```bash
# Logs de Apache
docker-compose exec app tail -f /var/log/apache2/access.log

# Logs de MQTT
docker-compose exec app tail -f /var/log/supervisor/mqtt_out.log

# Logs de Laravel
docker-compose exec app tail -f storage/logs/laravel.log
```

## 🔧 Configuración Avanzada

### Variables de entorno importantes en `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=password

MQTT_HOST=mosquitto
MQTT_PORT=1883

REDIS_HOST=redis
CACHE_STORE=redis
```

### Personalizar configuración de Mosquitto:
Edita `docker/mosquitto/mosquitto.conf` para cambiar la configuración del broker MQTT.

### Personalizar configuración de Apache:
Edita `docker/apache/000-default.conf` para cambiar la configuración del servidor web.

## 🐛 Resolución de Problemas

### Error de permisos:
```bash
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Base de datos no se conecta:
1. Verificar que el contenedor de MySQL esté ejecutándose: `docker-compose ps`
2. Revisar logs: `docker-compose logs db`
3. Esperar unos segundos para que MySQL termine de inicializar

### MQTT no funciona:
1. Verificar que Mosquitto esté ejecutándose: `docker-compose ps`
2. Probar conexión: `docker-compose exec mosquitto mosquitto_pub -h localhost -t test -m "hello"`
3. Revisar logs: `docker-compose logs mosquitto`

### La aplicación no inicia:
1. Verificar logs: `docker-compose logs app`
2. Verificar que el archivo `.env` exista y tenga la configuración correcta
3. Regenerar caches: `docker-compose exec app php artisan config:clear`

## 🔄 Actualización

Para actualizar la aplicación:

1. Detener servicios: `docker-compose down`
2. Hacer pull de cambios: `git pull`
3. Reconstruir: `docker-compose build`
4. Levantar: `docker-compose up -d`
5. Ejecutar migraciones si es necesario: `docker-compose exec app php artisan migrate`