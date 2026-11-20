<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled(): void
    {
        if (Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_users_can_register(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_authenticated_operators_can_render_the_registration_screen(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Registrar Nuevo Operador');
    }

    public function test_authenticated_operators_can_register_new_operators_without_logging_out(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@intalnet.com',
        ]);

        $response = $this->actingAs($admin)->post('/register', [
            'name' => 'Operador Carlos',
            'email' => 'carlos@intalnet.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'access_level' => 'N2',
        ]);

        // Asegurar que el nuevo usuario fue creado en la base de datos
        $this->assertDatabaseHas('users', [
            'email' => 'carlos@intalnet.com',
            'access_level' => 'N2',
        ]);

        // Asegurar que el administrador actual permanece autenticado (no fue deslogueado)
        $this->assertAuthenticatedAs($admin);
    }

    public function test_unauthenticated_guests_cannot_post_to_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Intruso Externo',
            'email' => 'intruso@externo.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(404);
        $this->assertDatabaseMissing('users', [
            'email' => 'intruso@externo.com',
        ]);
    }
}
