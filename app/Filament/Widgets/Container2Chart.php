<?php

namespace App\Filament\Widgets;

use App\Models\Container;
use App\Models\Reading;
use App\Models\Sensor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class Container2Chart extends ChartWidget
{
    protected static ?string $heading = 'Contenedor Secundario';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Obtener lecturas de los últimos 7 días para el contenedor 2
        $readings = Reading::whereHas('sensor', function($query) {
            $query->where('container_id', 2);
        })
        ->where('reading_date', '>=', now()->subDays(7))
        ->orderBy('reading_date')
        ->get();

        // Separar por tipo de sensor
        $mq4Readings = $readings->filter(function($reading) {
            return $reading->sensor->sensor_type_id == 2; // MQ4
        });

        $ultrasonicReadings = $readings->filter(function($reading) {
            return $reading->sensor->sensor_type_id == 1; // HC-SR04
        });

        // Crear etiquetas de tiempo (cada 6 horas)
        $labels = [];
        for ($i = 168; $i >= 0; $i -= 6) {
            $labels[] = now()->subHours($i)->format('M d H:i');
        }

        // Preparar datos MQ4
        $mq4Data = [];
        foreach ($labels as $label) {
            $time = Carbon::createFromFormat('M d H:i', $label);
            $endTime = $time->copy()->addHours(6);
            
            $reading = $mq4Readings->where('reading_date', '>=', $time)
                                  ->where('reading_date', '<=', $endTime)
                                  ->last();
            
            $mq4Data[] = $reading ? $reading->value : null;
        }

        // Preparar datos Ultrasonic
        $ultrasonicData = [];
        foreach ($labels as $label) {
            $time = Carbon::createFromFormat('M d H:i', $label);
            $endTime = $time->copy()->addHours(6);
            
            $reading = $ultrasonicReadings->where('reading_date', '>=', $time)
                                         ->where('reading_date', '<=', $endTime)
                                         ->last();
            
            $ultrasonicData[] = $reading ? $reading->value : null;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Gas Metano (MQ4)',
                    'data' => $mq4Data,
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'backgroundColor' => 'rgba(255, 159, 64, 0.1)',
                    'tension' => 0.4,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Nivel Llenado (HC-SR04)',
                    'data' => $ultrasonicData,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Gas Metano (MQ4) - Valor 0-4095',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Nivel Llenado (HC-SR04) - cm',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}