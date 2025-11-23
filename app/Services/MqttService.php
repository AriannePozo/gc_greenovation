<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\Reading;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MqttService
{
    private MqttClient $client;
    private ConnectionSettings $connectionSettings;
    
    public function __construct(
        private string $host,
        private int $port,
        private ?string $username = null,
        private ?string $password = null
    ) {
        $this->client = new MqttClient($this->host, $this->port);
        $this->connectionSettings = new ConnectionSettings();
        
        if ($this->username) {
            $this->connectionSettings = $this->connectionSettings
                ->setUsername($this->username)
                ->setPassword($this->password);
        }
    }

    public function connect(): bool
    {
        try {
            $this->client->connect($this->connectionSettings, true);
            Log::info('Connected to MQTT broker successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to connect to MQTT broker: ' . $e->getMessage());
            throw $e;
        }
    }

    public function subscribe(string $topic, callable $callback): void
    {
        $this->client->subscribe($topic, $callback, 0);
        Log::info("Subscribed to topic: {$topic}");
    }

    public function loop(bool $blocking = true): void
    {
        $this->client->loop($blocking);
    }

    public function disconnect(): void
    {
        if ($this->client->isConnected()) {
            $this->client->disconnect();
            Log::info('Disconnected from MQTT broker');
        }
    }

    public function isConnected(): bool
    {
        return $this->client->isConnected();
    }

    /**
     * Process incoming MQTT message and save to database
     */
    public function processMessage(string $topic, string $message): bool
    {
        Log::info("Processing message from topic '{$topic}': {$message}");

        try {
            // Parse JSON message
            $data = json_decode($message, true);
            
            if (!$data) {
                Log::error('Invalid JSON message received');
                return false;
            }

            // Validate required fields
            if (!isset($data['sensor_id']) || !isset($data['value'])) {
                Log::error('Missing required fields: sensor_id or value');
                return false;
            }

            // Find sensor
            $sensor = Sensor::find($data['sensor_id']);
            if (!$sensor) {
                Log::warning("Sensor with ID {$data['sensor_id']} not found, skipping...");
                return false;
            }

            // Get container_id from sensor or from message
            $containerId = $data['container_id'] ?? $sensor->container_id ?? null;

            // Create new reading
            $reading = Reading::create([
                'sensor_id' => $data['sensor_id'],
                'container_id' => $containerId,
                'value' => $data['value'],
                'reading_date' => isset($data['reading_date']) 
                    ? Carbon::parse($data['reading_date']) 
                    : now(),
            ]);

            Log::info("Saved reading: ID={$reading->id}, Sensor={$reading->sensor_id}, Value={$reading->value}");
            return true;

        } catch (\Exception $e) {
            Log::error('Error processing message: ' . $e->getMessage());
            return false;
        }
    }
}