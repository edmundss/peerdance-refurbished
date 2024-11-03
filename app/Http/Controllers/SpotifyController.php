<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use Auth;
use GuzzleHttp;
use Exception;
use App\Models\Song;

class SpotifyController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function initialAuthorization(Request $request)
    {

    	$request->session()->put('last_url', $request->uri);

    	return Socialite::driver('spotify')->scopes([
    		'user-read-private',
    		'user-read-email',
    		'user-modify-playback-state',
    		'user-read-playback-state',
    		'user-read-currently-playing',
    	])->redirect();
    }

    public function callback(Request $request)
    {
    	$session_owner = Auth::user();
    	$code = $request->code;

    	if($code){
            $client = new GuzzleHttp\Client();

            $token_request_params = [
                'headers' => [
                    'Authorization' =>  ['Basic ' . base64_encode(env('SPOTIFY_KEY') . ':' . env('SPOTIFY_SECRET'))],
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
                ]
            ];


            $res = $client->post('https://accounts.spotify.com/api/token', $token_request_params);
            $response_body = $res->getBody();

            $data = json_decode($response_body);

            $session_owner->update([
                'spotify_token' => $data->access_token,
                'spotify_refresh_token' => $data->refresh_token,
            ]);

    		//echo '<strong>Saņemtais: </strong>' . $code . '<br>';
    		//echo '<strong>Saglabātais: </strong>' . $session_owner->spotify_token;
    		return redirect()->to($request->session()->get('last_url'))->withMessage('Spotify sekmīgi autorizēts!');
    	} else {
    		return 'Kods nav saņemts';
    	}
    }

    public function songSearch(Request $request)
    {
    	$session_owner = Auth::user();

    	$request_params = [
			'headers' => [
				'Authorization' =>  ['Bearer ' . $session_owner->spotify_token],
				"Content-Type" => "application/x-www-form-urlencoded",
		    ]
    	];

    	//return $request_params;

    	try {
	    	//return 'yay';
	    	$client = new GuzzleHttp\Client();
			$res = $client->get('https://api.spotify.com/v1/search?q='.$request->q.'&type=track&limit=5', $request_params);

			$result = json_decode($res->getBody(), true);
			$tracks = [];
			//return $result;

			//return $result['tracks']['items'];
			foreach ($result['tracks']['items'] as $item) {
				$tracks[] = [
					'text' => $item['name'] . ' by ' . $item['artists'][0]['name'],
					'id' => $item['id'],
				];
			}
			return $tracks;

    	} catch (Exception $e) {
    		echo $e->getMessage();

	    	$token_request_params = [
	    		'headers' => [
					'Authorization' =>  ['Basic ' . base64_encode(env('SPOTIFY_KEY') . ':' . env('SPOTIFY_SECRET'))],
					"Content-Type" => "application/x-www-form-urlencoded",
	    		],
	    		'form_params' => [
					'grant_type' => 'refresh_token',
					'refresh_token' => $session_owner->spotify_refresh_token,
	    		]
			];

			$client = new GuzzleHttp\Client();
			$res = $client->post('https://accounts.spotify.com/api/token', $token_request_params);
			$response_body = $res->getBody();

			$data = json_decode($response_body);

	    	$session_owner->update([
	    		'spotify_token' => $data->access_token,
	    	]);
    	}


    }


    public function play(Request $request)
    {
    	$session_owner = Auth::user();

    	try {
	    	$client = new GuzzleHttp\Client();
			$res = $client->request("PUT", "https://api.spotify.com/v1/me/player/play?device_id=".$request->device_id, [
				"headers" => [
					"Authorization" =>  ["Bearer " . $session_owner->spotify_token],
					"Content-Type" => "application/json",
			    ],
	    		"json" => ["uris" => ["spotify:track:" . $request->spotify_song_id]]
			]);

    	return Song::where('spotify_id', $request->spotify_song_id)->first()->tempo;


    	} catch (Exception $e) {
    		echo $e->getMessage();

	    	$token_request_params = [
	    		'headers' => [
					'Authorization' =>  ['Basic ' . base64_encode(env('SPOTIFY_KEY') . ':' . env('SPOTIFY_SECRET'))],
					"Content-Type" => "application/x-www-form-urlencoded",
	    		],
	    		'form_params' => [
					"grant_type" => "refresh_token",
					"refresh_token" => $session_owner->spotify_refresh_token,
	    		]
			];

			$client = new GuzzleHttp\Client();
			$res = $client->post('https://accounts.spotify.com/api/token', $token_request_params);
			$response_body = $res->getBody();

			$data = json_decode($response_body);

	    	$session_owner->update([
	    		'spotify_token' => $data->access_token,
	    	]);
    	}
    }


    public function getPlaybackInfo(Request $request)
    {
    	$session_owner = Auth::user();

    	try {
	    	$client = new GuzzleHttp\Client();
			$res = $client->request("GET", "https://api.spotify.com/v1/me/player/currently-playing", [
				"headers" => [
					"Authorization" =>  ["Bearer " . $session_owner->spotify_token],
					"Content-Type" => "application/json",
			    ],
			]);

			return json_decode($res->getBody(), true);


    	} catch (Exception $e) {
    		echo $e->getMessage();

	    	$token_request_params = [
	    		'headers' => [
					'Authorization' =>  ['Basic ' . base64_encode(env('SPOTIFY_KEY') . ':' . env('SPOTIFY_SECRET'))],
					"Content-Type" => "application/x-www-form-urlencoded",
	    		],
	    		'form_params' => [
					"grant_type" => "refresh_token",
					"refresh_token" => $session_owner->spotify_refresh_token,
	    		]
			];

			$client = new GuzzleHttp\Client();
			$res = $client->post('https://accounts.spotify.com/api/token', $token_request_params);
			$response_body = $res->getBody();

			$data = json_decode($response_body);

	    	$session_owner->update([
	    		'spotify_token' => $data->access_token,
	    	]);
    	}
    }
}
