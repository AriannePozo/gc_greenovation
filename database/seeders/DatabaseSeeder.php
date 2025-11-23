<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ========================================
        // TIPOS DE SENSOR
        // ========================================
        DB::table('sensor_types')->insert([
            [
                'id' => 1,
                'name' => 'Ultrasonico',
                'description' => 'Sensor de distancia ultrasónico HC-SR04',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'MQ4',
                'description' => 'Sensor de gas metano MQ-4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================================
        // ESTADOS DE SENSOR
        // ========================================
        DB::table('sensor_statuses')->insert([
            [
                'id' => 1,
                'name' => 'Activo',
                'description' => 'Sensor funcionando correctamente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Inactivo',
                'description' => 'Sensor desactivado o en mantenimiento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Error',
                'description' => 'Sensor con fallas o fuera de servicio',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================================
        // CONTENEDORES
        // ========================================
        DB::table('containers')->insert([
            [
                'id' => 1,
                'name' => 'Contenedor Principal',
                'location' => 'Zona de Residuos - Planta Baja',
                'latitude' => -17.783329,
                'longitude' => -63.182126,
                'installation_date' => now()->subDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Contenedor Secundario',
                'location' => 'Zona de Residuos - Planta Alta',
                'latitude' => -17.783500,
                'longitude' => -63.182200,
                'installation_date' => now()->subDays(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Contenedor de Emergencia',
                'location' => 'Zona de Emergencia - Exterior',
                'latitude' => -17.783100,
                'longitude' => -63.182050,
                'installation_date' => now()->subDays(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================================
        // SENSORES
        // ========================================
        DB::table('sensors')->insert([
            // Sensores del Contenedor 1
            [
                'id' => 1,
                'sensor_type_id' => 1, // Ultrasonico
                'container_id' => 1,
                'sensor_status_id' => 1, // Activo
                'model' => 'HC-SR04',
                'installation_date' => now()->subDays(30),
                'last_reading' => now()->subMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'sensor_type_id' => 2, // MQ4
                'container_id' => 1,
                'sensor_status_id' => 1, // Activo
                'model' => 'MQ-4',
                'installation_date' => now()->subDays(30),
                'last_reading' => now()->subMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Sensores del Contenedor 2
            [
                'id' => 3,
                'sensor_type_id' => 1, // Ultrasonico
                'container_id' => 2,
                'sensor_status_id' => 1, // Activo
                'model' => 'HC-SR04',
                'installation_date' => now()->subDays(15),
                'last_reading' => now()->subMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'sensor_type_id' => 2, // MQ4
                'container_id' => 2,
                'sensor_status_id' => 1, // Activo
                'model' => 'MQ-4',
                'installation_date' => now()->subDays(15),
                'last_reading' => now()->subMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Sensores del Contenedor 3
            [
                'id' => 5,
                'sensor_type_id' => 1, // Ultrasonico
                'container_id' => 3,
                'sensor_status_id' => 2, // Inactivo
                'model' => 'HC-SR04',
                'installation_date' => now()->subDays(7),
                'last_reading' => now()->subHours(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'sensor_type_id' => 2, // MQ4
                'container_id' => 3,
                'sensor_status_id' => 1, // Activo
                'model' => 'MQ-4',
                'installation_date' => now()->subDays(7),
                'last_reading' => now()->subMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========================================
        // LECTURAS (READINGS) - DESHABILITADO
        // ========================================
        // Comentado para evitar generación automática de datos
        // Los datos de readings se generarán solo cuando se ejecute el seeder manualmente
        // o cuando el Arduino envíe datos reales
        
        /*
        // Generar lecturas para los últimos 7 días
        $startDate = now()->subDays(7);
        $endDate = now();
        
        // Sensores activos: 1, 2, 3, 4, 6 (el 5 está inactivo)
        $activeSensors = [1, 2, 3, 4, 6];
        
        foreach ($activeSensors as $sensorId) {
            $sensorTypeId = $sensorId <= 2 ? 1 : ($sensorId <= 4 ? 1 : 2); // 1=Ultrasonico, 2=MQ4
            $containerId = $sensorId <= 2 ? 1 : ($sensorId <= 4 ? 2 : 3);
            
            // Generar lecturas cada 15 minutos para los últimos 7 días
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Generar entre 1-3 lecturas por hora (simulando el comportamiento del Arduino)
                $readingsPerHour = rand(1, 3);
                
                for ($i = 0; $i < $readingsPerHour; $i++) {
                    $readingTime = $currentDate->copy()->addMinutes(rand(0, 59));
                    
                    // Generar valor según el tipo de sensor
                    if ($sensorTypeId == 1) { // Ultrasonico (0-60 cm)
                        $value = rand(5, 55); // Valores realistas para un contenedor
                    } else { // MQ4 (0-4095)
                        $value = rand(100, 3500); // Valores realistas para detección de gas
                    }
                    
                    DB::table('readings')->insert([
                        'sensor_id' => $sensorId,
                        'container_id' => $containerId,
                        'value' => $value,
                        'reading_date' => $readingTime,
                        'created_at' => $readingTime,
                        'updated_at' => $readingTime,
                    ]);
                }
                
                $currentDate->addHour();
            }
        }
        
        // Generar algunas lecturas recientes (últimas 2 horas) para mostrar datos actuales
        $recentSensors = [1, 2, 3, 4, 6];
        
        foreach ($recentSensors as $sensorId) {
            $sensorTypeId = $sensorId <= 2 ? 1 : ($sensorId <= 4 ? 1 : 2);
            $containerId = $sensorId <= 2 ? 1 : ($sensorId <= 4 ? 2 : 3);
            
            // Generar 5-10 lecturas recientes
            for ($i = 0; $i < rand(5, 10); $i++) {
                $readingTime = now()->subMinutes(rand(0, 120));
                
                if ($sensorTypeId == 1) { // Ultrasonico
                    $value = rand(8, 45);
                } else { // MQ4
                    $value = rand(200, 2800);
                }
                
                DB::table('readings')->insert([
                    'sensor_id' => $sensorId,
                    'container_id' => $containerId,
                    'value' => $value,
                    'reading_date' => $readingTime,
                    'created_at' => $readingTime,
                    'updated_at' => $readingTime,
                ]);
            }
        }
        */

        // ========================================
        // TIPOS DE USUARIO
        // ========================================
        DB::table('user_types')->insert([
            ['id' => 1, 'name' => 'Administrador', 'description' => 'Usuario con acceso a todo el sistema'],
            ['id' => 2, 'name' => 'Empleado', 'description' => 'Usuario con acceso limitado'],
        ]);

        // ========================================
        // ESTADOS DE USUARIO
        // ========================================
        DB::table('user_statuses')->insert([
            ['id' => 1, 'name' => 'Activo', 'description' => 'Usuario con cuenta activa'],
            ['id' => 2, 'name' => 'Inactivo', 'description' => 'Usuario con cuenta deshabilitada'],
        ]);

        // ========================================
        // USUARIO ADMINISTRADOR
        // ========================================
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('1234'),
            'user_type_id' => 1,
            'user_status_id' => 1,
            'ci' => '00000000',
        ]);
    }
}
