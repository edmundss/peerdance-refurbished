<?php

namespace App\Http\Controllers;

use App\Models\DanceFamily;
use Illuminate\Http\Request;

use Auth;

class DanceFamilyController extends Controller
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

        $params = [
            'page_title' => 'Dance Families',
            'session_owner' => $session_owner,
            'dance_families' => DanceFamily::all(),
        ];

        return view('dance_families.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $session_owner = Auth::user();

        $params = [
            'page_title' => 'Create new dance faily',
            'session_owner' => $session_owner,

        ];

        return view('dance_families.create')->with($params);
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
            'name' => 'required',
        ]);

        $category = DanceFamily::create([
            'name' => $request->name,
        ]);

        return redirect()->route('dance_family.index')->withMessage('New dance family is saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DanceFamily  $danceFamily
     * @return \Illuminate\Http\Response
     */
    public function show(DanceFamily $danceFamily)
    {
        $session_owner = Auth::user();

        $params = [
            'page_title' => $danceFamily->name,
            'session_owner' => $session_owner,
            'dance_family'=> $danceFamily,
        ];

        return view('dance_families.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DanceFamily  $danceFamily
     * @return \Illuminate\Http\Response
     */
    public function edit(DanceFamily $danceFamily)
    {
        $session_owner = Auth::user();

        $params = [
            'page_title' => $danceFamily->name,
            'session_owner' => $session_owner,
            'dance_family'=> $danceFamily,
        ];

        return view('dance_families.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DanceFamily  $danceFamily
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DanceFamily $danceFamily)
    {
        $this->validate($request,
        [
            'name' => 'required',
        ]);

        $danceFamily->update([
            'name' => $request->name,
        ]);

        return redirect()->route('dance_family.index')->withMessage('Dance family is updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DanceFamily  $danceFamily
     * @return \Illuminate\Http\Response
     */
    public function destroy(DanceFamily $danceFamily)
    {
        //
    }
}
