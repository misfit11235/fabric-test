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
    public function test_omdb_titles_request_has_results()
    {
        $titles = ['Matrix', 'Matrix Reloaded', 'Matrix Revolution'];
        foreach($titles as $title) {
            $response = OMDB::search($title);
            $this->assertTrue(count($response['Search']) > 0);
        }
    }

    public function test_omdb_titles_request_no_results()
    {
        $response = OMDB::search(352532532);
        $this->assertTrue($response['Response'] === 'False');
    }

}
