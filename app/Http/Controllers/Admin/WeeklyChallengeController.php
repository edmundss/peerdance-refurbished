<?php

namespace App\Http\Controllers\Admin;

use App\Models\WeeklyChallenge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeeklyChallengeController extends Controller
{

     public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('role:admin');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = array(
            'page_title' => 'Weekly Challenges',
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>'#', 'title'=>'Admin'],
                ['url'=>route('admin.weeklyChallenge.index'), 'title'=>'Weekly Challenges'],
            ),
        );

        return view('admin.weekly_challenges.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params = array(
            'page_title' => 'Weekly Challenges',
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>'#', 'title'=>'Admin'],
                ['url'=>route('admin.weeklyChallenge.index'), 'title'=>'Weekly Challenges'],
                ['url'=>route('admin.weeklyChallenge.create'), 'title'=>'Create'],
            ),
        );

        return view('admin.weekly_challenges.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'parent_id' => 'nullable',
            'parent_class' => 'nullable',
            'status' => 'required',
            'end' => 'required'
        ]);

        $weeklyChallenge = WeeklyChallenge::create($input);

        return redirect()->route('admin.weeklyChallenge.show', $weeklyChallenge)->withMessage('Weekly challenge created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WeeklyChallenge  $weeklyChallenge
     * @return \Illuminate\Http\Response
     */
    public function show(WeeklyChallenge $weeklyChallenge)
    {
        $params = array(
            'page_title' => 'Weekly Challenge: ' . $weeklyChallenge->name,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>'#', 'title'=>'Admin'],
                ['url'=>route('admin.weeklyChallenge.index'), 'title'=>'Weekly Challenges'],
                ['url'=>route('admin.weeklyChallenge.show', $weeklyChallenge), 'title'=>$weeklyChallenge->name],
            ),
            'weekly_challenge' => $weeklyChallenge,
        );

        return view('admin.weekly_challenges.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WeeklyChallenge  $weeklyChallenge
     * @return \Illuminate\Http\Response
     */
    public function edit(WeeklyChallenge $weeklyChallenge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WeeklyChallenge  $weeklyChallenge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WeeklyChallenge $weeklyChallenge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WeeklyChallenge  $weeklyChallenge
     * @return \Illuminate\Http\Response
     */
    public function destroy(WeeklyChallenge $weeklyChallenge)
    {
        //
    }
}
