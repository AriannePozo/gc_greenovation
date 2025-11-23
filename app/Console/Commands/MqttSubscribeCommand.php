<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MqttService;

class MqttSubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:subscribe 
                           {--host= : MQTT broker host}
                           {--port= : MQTT broker port}
                           {--username= : MQTT username}
                           {--password= : MQTT password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to MQTT topic for smartbin measurements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get configuration from options or environment variables
        $host = $this->option('host') ?? env('MQTT_HOST', 'localhost');
        $port = (int) ($this->option('port') ?? env('MQTT_PORT', 1883));
        $username = $this->option('username') ?? env('MQTT_USERNAME');
        $password = $this->option('password') ?? env('MQTT_PASSWORD');

        $this->info("Connecting to MQTT broker at {$host}:{$port}");

        // Create MQTT service
        $mqttService = new MqttService($host, $port, $username, $password);

        try {
            // Connect to broker
            $mqttService->connect();
            $this->info('Connected to MQTT broker successfully');

            // Subscribe to smartbin/measurements topic
            $mqttService->subscribe('smartbin/measurements', function (string $topic, string $message) use ($mqttService) {
                $this->processMessage($mqttService, $topic, $message);
            });

            $this->info('Subscribed to topic: smartbin/measurements');
            $this->info('Listening for messages... Press Ctrl+C to stop');

            // Start the event loop
            $mqttService->loop(true);

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        } finally {
            $mqttService->disconnect();
            $this->info('MQTT subscriber stopped');
        }

        return 0;
    }

    /**
     * Process incoming MQTT message using the service
     */
    private function processMessage(MqttService $mqttService, string $topic, string $message): void
    {
        $this->info("Received message on topic '{$topic}': {$message}");
        
        $success = $mqttService->processMessage($topic, $message);
        
        if ($success) {
            $this->info('Message processed successfully');
        } else {
            $this->warn('Failed to process message');
        }
    }
}
