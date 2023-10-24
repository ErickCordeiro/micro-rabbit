<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';

    /**
     * Get All Categories
     *
     * @return void
     */
    public function test_get_all_categories()
    {
        Category::factory()->count(6)->create();
        $response = $this->getJson($this->endpoint);
        $response->assertJsonCount(6, 'data');
        $response->assertStatus(200);
    }

    /**
     * Error get single category
     *
     * @return void
     */
    public function test_error_single_category()
    {
        $category = 'fake-uri';
        $response = $this->getJson("{$this->endpoint}/{$category}");
        $response->assertNotFound();
    }

    /**
     * Get single category
     *
     * @return void
     */
    public function test_get_single_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson("{$this->endpoint}/{$category->url}");
        $response->assertOk();
    }

    /**
     * Validation Store category
     *
     * @return void
     */
    public function test_validation_store_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => '',
            'description' => ''
        ]);

        $response->assertStatus(422);
    }

    /**
     * Store category
     *
     * @return void
     */
    public function test_store_category()
    {
        $response = $this->postJson($this->endpoint, [
            'title' => 'Categoria 01',
            'description' => 'description of category'
        ]);

        $response->assertCreated();
    }

    /**
     * Update category
     *
     * @return void
     */
    public function test_update_category()
    {
        $category = Category::factory()->create();

        $data = [
            'title' => 'Category update',
            'description' => 'Description of category upload'
        ];

        //Validate category not found (404) - invalid uri
        $response = $this->putJson("{$this->endpoint}/fake-category", $data);
        $response->assertNotFound();

        //Validate category with data empty and uri incorrect
        $response = $this->putJson("{$this->endpoint}/fake-category", []);
        $response->assertStatus(422);

        //Validate category with data empty and uri correct
        $response = $this->putJson("{$this->endpoint}/{$category->url}", []);
        $response->assertStatus(422);

        //Validate category correct
        $response = $this->putJson("{$this->endpoint}/{$category->url}", $data);
        $response->assertOk();
    }

    /**
     * Delete category
     *
     * @return void
     */
    public function test_delete_category()
    {
        $category = Category::factory()->create();

        //Validate category not found (404) - invalid uri
        $response = $this->deleteJson("{$this->endpoint}/fake-category");
        $response->assertNotFound();

        //Validate category correct
        $response = $this->deleteJson("{$this->endpoint}/{$category->url}");
        $response->assertNoContent();
    }
}
