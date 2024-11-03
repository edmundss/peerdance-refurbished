<?php

namespace App\Http\Controllers;

use App\Models\AlternateName;
use Illuminate\Http\Request;

class AlternateNameController extends Controller
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
        $this->validate($request, ['name' => 'required']);

        AlternateName::create([
            'name' => $request->name,
            'parent_class' => $request->parent_class,
            'parent_id' => $request->parent_id,
        ]);


        return redirect()->back()->withMessage('Alternate name was saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AlternateNames  $alternateNames
     * @return \Illuminate\Http\Response
     */
    public function show(AlternateNames $alternateNames)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AlternateNames  $alternateNames
     * @return \Illuminate\Http\Response
     */
    public function edit(AlternateNames $alternateNames)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AlternateNames  $alternateNames
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlternateNames $alternateNames)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AlternateNames  $alternateNames
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlternateNames $alternateNames)
    {
        //
    }
}
