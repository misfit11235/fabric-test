<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class OMDB
{
	public function search($title, $page = 1)
	{
		if(!env('OMDB_API_KEY')) {
			throw new \Exception('OMDB API key not set.');
		}

		if(!env('OMDB_BASE_URL')) {
			throw new \Exception('OMDB API base url not set.');
		}

		$response = Http::get(env('OMDB_BASE_URL'), [
			's' => $title,
			'apikey' => env('OMDB_API_KEY'),
			'page' => $page
		]);
	
		if($response->failed()) {
			throw new \Exception('OMDB API request failed.');
		}

		return $response->json();
	}
}