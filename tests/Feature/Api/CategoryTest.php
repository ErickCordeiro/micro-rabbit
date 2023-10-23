<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Get All Categories
     */
    public function test_get_all_category(): void
    {
        $response = $this->getJson('/categories');
        $response->assertStatus(200);
    }

    /**
     * Get Unique Category
     */
    public function test_get_category(): void
    {
        $response = $this->getJson('/categories');
        $response->assertStatus(200);
    }
}
