<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{

    protected $endpoint = '/companies';

    /**
     * Get All Company.
     * @return void
     */
    public function test_get_all_company(): void
    {
        Company::factory()->count(6)->create();
        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    /**
     * Error get single company
     *
     * @return void
     */
    public function test_error_single_company()
    {
        $company = 'fake-app_token';
        $response = $this->getJson("{$this->endpoint}/{$company}");
        $response->assertNotFound();
    }

    /**
     * Get single company
     *
     * @return void
     */
    public function test_get_single_company()
    {
        $company = Company::factory()->create();
        $response = $this->getJson("{$this->endpoint}/{$company->app_token}");
        $response->assertOk();
    }

    /**
     * Validation Store Company
     *
     * @return void
     */
    public function test_validation_store_company()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => '',
            'category_id' => '',
            'email' => '',
            'whatsapp' => ''
        ]);

        $response->assertStatus(422);
    }

    /**
     * Store Company
     *
     * @return void
     */
    public function test_store_company()
    {
        $category = Category::factory()->create();
        $response = $this->postJson($this->endpoint, [
            'name' => 'Company de teste',
            'category_id' => $category->id,
            'email' => 'contato@teste.com.br',
            'whatsapp' => '13996631713'
        ]);

        $response->assertCreated();
    }

    /**
     * Update company
     *
     * @return void
     */
    public function test_update_company()
    {
        $company = Company::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'name' => 'Company de teste',
            'category_id' => $category->id,
            'email' => 'contato@teste.com.br',
            'whatsapp' => '13996631713'
        ];

        //Validate company not found (404) - invalid uri
        $response = $this->putJson("{$this->endpoint}/fake-app_token", $data);
        $response->assertNotFound();

        //Validate company with data empty and uri incorrect
        $response = $this->putJson("{$this->endpoint}/fake-app_token", []);
        $response->assertStatus(422);

        //Validate company with data empty and uri correct
        $response = $this->putJson("{$this->endpoint}/{$company->app_token}", []);
        $response->assertStatus(422);

        //Validate company correct
        $response = $this->putJson("{$this->endpoint}/{$company->app_token}", $data);
        $response->assertOk();
    }

    /**
     * Delete company
     *
     * @return void
     */
    public function test_delete_company()
    {
        $company = Company::factory()->create();

        //Validate company not found (404) - invalid uri
        $response = $this->deleteJson("{$this->endpoint}/fake-app_token");
        $response->assertNotFound();

        //Validate company correct
        $response = $this->deleteJson("{$this->endpoint}/{$company->app_token}");
        $response->assertNoContent();
    }

}
