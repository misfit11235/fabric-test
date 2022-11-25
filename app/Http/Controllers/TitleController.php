<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Facades\OMDB;
use App\Models\Title;
use App\Models\SearchedTerm;
use App\Http\Requests\OMDBTitlesRequest;

class TitleController extends Controller
{
    public function index(OMDBTitlesRequest $request) {
        
        $page = $request->page ?? 1;
        
        //try fetching titles from cache
        if(Cache::has($request['title'] . '-' . $page)) {
            $titles = Cache::get($request['title'] . '-' . $page);
            $pages = Cache::get($request['title'] . '-pages');

            return response()->json([
                'titles' => $titles,
                'pages' => $pages
            ]);
        }

        //check if title has already been searched and to what extent(furthest page saved)
        $searched = SearchedTerm::where('term', $request['title'])->first();
        $titles = Title::where('title', 'LIKE', '%' . $request['title'] . '%')->with('poster')->skip($page * 10)->take(10)->get()->toArray();
        //try fetching titles from DB if saved
        if($searched && count($titles) && $searched['last_cached_page'] >= $page) {
            $pages = $searched['total_pages'];
            
            Cache::add($request['title'] . '-' . $page, $titles);
            Cache::add($request['title'] . '-pages', $pages);

            return response()->json([
                'titles' => $titles,
                'pages' => $pages
            ]);
        };

        //fetch titles from OMDB API, store&cache them
        $response = OMDB::search($request['title'], $page);
        SearchedTerm::updateOrCreate([
            'term' => $request['title']
        ],
        [
            'total_pages' => ceil($response['totalResults'] / 10),
            'last_cached_page' => $page
        ]);

        foreach($response['Search'] as $title) {
            $item = [
                'title' => $title['Title'],
                'year' => $title['Year'],
                'imdb_id' => $title['imdbID'],
                'type' => $title['Type'],
            ];

            if(isset($title['Poster'])) {
                $item['poster'] = [
                    'url' => $title['Poster']
                ];
            }

            try {
                DB::beginTransaction();

                $titleModel = Title::updateOrCreate([
                    'imdb_id' => $item['imdb_id'],
                ],
                [
                    'title' => $item['title'],
                    'year' => $item['year'],
                    'type' => $item['type'],
                ]);
    
                if(isset($item['poster']['url']) && $item['poster']['url'] !== 'N/A' && !($titleModel->poster()->count())) {
                    $titleModel->poster()->create([
                        'url' => $item['poster']['url'],
                        'title_id' => $titleModel->id
                    ]);
                }
                
                array_push($titles, $item);

                DB::commit();

            }
            catch(\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        Cache::add($request['title'] . '-' . $page, $titles, 1000);
        Cache::add($request['title'] . '-pages', ceil($response['totalResults'] / 10), 1000);

        return response()->json([
            'titles' => $titles,
            'pages' => ceil($response['totalResults'] / 10)
        ]);
    }
}
