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
        $response->assertSee('id="metric-heat-val"', false);
        $response->assertSee('id="metric-hum-val"', false);
        $response->assertSee('id="metric-gas-val"', false);
        $response->assertSee('id="metric-estado-val"', false);
        $response->assertSee('id="metric-buzzer-val"', false);
        $response->assertSee('id="metric-uptime-val"', false);
        $response->assertSee('id="telemetry-chart"', false);
        $response->assertSee('id="telemetry-table-body"', false);
    }

    public function test_telemetry_can_be_stored_and_retrieved_via_api(): void
    {
        $payload = [
            'temperatura' => 24.5,
            'humedad' => 52.0,
            'gas' => 450,
            'estado' => 'NORMAL',
            'silenciado' => false,
            'dispositivo' => 'ESP32_SALA01',
            'sala_id' => 'sala01',
            'zona' => 'Sala 01 - Intalnet (Montería)',
        ];

        $storeResponse = $this->postJson('/api/telemetry', $payload);
        $storeResponse->assertStatus(200);
        $storeResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('telemetry_logs', [
            'dispositivo' => 'ESP32_SALA01',
            'sala_id' => 'sala01',
            'temperatura' => 24.5,
        ]);

        $historyResponse = $this->getJson('/api/telemetry/history?range=24h&sala=sala01');
        $historyResponse->assertStatus(200);
        $historyResponse->assertJsonStructure([
            'range',
            'sala',
            'total_samples',
            'kpis' => ['avg_temp', 'min_temp', 'max_temp', 'avg_hum', 'avg_heat'],
            'chart' => ['categories', 'temp', 'hum'],
            'samples',
        ]);
        $this->assertEquals(1, $historyResponse->json('total_samples'));
    }

    public function test_telemetry_creates_incident_log_when_critical_threshold_exceeded(): void
    {
        $payload = [
            'temperatura' => 39.2,
            'humedad' => 65.0,
            'gas' => 1950,
            'estado' => 'CRITICO',
            'dispositivo' => 'ESP32_SALA01',
            'sala_id' => 'sala01',
        ];

        $this->postJson('/api/telemetry', $payload)->assertStatus(200);

        $this->assertDatabaseHas('incident_logs', [
            'sala_id' => 'sala01',
            'severidad' => 'critical',
            'estado' => 'Pendiente',
        ]);

        $alertsResponse = $this->getJson('/api/telemetry/alerts?sala=sala01');
        $alertsResponse->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($alertsResponse->json('data')));

        $alertId = $alertsResponse->json('data.0.id');
        $ackResponse = $this->postJson("/api/telemetry/alerts/{$alertId}/ack");
        $ackResponse->assertStatus(200);

        $this->assertDatabaseHas('incident_logs', [
            'id' => $alertId,
            'estado' => 'Reconocido',
        ]);
    }
}
