-- Script de inicialización para MySQL
-- Este script se ejecuta automáticamente cuando se crea el contenedor

USE laravel;

-- Crear usuario adicional si es necesario
-- CREATE USER 'app_user'@'%' IDENTIFIED BY 'app_password';
-- GRANT ALL PRIVILEGES ON laravel.* TO 'app_user'@'%';
-- FLUSH PRIVILEGES;

-- Configuraciones adicionales
SET GLOBAL sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';