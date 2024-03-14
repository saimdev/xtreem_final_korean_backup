<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class Loader extends Controller
{
    function load_games(Request $req)
    {

        if (Cache::has('game_list')) {
            $cachedData = Cache::get('game_list');
            $userDetails = $this->get_user();
            // dd("Have cahed");
            return view('gamelist', ['cachedData' => $cachedData, 'userDetails' => $userDetails]);
        }

        $client = new Client();
        $userDetails = $this->get_user();
        try {
            $url = "https://api.honorlink.org/api/game-list?vendor=";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);
            $games = $response->getBody();
            $gamesArray = json_decode($games, true);

            $vendorGamesCount = [];

            foreach ($gamesArray as $game) {
                if (isset($game['vendor'])) {
                    $vendor = $game['vendor'];
                    $vendorGamesCount[$vendor] = isset($vendorGamesCount[$vendor]) ? $vendorGamesCount[$vendor] + 1 : 1;
                }
            }

            $uniqueVendors = array_keys($vendorGamesCount);

            Cache::put('game_list', ['uniqueVendors' => $uniqueVendors, 'vendorGamesCount' => $vendorGamesCount], now()->addMinutes(60));

            return view('gamelist', ['uniqueVendors' => $uniqueVendors, 'vendorGamesCount' => $vendorGamesCount, 'userDetails' => $userDetails]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function dashboard()
    {
        $userDetails = $this->get_user();
        return view('dashboard', compact('userDetails'));
    }

    function get_user()
    {
        $client = new Client();
        $username = Auth::user()->email;
        try {
            
            $url = "https://api.honorlink.org/api/user";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'username' => $username,
                ],
            ]);
            $userDetails = $response->getBody();
            $balance = json_decode($userDetails)->balance;
            $user = User::where('email', Auth::user()->email)->first();
            $user->balance = $balance;
            $user->save();
            return $userDetails;
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
