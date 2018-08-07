<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class UserController extends Controller
{
    public function level(Request $request){
        $client = new Client;
        $response = $client->request('GET', env('IZZI_URL', 'http://izzisystem.local/api/').'user/group',
            'query' => [],
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ]
        )

        $response = json_decode($response->getBody()->getContents());
    }
}
