<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verificar que la configuración inicial replique las constantes del firmware del ESP32 de Intalnet.
     */
    public function test_aethersens_default_settings_follow_the_intalnet_firmware(): void
    {
        Storage::fake();
        $settings = getAetherSensSettings();

        $this->assertIsArray($settings);
        $this->assertEquals(18.0, $settings['tempMin']);
        $this->assertEquals(27.0, $settings['tempMax']);
        $this->assertEquals(31.0, $settings['tempCritica']);
        $this->assertEquals(40.0, $settings['humMin']);
        $this->assertEquals(60.0, $settings['humMax']);
        $this->assertEquals(75.0, $settings['humCritica']);
        $this->assertEquals(3300, $settings['gasAdcUmbral']);
        $this->assertEquals('broker.hivemq.com', $settings['brokerHost']);
        $this->assertEquals(1883, $settings['brokerPort']);
        $this->assertEquals('intalnet/sala01/telemetria', $settings['topic']);
        $this->assertEquals('intalnet/sala01/estado', $settings['topicEstado']);
        $this->assertEquals('intalnet/sala01/alerta', $settings['topicAlerta']);
    }
}
