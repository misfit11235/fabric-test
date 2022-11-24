<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTitleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_title_without_params()
    {
        $response = $this->get('/api/title');
        $response->assertStatus(302);
    }

    public function test_api_title_with_params()
    {
        $response = $this->get('/api/title?title=Matrix');
        $response->assertStatus(200);
    }

    public function test_api_title_with_bad_params()
    {
        $response = $this->get('/api/title?title=Matrix&page=20');
        $response->assertStatus(500);
    }

}
