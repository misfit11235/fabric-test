<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_landing_is_loaded()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
