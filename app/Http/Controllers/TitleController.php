<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Facades\OMDB;
use App\Models\Title;
use App\Http\Requests\OMDBTitlesRequest;

class TitleController extends Controller
{
    public function index(OMDBTitlesRequest $request) {
        
        $page = $request->page ?? 1;

        $titles = collect();

        if(Cache::has($request['title'] . '-' . $page)) {
            $titles = Cache::get($request['title'] . '-' . $page);
            $pages = Cache::get($request['title'] . '-pages');

            return response()->json([
                'titles' => $titles,
                'pages' => $pages
            ]);
        }

        //TODO store searched terms and cached page number/total pages per term
        // if($titles = Title::where('title', 'LIKE', '%' . $request['title'] . '%')->with('poster')->get()->toArray()) {
        //     $pages = ceil(count($titles) / 10);
        //     $titles = array_chunk($titles, 10);
            
        //     return response()->json([
        //         'titles' => $titles[$page - 1],
        //         'pages' => $pages
        //     ]);
        // };

        $response = OMDB::search($request['title'], $page);
        
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
    
                $titles->push($item);

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
