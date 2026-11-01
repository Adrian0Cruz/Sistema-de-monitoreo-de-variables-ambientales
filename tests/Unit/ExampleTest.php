<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verificar que la configuración inicial de AetherSens cumpla con la normativa térmica ASHRAE TC 9.9.
     */
    public function test_aethersens_default_settings_follow_ashrae_tc_99(): void
    {
        $settings = getAetherSensSettings();

        $this->assertIsArray($settings);
        $this->assertEquals(18.0, $settings['tempMin']);
        $this->assertEquals(24.0, $settings['tempMax']);
        $this->assertEquals(28.0, $settings['tempCritica']);
        $this->assertEquals(40.0, $settings['humMin']);
        $this->assertEquals(60.0, $settings['humMax']);
        $this->assertEquals(75.0, $settings['humCritica']);
        $this->assertEquals('broker.hivemq.com', $settings['brokerHost']);
        $this->assertEquals('pas_iot/datacenter/telemetria', $settings['topic']);
    }
}
