# Configuración MQTT para SmartBin

Este proyecto ahora incluye soporte para recibir datos a través de MQTT en lugar de la API REST.

## Configuración

1. **Variables de entorno**: Agrega las siguientes variables a tu archivo `.env`:

```bash
MQTT_HOST=localhost
MQTT_PORT=1883
MQTT_USERNAME=
MQTT_PASSWORD=
```

2. **Dependencias**: El paquete `php-mqtt/client` ya está instalado.

## Uso

### Ejecutar el suscriptor MQTT

Para iniciar el suscriptor que escucha el tópico `smartbin/measurements`:

```bash
php artisan mqtt:subscribe
```

Para usar configuración personalizada:

```bash
php artisan mqtt:subscribe --host=your-broker.com --port=1883 --username=user --password=pass
```

### Formato de mensajes

Los mensajes MQTT deben enviarse al tópico `smartbin/measurements` con el siguiente formato JSON:

```json
{
    "sensor_id": 1,
    "value": 75.5,
    "container_id": 1,
    "reading_date": "2024-12-17 10:30:00"
}
```

**Campos requeridos:**
- `sensor_id`: ID del sensor (debe existir en la base de datos)
- `value`: Valor de la lectura

**Campos opcionales:**
- `container_id`: ID del contenedor (si no se proporciona, se obtiene del sensor)
- `reading_date`: Fecha de la lectura (si no se proporciona, se usa la fecha actual)

### Ejecutar en background

Para ejecutar el suscriptor en background, puedes usar:

**En Linux/Mac:**
```bash
nohup php artisan mqtt:subscribe > mqtt.log 2>&1 &
```

**En Windows:**
```bash
start /B php artisan mqtt:subscribe > mqtt.log 2>&1
```

O usar un supervisor de procesos como `supervisor` en Linux.

## Logs

Los eventos MQTT se registran en el log de Laravel (`storage/logs/laravel.log`). Puedes monitorear la actividad con:

```bash
tail -f storage/logs/laravel.log
```

## Migración desde API

Si estabas usando el endpoint API `/api/store`, ahora puedes enviar los mismos datos a través de MQTT al tópico `smartbin/measurements`. El formato de datos es compatible.

## Ejemplo de cliente MQTT (Python)

```python
import paho.mqtt.client as mqtt
import json
import datetime

def on_connect(client, userdata, flags, rc):
    print(f"Connected with result code {rc}")

client = mqtt.Client()
client.on_connect = on_connect

client.connect("localhost", 1883, 60)

# Enviar datos
data = {
    "sensor_id": 1,
    "value": 75.5,
    "reading_date": datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
}

client.publish("smartbin/measurements", json.dumps(data))
client.disconnect()
```