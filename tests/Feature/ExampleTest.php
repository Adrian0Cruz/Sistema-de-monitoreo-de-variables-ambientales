<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verificar que la página de bienvenida responda correctamente con la marca AetherSens.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('AetherSens');
    }

    /**
     * Verificar que la página de Ayuda y Documentación sea accesible.
     */
    public function test_docs_page_is_accessible(): void
    {
        $response = $this->get('/docs');

        $response->assertStatus(200);
        $response->assertSee('AetherSens');
    }
}
