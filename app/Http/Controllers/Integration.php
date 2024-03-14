<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Integration extends Controller
{
    function game_list(Request $req, $vendorName)
    {
        $client = new Client();

        try {
            $url = "https://api.honorlink.org/api/game-list?vendor={$vendorName}";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);
            $result = $response->getBody();
            return $result;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function game_launch(Request $req, $vendorName, $gameId)
    {
        $client = new Client();
        $user = $this->get_user();
        $username = json_decode($user)->username;
        $nickname = json_decode($user)->nickname;
        try {
            $url = "https://api.honorlink.org/api/game-launch-link";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'username' => $username,
                    // 'nickname' => $nickname,
                    'game_id' => $gameId,
                    'vendor' => $vendorName,
                    // 'skin' => '6',
                ],
            ]);
            
            $result = $response->getBody();
            // dd($result);
            return $result;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            return $userDetails;
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
