<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;  

class OAuthController extends Controller
{
    public function redirect(Request $request) 
    {
        $request->session()->put('state', $state = Str::random(40));

        $queries = http_build_query([
            'client_id' => '5',
            'redirect_url' => 'http://localhost:81/oauth/callback',
            'response_type' => 'code',
            'scope' => 'get-name get-email',
            'state' => $state
        ]);
        return redirect('http://localhost:8080/oauth/authorize?' . $queries);
    }
    
    public function callback(Request $request)
    {
        ///dd($request->all());
        $response = Http::post('http://localhost:8080/oauth/token', [ 
            'grant_type' => 'authorization_code',
            'client_id' => '5',
            'client_secret' => 'NZB5aSQafSvJrzPhptlJH0tVzaryHAd2ylrHsxUw',
            'redirect_url' => 'http://localhost:81/oauth/callback',
            'code' => $request->code
        ]);

        $result = $response->json();

        $request->user()->token()->delete();

        $request->user()->token()->create([
            'access_token' => $result['access_token']
        ]);

        return redirect('/dashboard');

    }
    
    
}
