<?php

namespace App\Http\Controllers;

use App\Models\Step;
use Illuminate\Http\Request;

use Auth;
use DB;
use DataTables;
use Form;

use App\Models\Dance;
use App\Models\Difficulty;

class StepController extends Controller
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
            'page_title' => 'Create new step',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dance.index'), 'title'=>'Dances'],
                ['url'=>route('dance.show', $dance_id), 'title'=>$dance->title],
                ['url'=>route('step.create'), 'title'=>'Add step'],
            ),
            'dance' => [$dance->id => $dance->title],
            'dance_id' => $dance_id,
            'difficulty_levels' => $difficulty_levels,
        );

        return view('steps.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'title' => 'required',
                'difficulty' => 'required',
                'dance_id' => 'required',
            ]
        );

        $step = Step::create([
            'title' => $request->input('title'),
            'dance_id' => $request->input('dance_id'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('step.show', $step)->withMessage('You created new step!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Step  $step
     * @return \Illuminate\Http\Response
     */
    public function show(Step $step, Request $request)
    {
        $session_owner = Auth::user();
        $dance = $step->dance;

        $keywords = config('constants.keywords');
        $keywords[] = $dance->title;
        $keywords[] = $step->title;
        $keywords[] = 'step';


        $video_id = $request->vid;
        $video = $step->videos()->find($video_id);

        $params = array(
            'page_title' => $dance->title . ': ' .$step->title,
            'keywords' => implode(",", $keywords),
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.show', $step->dance_id), 'title'=>$step->dance->title],
                ['url'=>route('steps.show', $step->id), 'title'=>$step->title],
            ),
            'step' => $step,
            'video' => ($video)?$video:$step->videos()->first(),
            'parent_id' => $step->id,
            'parent_class' => 'Step',
            'comments' => $step->comments,
        );

        return view('steps.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Step  $step
     * @return \Illuminate\Http\Response
     */
    public function edit(Step $step)
    {
        $session_owner = Auth::user();
        $dance = $step->dance;
        $difficulty_levels = Difficulty::select(DB::raw('CONCAT (level, " ", title) AS title'), 'id')->pluck('title', 'id');

        $params = array(
            'page_title' => $step->title,
            'sub_title' => $dance->title . ' solis',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('dances.index'), 'title'=>'Deju katalogs'],
                ['url'=>route('dances.show', $step->dance_id), 'title'=>$step->dance->title],
                ['url'=>route('steps.show', $step->id), 'title'=>$step->title],
                ['url'=>route('steps.edit', $step->id), 'title'=>'Rediģēšana'],
            ),
            'dance' => [$dance->id => $dance->title],
            'dance_id' => $step->dance_id,
            'step' => $step,
            'difficulty_levels' => $difficulty_levels,
        );

        return view('steps.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Step  $step
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Step $step)
    {
        $this->validate(
            $request,
            [
                'title' => 'required',
                'difficulty' => 'required',
                'dance_id' => 'required',
            ]
        );

        $step->update([
            'title' => $request->input('title'),
            'dance_id' => $request->input('dance_id'),
            'description' => $request->input('description'),
            'difficulty' => $request->input('difficulty'),
        ]);

        return redirect()->route('steps.show', $step)->withMessage('Step was updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Step  $step
     * @return \Illuminate\Http\Response
     */
    public function destroy(Step $step)
    {
        //
    }

    public function datatable(Request $request)
    {

        $user_id = Auth::id();

        $steps = DB::table('steps')
            ->leftJoin('alternate_names', function($join) {
                $join->on('steps.id', '=', 'alternate_names.parent_id')
                    ->where('alternate_names.parent_class', '=', 'Step');
            })
            ->leftJoin('difficulties', 'steps.difficulty', '=', 'difficulties.id')
            ->leftJoin('step_user', 'steps.id', '=', 'step_user.step_id')
            ->leftJoin('step_user as relation', function($join) use ($user_id){
                $join->on('steps.id', '=', 'relation.step_id')
                    ->where('relation.user_id', '=', $user_id);
            })
            ->where('dance_id', $request->input('dance_id'))
            ->groupBy('steps.id', 'steps.title', 'relation.relation_type', 'difficulties.title', 'difficulties.level')
            ->select([
                'steps.title',
                'steps.id',
                DB::raw('CONCAT(level, " ", difficulties.title) AS difficulties'),
                DB::raw("GROUP_CONCAT(alternate_names.name SEPARATOR ', ') as `alternate_names`"),
                'relation.relation_type',
                DB::raw('COUNT(step_user.id) as dancers')
            ])
            ->groupBy('steps.id');


        return Datatables::of($steps)

            ->editColumn('title', function($item){
                return '<a href="'.route('steps.show', $item->id).'" class="step-title">'.$item->title.'</a>';
            })
            ->editColumn('repertuar', function($item){
                return Form::select('relation_type', [0 =>'Unused', 1 => 'Habbit', 2 => 'Track'], $item->relation_type, ['class' => 'form-control step-repertuar select', 'data-id' => $item->id]);
            })
            ->filterColumn('repertuar', function($query, $keyword) {
                $prefix = DB::getTablePrefix();
                $query->whereRaw('(CASE
                    WHEN (' . $prefix .'step_user.relation_type = 0) THEN "Unused"
                    WHEN (' . $prefix .'step_user.relation_type = 1) THEN "Habbit"
                    WHEN (' . $prefix .'step_user.relation_type = 2) THEN "Track"
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

            return Step::where('dance_id', $dance_id)->where('title', 'like', '%' . $q . '%')->orderBy('title')->select('id', 'title AS text')->take(10)->get();
        } else {
            // code...
            return Step::where('title', 'like', '%' . $q . '%')->orderBy('title')->select('id', 'title AS text')->take(10)->get();
        }


    }

    public function user_toggle (Request $request) {
        $id = $request->input('step_id');
        $step = Step::findOrFail($id);
        $user_id = Auth::id();
        $relation_type = $request->input('relation_type');

        $step->users()->detach($user_id);

        if($relation_type > 0) {
            $step->users()->attach($user_id, ['relation_type' => $relation_type]);

            return 'This step is now in your repertiore.';
        }

        return 'The step s removed from your repertiore.';
    }
}
