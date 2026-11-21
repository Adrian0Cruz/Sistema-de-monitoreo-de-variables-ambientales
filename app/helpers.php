<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('getAetherSensSettings')) {
    /**
     * Obtiene la configuración de umbrales y broker de AetherSens.
     *
     * @return array<string, mixed>
     */
    function getAetherSensSettings(): array
    {
        // Los valores por defecto replican las constantes del firmware del ESP32
        // (TEMP_UMBRAL_ADVERTENCIA, TEMP_UMBRAL_CRITICO, MQ2_UMBRAL_HUMO y los
        // tópicos MQTT declarados en el sketch de Intalnet).
        $default = [
            'tempMin' => 18.0,
            'tempMax' => 28.0,
            'tempCritica' => 35.0,
            'humMin' => 40.0,
            'humMax' => 60.0,
            'humCritica' => 75.0,
            'gasAdcUmbral' => 3500,
            'brokerHost' => 'broker.hivemq.com',
            'brokerPort' => 1883,
            'brokerWsPort' => 8000,
            'brokerWsPortTls' => 8884,
            'topic' => 'intalnet/sala01/telemetria',
            'topicEstado' => 'intalnet/sala01/estado',
            'topicAlerta' => 'intalnet/sala01/alerta',
            'topicConfig' => 'intalnet/sala01/config',
            'intervalo' => 5,
            'watchdog' => 10,
            'zona' => 'Sala 01 - Intalnet (Montería)',
            'audioAlert' => true,
            'auditLog' => true,
        ];

        if (Storage::exists('settings.json')) {
            try {
                $stored = json_decode((string) Storage::get('settings.json'), true);
                if (is_array($stored)) {
                    return array_merge($default, $stored);
                }
            } catch (Throwable) {
                // Mantener configuración por defecto en caso de excepción
            }
        }

        return $default;
    }
}
