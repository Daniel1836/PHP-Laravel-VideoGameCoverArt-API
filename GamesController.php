<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GamesController extends Controller {
    
    public $popularGames = [];

    public function loadPopularGames()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;
 
        
        $popularGamesUnformatted = Cache::remember('popular-games', 7, function () use ($before, $after) {. 
               $client = new \GuzzleHttp\Client(['base_uri' =>  'https://api.igdb.com/v4/games' ]);
               $response = $client->request('POST', 'multiquery', [
                'headers' => [
                   'Client-ID'=> 't3qfl3qldbe7ft7sp90gqw2mxk0m20',
                   'Authorization' => 'Bearer 5xknzmaxkw3ph1f2ivixzfab8ms18q',
                
                ],
                'body' => '
                     query games "Playstation"
                    {
                      cover.url;
                              
                      limit 25;
                    };
        
                         '
                             ]);
             $body = $response->getBody();
             $game = json_decode($body, true);

             return $game;
            
        });
          $popularGames = $this->formatForView($popularGamesUnformatted);
  
          return view('games', compact('popularGames'));
        
    }

    private function formatForView($games)
    
    {
        
       return collect($games)->map(function ($game) {
            return collect($game)->merge([
               'coverImageUrl' => Str::replaceFirst('thumb','cover_big', $game['cover']['url']),
            ]);
        })->toArray();
      
    }

}

?>
