# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer primero para aprovechar el cache de Docker
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-scripts

# Copiar el resto de la aplicación
COPY . .

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copiar configuración personalizada de Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copiar configuración de Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Crear directorios necesarios
RUN mkdir -p /var/log/supervisor

# Instalar dependencias de Node.js y compilar assets
RUN npm install && npm run build

# Ejecutar scripts de composer
RUN composer run-script post-autoload-dump

# Exponer puerto 80
EXPOSE 80

# Comando de inicio con Supervisor para ejecutar Apache y el suscriptor MQTT
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]