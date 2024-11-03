<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use DB;

class ArtistController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        $keywords = config('constants.keywords');
        $keywords[] = $artist->name;
        $keywords[] = 'artist';
        $keywords[] = 'music';

        $dances = DB::table('songs')
            ->leftJoin('dances', 'dances.id', '=', 'songs.dance_id')
            ->where('songs.artist_id', $artist->id)
            ->groupBy('dances.id', 'dances.title')
            ->pluck('dances.title', 'dances.id');

        $params = array(
            'page_title' => 'Artist: ' .$artist->name,
            'keywords' => implode(",", $keywords),
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('artists.index'), 'title'=>'Music'],
                ['url'=>route('artists.show', $artist->id), 'title'=>$artist->name],
            ),
            'artist' => $artist,
            'parent_id' => $artist->id,
            'parent_class' => 'Artist',
            'comments' => $artist->comments,
            'dances' => $dances,
        );

        return view('artists.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit(Artist $artist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        //
    }
}
