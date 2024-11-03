<?php

namespace App\Http\Controllers;

use App\Models\Combination;
use Illuminate\Http\Request;

use Auth;
use DB;
use DataTables;
use Form;

use App\Models\Dance;
use App\Models\Difficulty;

class CombinationController extends Controller
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
            'page_title' => 'Create combo',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dance.index'), 'title'=>'Dances'],
                ['url'=>route('dance.show', $dance_id), 'title'=>$dance->title],
                ['url'=>route('combination.create'), 'title'=>'Create combo'],
            ),
            'dance' => [$dance->id => $dance->title],
            'dance_id' => $dance_id,
            'difficulty_levels' => $difficulty_levels,
        );

        return view('combinations.create')->with($params);
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

        $combination = Combination::create([
            'title' => $request->input('title'),
            'dance_id' => $request->input('dance_id'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
            'author' => $request->input('author'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('combination.show', $combination->id)->withMessage('You created new Combo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Combination  $combination
     * @return \Illuminate\Http\Response
     */
    public function show(Combination $combination, Request $request)
    {
        $session_owner = Auth::user();
        $dance = $combination->dance;

        $keywords = config('constants.keywords');
        $keywords[] = $dance->title;
        $keywords[] = $combination->title;
        $keywords[] = $combination->author;
        $keywords[] = 'step combination';

        $video_id = $request->vid;
        $video = $combination->videos()->find($video_id);

        $params = array(
            'page_title' => $combination->title,
            'keywords' => implode(",", $keywords),
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.show', $combination->dance_id), 'title'=>$dance->title],
                ['url'=>route('combinations.show', $combination->id), 'title'=>$combination->title],
            ),
            'combination' => $combination,
            'video' => ($video)?$video:$combination->videos()->first(),
            'parent_id' => $combination->id,
            'parent_class' => 'Combination',
            'comments' => $combination->comments,
        );

        return view('combinations.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Combination  $combination
     * @return \Illuminate\Http\Response
     */
    public function edit(Combination $combination)
    {
        $dance = $combination->dance;
        $difficulty_levels = Difficulty::select(DB::raw('CONCAT (level, " ", title) AS title'), 'id')->pluck('title', 'id');

        $params = array(
            'page_title' => $combination->title,
            'sub_title' => $dance->title . ' solis',
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dance.index'), 'title'=>'Dances'],
                ['url'=>route('dance.show', $combination->dance_id), 'title'=>$dance->title],
                ['url'=>route('combination.show', $combination->id), 'title'=>$combination->title],
                ['url'=>route('combination.edit', $combination->id), 'title'=>'Edit'],
            ),
            'dance' => [$dance->id => $dance->title],
            'dance_id' => $combination->dance_id,
            'combination' => $combination,
            'difficulty_levels' => $difficulty_levels,
        );

        return view('combinations.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Combination  $combination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Combination $combination)
    {
        $this->validate(
            $request,
            [
                'title' => 'required',
                'difficulty' => 'required',
                'dance_id' => 'required',
            ]
        );

        $combination->update([
            'title' => $request->input('title'),
            'dance_id' => $request->input('dance_id'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
            'author' => $request->input('author'),
        ]);

        return redirect()->route('combination.show', $combination->id)->withMessage('You updated Combo!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Combination  $combination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Combination $combination)
    {
        //
    }


    public function datatable(Request $request)
    {
        $user_id = Auth::id();

        $combinations = DB::table('combinations')
            ->leftJoin('difficulties', 'combinations.difficulty', '=', 'difficulties.id')
            ->leftJoin('combination_user', 'combinations.id', '=', 'combination_user.combination_id')
            ->leftJoin('combination_user as relation', function($join) use ($user_id){
                $join->on('combinations.id', '=', 'relation.combination_id')
                    ->where('relation.user_id', '=', $user_id);
            })
            ->where('dance_id', $request->input('dance_id'))
            ->groupBy('combinations.id', 'combinations.title', 'difficulties.title', 'difficulties.level', 'relation.relation_type', 'combination_user.skill')
            ->select([
                'combinations.title',
                'combinations.id',
                DB::raw('CONCAT(level, " ", '. DB::getTablePrefix().'difficulties.title) AS difficulties'),
                'relation.relation_type',
                'combination_user.skill',
                DB::raw('COUNT(' . DB::getTablePrefix().'combination_user.id) as dancers')
            ])
            ->groupBy('combinations.id');


        return Datatables::of($combinations)

            ->editColumn('title', function($item){
                return '<a href="'.route('combinations.show', $item->id).'">'.$item->title.'</a>';
            })
            ->editColumn('repertuar', function($item){
                return Form::select('relation_type', [0 =>'Unused', 1 => 'Habbit', 2 => 'Track'], $item->relation_type, ['class' => 'form-control combination-repertuar', 'data-id' => $item->id]);
            })
            ->filterColumn('repertuar', function($query, $keyword) {
                $prefix = DB::getTablePrefix();
                $query->whereRaw('(CASE
                    WHEN (' . $prefix .'combination_user.relation_type = 0) THEN "Unused"
                    WHEN (' . $prefix .'combination_user.relation_type = 1) THEN "Habbit"
                    WHEN (' . $prefix .'combination_user.relation_type = 2) THEN "Track"
                    ELSE "Unused" END) like ?', ["%{$keyword}%"]);
            })
            ->rawColumns(['title', 'repertuar'])
            ->make(true);
    }

    public function select2(Request $request)
    {
        $dance_id = $request->input('dance_id');
        $q = $request->input('q');
        if($dance_id) {

            return Combination::where('dance_id', $dance_id)->where('title', 'like', '%' . $q . '%')->orderBy('title')->select('id', 'title AS text')->take(10)->get();
        } else {
            // code...
            return Combination::where('title', 'like', '%' . $q . '%')->orderBy('title')->select('id', 'title AS text')->take(10)->get();
        }


    }
}
