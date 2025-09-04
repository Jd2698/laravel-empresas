<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Empresa;
use Illuminate\Validation\ValidationException;

class EmpresaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verificar que los atributos fillable sean correctos
     *
     * @return void
     */
    public function test_empresa_fillable()
    {
        $empresa = new Empresa();

        $fillable = $empresa->getFillable();

        $this->assertContains('nombre', $fillable);
        $this->assertContains('nit', $fillable);
        $this->assertContains('direccion', $fillable);
        $this->assertContains('telefono', $fillable);
        $this->assertContains('estado', $fillable);
    }

    /**
     * Prueba el endpoint GET /empresas
     *
     * @return void
     */
    public function test_get_all_companies()
    {
        Empresa::factory(2)->create();
        $empresas = Empresa::get()->toArray();

        $response = $this->get('/api/empresas');
        
        $response->assertStatus(200);
        $response->assertJson($empresas);
    }

    /**
     * Prueba el endpoint GET /empresas/nit
     *
     * @return void
     */
    public function test_get_by_nit()
    {
        Empresa::factory(1)->create();
        $empresa = Empresa::first();

        $response = $this->get('/api/empresas/nit/' . $empresa->nit);
        
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $empresa->id,
            'nit' => $empresa->nit,
            'nombre' => $empresa->nombre,
            'direccion' => $empresa->direccion,
            'telefono' => $empresa->telefono,
        ]);
    }

    /**
     * Prueba el endpoint POST /empresas
     *
     * @return void
     */
    public function test_create()
    {
        $data = [
            'nombre' => 'Empresa test',
            'nit' => '123456789',
            'direccion' => 'Calle 2b 123',
            'telefono' => '1231231231',
        ];

        $response = $this->postJson('/api/empresas', $data);

        $response->assertStatus(201);
        $response->assertJson($data);
        $this->assertDatabaseHas('empresas', $data);
    }

    /**
     * Prueba que el endpoint POST /empresas falle cuando los datos no son enviados
     *
     * @return void
     */
    public function test_create_empresa_invalid_data()
    {
        $data = [];

        $response = $this->postJson('/api/empresas', $data);
        $response->assertStatus(400);

        $response->assertJsonValidationErrors([
            'nombre',
            'nit',
            'direccion',
            'telefono',
        ]);
    }

    /**
     * Prueba que el endpoint POST /empresas falle si el nit ya existe
     *
     * @return void
     */
    public function test_create_empresa_already_exist_nit()
    {        
        Empresa::factory(1)->create();
        $empresa = Empresa::first();

        $data = [
            'nombre' => 'Empresa test',
            'nit' => $empresa->nit,
            'direccion' => 'Calle 2b 123',
            'telefono' => '1231231231',
        ];

        $response = $this->postJson('/api/empresas', $data);
        $response->assertStatus(400);

        $response->assertJsonValidationErrors('nit');

        $response->assertJson([
            'errors' => [
                'nit' => [
                    'The nit has already been taken.',
                ],
            ],
        ]);
    }

    /**
     * Prueba el endpoint PUT /empresas
     *
     * @return void
     */
    public function test_update()
    {
        Empresa::factory(1)->create();
        $empresa = Empresa::first();

        $data = [
            'nombre' => 'nombre actualizado'
        ];

        $response = $this->putJson('/api/empresas/' .$empresa->id, $data);

        $response->assertStatus(200);
        $response->assertJson([
            'nombre' => $data['nombre']
        ]);

        $this->assertDatabaseHas('empresas', $data);

    }

    /**
     * Prueba el endpoint PUT /empresas no actualiza el nit
     *
     * @return void
     */
    public function test_update_wihout_nit()
    {
        Empresa::factory(1)->create([
            'nit' => '123456788'
        ]);
        $empresa = Empresa::first();

        $data = [
            'nit' => '33322211122'
        ];

        $response = $this->putJson('/api/empresas/' .$empresa->id, $data);

        $response->assertStatus(200);
        $response->assertJson([
            'nit' => '123456788'
        ]);

        $this->assertDatabaseHas('empresas', [
            'nit' => '123456788',
        ]);
    }

    public function test_destroy_inactive_companies()
    {
        $empresaActiva = Empresa::factory()->create([
            'estado' => 'Activo'
        ]);

        $empresaInactiva = Empresa::factory()->create([
            'estado' => 'Inactivo'
        ]);

        $response = $this->deleteJson('/api/empresas/inactivas');

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Empresas inactivas borradas: 1'
        ]);

        // Verificar que la empresa inactiva tenga un deleted_at
        $this->assertNotNull($empresaInactiva->fresh()->deleted_at);

        // Verificar que la empresa activa no tenga un deleted_at
        $this->assertNull($empresaActiva->fresh()->deleted_at);
    }

}
