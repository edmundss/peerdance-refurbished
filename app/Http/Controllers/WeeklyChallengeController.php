<?php

namespace App\Http\Controllers;

use App\Models\WeeklyChallenge;
use Illuminate\Http\Request;

class WeeklyChallengeController extends Controller
{
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
        //
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
                ['url'=>route('admin.weeklyChallenge.index'), 'title'=>'Weekly Challenges'],
                ['url'=>route('admin.weeklyChallenge.show', $weeklyChallenge), 'title'=>$weeklyChallenge->name],
            ),
            'weekly_challenge' => $weeklyChallenge,
        );

        return view('weekly_challenges.show')->with($params);
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
