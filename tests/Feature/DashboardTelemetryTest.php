<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTelemetryTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_dashboard_exposes_the_intalnet_mqtt_topics_to_the_browser(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('intalnet/sala01/telemetria', false);
        $response->assertSee('intalnet/sala01/estado', false);
        $response->assertSee('intalnet/sala01/alerta', false);
    }

    public function test_dashboard_renders_the_containers_for_the_real_esp32_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('id="metric-temp-val"', false);
        $response->assertSee('id="metric-hum-val"', false);
        $response->assertSee('id="metric-gas-val"', false);
        $response->assertSee('id="metric-estado-val"', false);
        $response->assertSee('id="metric-buzzer-val"', false);
        $response->assertSee('id="metric-uptime-val"', false);
        $response->assertSee('id="telemetry-chart"', false);
        $response->assertSee('id="telemetry-table-body"', false);
    }
}
