<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Reading;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    // Umbrales para sensor ultrasónico (distancia en cm)
    // Contenedor: 62.5cm de altura, rango de sensor: 0-60cm
    const ULTRASONIC_WARNING_THRESHOLD = 10;  // ~84% lleno (62.5 - 10 = 52.5cm de basura)
    const ULTRASONIC_CRITICAL_THRESHOLD = 6;  // ~90% lleno (62.5 - 6 = 56.5cm de basura)

    // Umbrales para sensor MQ4 (valor analógico 0-4095)
    const MQ4_WARNING_THRESHOLD = 2500;   // ~61% del rango
    const MQ4_CRITICAL_THRESHOLD = 3200;  // ~78% del rango

    /**
     * Verificar si una lectura excede umbrales y crear notificación
     */
    public function checkThresholds(Reading $reading): ?Notification
    {
        $sensor = $reading->sensor;

        if (!$sensor || !$sensor->sensorType) {
            return null;
        }

        // Verificar según tipo de sensor
        $sensorTypeName = $sensor->sensorType->name;

        if (stripos($sensorTypeName, 'Ultrasónico') !== false || stripos($sensorTypeName, 'Ultrasonico') !== false) {
            return $this->checkUltrasonicThreshold($reading);
        } elseif (stripos($sensorTypeName, 'Gas') !== false || stripos($sensorTypeName, 'Metano') !== false) {
            return $this->checkMQ4Threshold($reading);
        }

        return null;
    }

    /**
     * Verificar umbral de sensor ultrasónico
     */
    private function checkUltrasonicThreshold(Reading $reading): ?Notification
    {
        $distance = $reading->value;

        if ($distance <= self::ULTRASONIC_CRITICAL_THRESHOLD) {
            return $this->createNotification(
                $reading->container_id,
                'critical',
                "Contenedor CRÍTICO: Nivel de llenado al {$this->calculateFillPercentage($distance)}% (distancia: {$distance}cm)"
            );
        } elseif ($distance <= self::ULTRASONIC_WARNING_THRESHOLD) {
            return $this->createNotification(
                $reading->container_id,
                'warning',
                "Contenedor lleno: Nivel de llenado al {$this->calculateFillPercentage($distance)}% (distancia: {$distance}cm)"
            );
        }

        return null;
    }

    /**
     * Verificar umbral de sensor MQ4
     */
    private function checkMQ4Threshold(Reading $reading): ?Notification
    {
        $value = $reading->value;

        if ($value >= self::MQ4_CRITICAL_THRESHOLD) {
            return $this->createNotification(
                $reading->container_id,
                'critical',
                "PELIGRO: Nivel de gas metano CRÍTICO ({$value} ppm)"
            );
        } elseif ($value >= self::MQ4_WARNING_THRESHOLD) {
            return $this->createNotification(
                $reading->container_id,
                'warning',
                "Advertencia: Nivel de gas metano elevado ({$value} ppm)"
            );
        }

        return null;
    }

    /**
     * Crear notificación
     */
    private function createNotification(int $containerId, string $type, string $description): Notification
    {
        // Verificar si ya existe una notificación similar reciente (últimas 24 horas)
        $existingNotification = Notification::where('container_id', $containerId)
            ->where('type', $type)
            ->where('notification_date', '>=', now()->subDay())
            ->first();

        if ($existingNotification) {
            Log::info("Notificación similar ya existe (ID: {$existingNotification->id}), no se crea duplicada");
            return $existingNotification;
        }

        $notification = Notification::create([
            'container_id' => $containerId,
            'type' => $type,
            'description' => $description,
            'notification_date' => now(),
        ]);

        Log::info("✅ Notificación creada: ID={$notification->id}, Type={$type}, Container={$containerId}");
        Log::info("   Descripción: {$description}");

        return $notification;
    }

    /**
     * Calcular porcentaje de llenado basado en distancia
     * Contenedor real: 62.5cm de altura
     */
    private function calculateFillPercentage(float $distance): int
    {
        $containerHeight = 62.5; // cm (medida real del contenedor)
        $fillPercentage = (($containerHeight - $distance) / $containerHeight) * 100;
        return round($fillPercentage);
    }
}
