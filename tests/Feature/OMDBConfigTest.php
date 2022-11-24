<?php

namespace Tests\Feature;

use Tests\TestCase;

class OMDBConfigTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_has_omdb_api_key()
    {
        $this->assertTrue(strlen(env('OMDB_API_KEY')) > 0);
    }

    public function test_has_omdb_base_url()
    {
        $this->assertTrue(strlen(env('OMDB_BASE_URL')) > 0);
    }
}
