<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp;
use Auth;
use DB;
use DataTables;

use App\Models\Song;
use App\Models\Artist;

class SongController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update', 'destroy', 'toggle']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $session_owner = Auth::user();
        $keywords = config('constants.keywords');


        $params = array(
            'page_title' => 'Music Library',
            'keywords' => implode(",", $keywords),
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('songs.index'), 'title'=>'Music'],
            ),
        );

        return view('songs.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $attempts = 0;

        do {

            try {
                $session_owner = Auth::user();
                $request_params = [
                    'headers' => [
                        'Authorization' =>  ['Bearer ' . $session_owner->spotify_token],
                        "Content-Type" => "application/x-www-form-urlencoded",
                    ]
                ];

                $song_client = new GuzzleHttp\Client();
                $song_res = $song_client->get('https://api.spotify.com/v1/tracks/'.$request->spotify_song_id , $request_params);

                $result = json_decode($song_res->getBody(), true);
//return $result;

                //apstrādā mākslinieku
                $artist_name = $result['artists'][0]['name'];
                $artist_spotify_id = $result['artists'][0]['id'];

                $artist = Artist::firstOrNew([
                    'spotify_id' => $artist_spotify_id
                ]);
                $artist->name = $artist_name;
                $artist->save();

                //apstrādā dziesmu
                $feature_client = new GuzzleHttp\Client();
                $feature_res = $song_client->get('https://api.spotify.com/v1/audio-features/'.$request->spotify_song_id , $request_params);

                $feature_result = json_decode($feature_res->getBody(), true);

                $song_name = $result['name'];
                $song_spotify_id = $result['id'];
                $duration_ms = $result['duration_ms'];
                $tempo = $feature_result['tempo'];

                $song = Song::firstOrNew([
                    'spotify_id' => $song_spotify_id,
                    'dance_id' => $request->dance_id,
                ]);


                $song->name = $song_name;
                $song->duration_ms = $duration_ms;
                $song->tempo = $tempo;
                $song->artist_id = $artist->id;
                $song->save();

                return redirect()->back()->withMessage('Dziesma ir sekmīgi pievienota.');

            } catch (\Exception $e) {
                echo $e->getMessage();
                $attempts++;

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

            break;

        } while($attempts < 5);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function show(Song $song)
    {
        $keywords = config('constants.keywords');
        $keywords[] = $song->name;
        $keywords[] = $song->artist->name;
        $keywords[] = 'song';
        $keywords[] = 'music';

        $dances = DB::table('songs')
            ->leftJoin('dances', 'dances.id', '=', 'songs.dance_id')
            ->where('songs.spotify_id', $song->spotify_id)
            ->pluck('dances.title', 'dances.id');

        $params = array(
            'page_title' => 'Song: ' .$song->artist->name . ' - ' . $song->name,
            'keywords' => implode(",", $keywords),
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('song.index'), 'title'=>'Music'],
                ['url'=>route('song.show', $song->id), 'title'=>$song->artist->name . ' - ' . $song->name],
            ),
            'song' => $song,
            'parent_id' => $song->id,
            'parent_class' => 'Song',
            'comments' => $song->comments,
            'dances' => $dances,
        );

        return view('songs.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function edit(Song $song)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Song $song)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Song  $song
     * @return \Illuminate\Http\Response
     */
    public function destroy(Song $song)
    {
        //
    }

    public function json(Request $request)
    {
        return Song::where('spotify_id', $request->spotify_song_id)->first();
    }

    public function datatable()
    {
        $songs = DB::table('songs')
            ->leftJoin('dances', 'dances.id', '=', 'songs.dance_id')
            ->leftJoin('artists', 'artists.id', '=', 'songs.artist_id')
            ->groupBy('songs.name', 'songs.id', 'artists.name', 'artists.id')
            ->select([
                'songs.name',
                'songs.id',
                'artists.name AS artist',
                'artists.id AS artist_id',
                DB::raw('GROUP_CONCAT(dances.title SEPARATOR ", ") AS dances'),
            ])
            ->groupBy('songs.spotify_id');

        return DataTables::of($songs)
            ->editColumn('name', function($item){
                return '<a href="'.route('songs.show', $item->id).'">'.$item->name.'</a>';
            })
            ->editColumn('artist', function($item){
                return '<a href="'.route('artists.show', $item->artist_id).'">'.$item->artist.'</a>';
            })
            ->rawColumns(['name', 'artist'])
            ->make(true);
    }

}
