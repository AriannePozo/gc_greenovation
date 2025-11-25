#!/bin/bash

echo "🔐 Configurando autenticación MQTT..."

# 1. Asegurar que el archivo passwd existe
touch docker/mosquitto/passwd

# 2. Reiniciar contenedor para aplicar cambios de config (allow_anonymous false)
echo "🔄 Reiniciando Mosquitto..."
docker-compose restart mosquitto

# 3. Esperar un momento
sleep 2

# 4. Crear usuario y contraseña
echo "👤 Creando usuario 'admin'..."
docker-compose exec mosquitto mosquitto_passwd -b /mosquitto/config/passwd admin smartbin_secure

# 5. Reiniciar una vez más para asegurar que tome el archivo de passwords
echo "🔄 Aplicando cambios..."
docker-compose restart mosquitto

echo "✅ ¡Autenticación configurada!"
echo "   Usuario: admin"
echo "   Password: smartbin_secure"
