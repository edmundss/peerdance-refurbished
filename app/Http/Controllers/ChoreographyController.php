<?php

namespace App\Http\Controllers;

use App\Models\Choreography;
use Illuminate\Http\Request;

use Auth;
use DB;
use DataTables;

use App\Models\Dance;
use App\Models\Step;
use App\Models\Difficulty;

class ChoreographyController extends Controller
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
    public function create(Request $request)
    {
        $this->validate($request, ['dance_id' => 'required']);

        $session_owner = Auth::user();
        $dance_id = $request->input('dance_id');
        $dance = Dance::findOrFail($dance_id);
        $difficulty_levels = Difficulty::select(DB::raw('CONCAT (level, " ", title) AS title'), 'id')->pluck('title', 'id');

        $params = array(
            'page_title' => 'Create Routine',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.show', $dance_id), 'title'=>$dance->title],
                ['url'=>route('choreographies.create'), 'title'=>'Add Routine'],
            ),
            'dance' => [$dance->id => $dance->title],
            'dance_id' => $dance_id,
            'difficulty_levels' => $difficulty_levels,
        );

        return view('choreographies.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'title' => 'required',
                'difficulty' => 'required',
                'dance_id' => 'required',
            ]
        );

        $choreography = Choreography::create([
            'title' => $request->input('title'),
            'dance_id' => $request->input('dance_id'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
            'author' => $request->input('author'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('choreographies.show', $choreography->id)->withMessage('You added new routine to the library!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Choreography  $choreography
     * @return \Illuminate\Http\Response
     */
    public function show(Choreography $choreography, Request $request)
    {
        $session_owner = Auth::user();
        $dance = $choreography->dance;
        $video_id = $request->input('vid');

        $video = $choreography->videos()->find($video_id);

        $keywords = config('constants.keywords');
        $keywords[] = $dance->title;
        $keywords[] = $choreography->title;
        $keywords[] = $choreography->author;
        $keywords[] = 'routine';

        $params = array(
            'page_title' => $choreography->title,
            'keywords' => implode(",", $keywords),
            'sub_title' => $dance->title . ' solis',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.show', $choreography->dance_id), 'title'=>$choreography->dance->title],
                ['url'=>route('choreographies.show', $choreography->id), 'title'=>$choreography->title],
            ),
            'choreography' => $choreography,
            'parent_id' => $choreography->id,
            'parent_class' => 'Choreography',
            'comments' => $choreography->comments,
            'video' => ($video)?$video:$choreography->videos()->first(),
            'video_types' => config('constants.video_types'),
            'step' => [],
        );

        return view('choreographies.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Choreography  $choreography
     * @return \Illuminate\Http\Response
     */
    public function edit(Choreography $choreography)
    {
        $dance = $choreography->dance;
        $difficulty_levels = Difficulty::select(DB::raw('CONCAT (level, " ", title) AS title'), 'id')->pluck('title', 'id');

        $params = array(
            'page_title' => 'Edit choreography: ' . $choreography->title,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.show', $choreography->dance_id), 'title'=>$choreography->dance->title],
                ['url'=>route('choreographies.show', $choreography), 'title'=>$choreography->title],
                ['url'=>route('choreographies.edit', $choreography), 'title'=>'edit'],
            ),
            'dance' => [$dance->id => $dance->title],
            'dance_id' => $choreography->dance_id,
            'choreography' => $choreography,
            'difficulty_levels' => $difficulty_levels,
        );

        return view('choreographies.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Choreography  $choreography
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Choreography $choreography)
    {
        $this->validate(
            $request,
            [
                'title' => 'required',
                'difficulty' => 'required',
                'dance_id' => 'required',
            ]
        );

        $choreography->update([
            'title' => $request->input('title'),
            'dance_id' => $request->input('dance_id'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
            'author' => $request->input('author'),
        ]);

        return redirect()->route('choreographies.show', $choreography)->withMessage('You updated the routine!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Choreography  $choreography
     * @return \Illuminate\Http\Response
     */
    public function destroy(Choreography $choreography)
    {
        //
    }

    public function datatable(Request $request)
    {

        $choreographies = DB::table('choreographies')
            ->leftJoin('difficulties', 'choreographies.difficulty', '=', 'difficulties.id')
            ->where('dance_id', $request->input('dance_id'))
            ->select([
                'choreographies.title',
                'choreographies.id',
                DB::raw('CONCAT(level, " ", '. DB::getTablePrefix().'difficulties.title) AS difficulties')
            ]);


        return Datatables::of($choreographies)

            ->editColumn('title', function($item){
                return '<a href="'.route('choreographies.show', $item->id).'">'.$item->title.'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }

    public function select2(Request $request)
    {
        $dance_id = $request->input('dance_id');
        $q = $request->input('q');
        if($dance_id) {

            return Choreography::where('dance_id', $dance_id)->where('title', 'like', '%' . $q . '%')->orderBy('title')->select('id', 'title AS text')->take(10)->get();
        } else {
            // code...
            return Choreography::where('title', 'like', '%' . $q . '%')->orderBy('title')->select('id', 'title AS text')->take(10)->get();
        }


    }
}
