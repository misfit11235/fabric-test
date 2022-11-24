<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Facades\OMDB;

class OMDBRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_omdb_titles_request()
    {   
        $titles = ['Matrix', 'Matrix Reloaded', 'Matrix Revolution'];
        foreach($titles as $title) {
            $response = OMDB::search($title);
            $this->assertArrayHasKey('Search', $response);
        }   
    }
}
