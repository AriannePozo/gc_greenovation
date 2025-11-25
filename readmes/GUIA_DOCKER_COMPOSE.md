# 🐳 Guía Completa: Docker Compose para Greenovation

## 📖 Tabla de Contenidos
- [Servicios Incluidos](#-servicios-incluidos)
- [Inicio Rápido](#-inicio-rápido)
- [URLs de Acceso](#-urls-de-acceso)
- [Configuración MQTT](#-configuración-mqtt)
- [Base de Datos](#-base-de-datos)
- [Comandos Útiles](#-comandos-útiles)
- [Monitoreo y Logs](#-monitoreo-y-logs)
- [Resolución de Problemas](#-resolución-de-problemas)
- [Desarrollo Avanzado](#-desarrollo-avanzado)

---

## 🏗️ Servicios Incluidos

Tu configuración Docker incluye 5 servicios principales:

| Servicio | Imagen | Puerto | Descripción |
|----------|--------|--------|-------------|
| **Laravel App** | `custom (Dockerfile)` | `8000` | Aplicación principal con MQTT subscriber |
| **MySQL** | `mysql:8.0` | `3306` | Base de datos principal |
| **Mosquitto** | `eclipse-mosquitto:2.0` | `1883, 9001` | Broker MQTT |
| **PHPMyAdmin** | `phpmyadmin/phpmyadmin` | `8080` | Interfaz web para MySQL |
| **MQTT Explorer** | `smeagolworms4/mqtt-explorer` | `4000` | Visualizador MQTT |

---

## 🚀 Inicio Rápido

### Opción 1: Script Automático (Recomendado)
```bash
# Hacer ejecutable el script
chmod +x docker-start.sh

# Ejecutar todo automáticamente
./docker-start.sh
```

### Opción 2: Manual
```bash
# 1. Copiar configuración de entorno
cp .env.docker .env

# 2. Crear directorios necesarios
mkdir -p storage/{app,framework/{cache,sessions,views},logs}
mkdir -p bootstrap/cache

# 3. Configurar permisos
chmod -R 775 storage bootstrap/cache

# 4. Construir y levantar servicios
docker-compose build
docker-compose up -d

# 5. Configurar Laravel
docker-compose exec app php artisan key:generate --force
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
docker-compose exec app php artisan storage:link

# 6. Optimizar
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

---

## 🌐 URLs de Acceso

| Servicio | URL | Credenciales |
|----------|-----|--------------|
| **🌐 Aplicación Laravel** | http://localhost:8000 | - |
| **🗄️ PHPMyAdmin** | http://localhost:8080 | `root` / `1234` |
| **🔍 MQTT Explorer** | http://localhost:4000 | - |
| **📡 MQTT Broker** | `localhost:1883` | Anónimo |
| **📡 MQTT WebSocket** | `localhost:9001` | Anónimo |

---

## 📡 Configuración MQTT

### Configuración del Broker
- **Host**: `localhost` (externo) / `mosquitto` (interno)
- **Puerto**: `1883` (MQTT) / `9001` (WebSocket)
- **Autenticación**: Anónima (sin usuario/contraseña)
- **Tópico principal**: `smartbin/measurements`

### Formato de Mensajes
```json
{
  "sensor_id": 1,
  "value": 75.5,
  "container_id": 1,
  "reading_date": "2024-12-17 10:30:00"
}
```

**Campos obligatorios:**
- `sensor_id`: ID del sensor (debe existir en BD)
- `value`: Valor numérico de la lectura

**Campos opcionales:**
- `container_id`: Se obtiene del sensor si no se especifica
- `reading_date`: Se usa fecha actual si no se especifica

### Ejemplo con mosquitto_pub
```bash
# Enviar mensaje de prueba
mosquitto_pub -h localhost -p 1883 -t smartbin/measurements -m '{"sensor_id":1,"value":85.2}'

# Suscribirse para ver mensajes
mosquitto_sub -h localhost -p 1883 -t smartbin/measurements
```

### Python Cliente MQTT
```python
import paho.mqtt.client as mqtt
import json
from datetime import datetime

def on_connect(client, userdata, flags, rc):
    print(f"Conectado con código: {rc}")

client = mqtt.Client()
client.on_connect = on_connect
client.connect("localhost", 1883, 60)

# Enviar datos
data = {
    "sensor_id": 1,
    "value": 75.5,
    "reading_date": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
}

client.publish("smartbin/measurements", json.dumps(data))
client.disconnect()
```

---

## 🗄️ Base de Datos

### Configuración MySQL
```env
DB_HOST=db (interno) / localhost (externo)
DB_PORT=3306
DB_DATABASE=gc_greenovation
DB_USERNAME=root
DB_PASSWORD=1234
```

### Conexión Externa
```bash
# Desde tu máquina local
mysql -h localhost -P 3306 -u root -p1234 gc_greenovation

# O usando un cliente gráfico como MySQL Workbench
Host: localhost
Port: 3306
Username: root
Password: 1234
Database: gc_greenovation
```

### PHPMyAdmin
- **URL**: http://localhost:8080
- **Usuario**: `root`
- **Contraseña**: `1234`

---

## 🛠️ Comandos Útiles

### Gestión de Contenedores
```bash
# Ver estado de todos los servicios
docker-compose ps

# Levantar servicios
docker-compose up -d

# Detener servicios
docker-compose down

# Detener y eliminar volúmenes (CUIDADO: borra datos)
docker-compose down -v

# Reconstruir contenedores
docker-compose build --no-cache

# Reiniciar un servicio específico
docker-compose restart app
```

### Comandos Laravel
```bash
# Acceder al contenedor
docker-compose exec app bash

# Artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan tinker

# Limpiar caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# MQTT subscriber (ya se ejecuta automáticamente)
docker-compose exec app php artisan mqtt:subscribe
```

### Comandos de Base de Datos
```bash
# Hacer backup de la BD
docker-compose exec db mysqldump -u root -p1234 gc_greenovation > backup.sql

# Restaurar backup
docker-compose exec -T db mysql -u root -p1234 gc_greenovation < backup.sql

# Acceso directo a MySQL
docker-compose exec db mysql -u root -p1234 gc_greenovation
```

---

## 📊 Monitoreo y Logs

### Ver Logs en Tiempo Real
```bash
# Todos los servicios
docker-compose logs -f

# Servicio específico
docker-compose logs -f app
docker-compose logs -f mosquitto
docker-compose logs -f db

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# MQTT subscriber logs
docker-compose exec app tail -f /var/log/supervisor/mqtt_out.log

# Apache logs
docker-compose exec app tail -f /var/log/apache2/access.log
docker-compose exec app tail -f /var/log/apache2/error.log
```

### Monitoreo de MQTT
1. **MQTT Explorer** (Recomendado): http://localhost:4000
2. **Línea de comandos**:
   ```bash
   # Suscribirse a todos los tópicos
   mosquitto_sub -h localhost -v -t '#'
   
   # Solo smartbin/measurements
   mosquitto_sub -h localhost -v -t 'smartbin/measurements'
   ```

### Monitoreo del Sistema
```bash
# Uso de recursos por contenedor
docker stats

# Espacio en disco de volúmenes
docker system df

# Información detallada de un contenedor
docker-compose exec app df -h
docker-compose exec app free -h
```

---

## 🐛 Resolución de Problemas

### La aplicación no inicia
```bash
# 1. Verificar estado de contenedores
docker-compose ps

# 2. Ver logs de errores
docker-compose logs app

# 3. Verificar configuración
docker-compose exec app php artisan config:show

# 4. Regenerar caches
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

### Base de datos no conecta
```bash
# 1. Verificar que MySQL esté corriendo
docker-compose ps db

# 2. Ver logs de MySQL
docker-compose logs db

# 3. Probar conexión manualmente
docker-compose exec app php artisan tinker
# En tinker: DB::connection()->getPdo();

# 4. Recrear contenedor de BD
docker-compose down
docker-compose up -d db
sleep 10
docker-compose up -d
```

### MQTT no funciona
```bash
# 1. Verificar Mosquitto
docker-compose ps mosquitto
docker-compose logs mosquitto

# 2. Probar conexión local
docker-compose exec mosquitto mosquitto_pub -h localhost -t test -m "hello"
docker-compose exec mosquitto mosquitto_sub -h localhost -t test

# 3. Verificar configuración
docker-compose exec app cat docker/mosquitto/mosquitto.conf

# 4. Reiniciar servicio MQTT
docker-compose restart mosquitto
```

### Permisos de archivos
```bash
# En el host
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# En el contenedor
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Limpiar todo y empezar de nuevo
```bash
# CUIDADO: Esto elimina TODOS los datos
docker-compose down -v
docker system prune -a
rm -rf storage/logs/* bootstrap/cache/*
./docker-start.sh
```

---

## 🚀 Desarrollo Avanzado

### Personalizar Configuración

#### Cambiar Base de Datos
Editar `docker-compose.yml`:
```yaml
environment:
  MYSQL_DATABASE: mi_nueva_bd
  MYSQL_ROOT_PASSWORD: mi_password
```

#### Usar Broker MQTT Externo
Editar `.env`:
```env
MQTT_HOST=mqtt.theflyingpizza.live
MQTT_PORT=1883
MQTT_USERNAME=Ari
MQTT_PASSWORD=.AriAdmin1234.
```

#### Agregar Redis (Opcional)
En `docker-compose.yml`:
```yaml
redis:
  image: redis:7.0-alpine
  container_name: redis
  ports:
    - "6379:6379"
  networks:
    - laravel
```

### Variables de Entorno Importantes
```env
# Aplicación
APP_ENV=local          # local/production
APP_DEBUG=true         # true/false
APP_URL=http://localhost:8000

# Base de datos
DB_HOST=db
DB_DATABASE=gc_greenovation
DB_USERNAME=root
DB_PASSWORD=1234

# MQTT
MQTT_HOST=mosquitto    # o broker externo
MQTT_PORT=1883
MQTT_USERNAME=         # vacío para anónimo
MQTT_PASSWORD=

# Cache y colas
CACHE_STORE=database   # database/redis
QUEUE_CONNECTION=database
```

### Comandos de Producción
```bash
# Optimizar para producción
docker-compose exec app composer install --optimize-autoloader --no-dev
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Configurar entorno de producción
# Cambiar en .env:
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### Backup y Restauración
```bash
# Backup completo
docker-compose exec db mysqldump -u root -p1234 gc_greenovation > backup_$(date +%Y%m%d).sql
tar -czf backup_$(date +%Y%m%d).tar.gz backup_$(date +%Y%m%d).sql storage/

# Restauración
docker-compose exec -T db mysql -u root -p1234 gc_greenovation < backup_20241217.sql
```

---

## 📝 Notas Adicionales

- **Persistencia**: Los datos de MySQL se guardan en volúmenes Docker y persisten entre reinicios
- **Logs**: Todos los logs se almacenan en volúmenes y son accesibles
- **Red interna**: Los servicios se comunican usando nombres de contenedor (`db`, `mosquitto`, etc.)
- **Puertos**: Solo los puertos especificados están expuestos al host
