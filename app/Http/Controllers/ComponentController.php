<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;

use Auth;

class ComponentController extends Controller
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
        $this->validate(
            $request,
            [
                'parent_class' => 'required',
                'parent_id' => 'required',
                'step_id' => 'required_without:description',
                'description' => 'required_without:step_id',
            ]
        );

        $parent_class = $request->input('parent_class');
        $parent_id = $request->input('parent_id');

        $previous_number = Component::where('parent_class', $parent_class)
            ->where('parent_id', $parent_id)
            ->orderBy('order_number', 'DESC')
            ->value('order_number');

        //return $previous_number;

        Component::create([
            'parent_id' => $parent_id,
            'parent_class' => $parent_class,
            'step_id' => $request->input('step_id'),
            'description' => $request->input('description'),
            'order_number' => ($previous_number)?$previous_number+1:1,
        ]);

        return redirect()->back()->withMessage('Transcript was successfully saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        //return $component;

        $step = $component->step()->pluck('title', 'id');
        $parent = $component->parent;

        //return $component;

        $params = array(
            'page_title' => 'Edit component',
            'component' => $component,
            'step' => $step,
        );

        return view('components.edit')->with($params);   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        $this->validate(
            $request,
            [
                'step_id' => 'required_without:description',
                'description' => 'required_without:step_id',
            ]
        );

        $component->update([
            'step_id' => $request->input('step_id'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route(strtolower($component->parent_class) . '.show', $component->parent_id)->withMessage('Component was successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        $component->delete();

        return redirect()->back()->withMessage('Component was removed!');
    }

    public function update_order(Request $request){

        if(Auth::id()!=1){
            abort(403, 'Unauthorized action.');
        }

        $items = $request->input('items');

        foreach ($items as $i) {
            Component::findOrFail($i['id'])->update(['order_number' => $i['order_number']]);
        }
    }
}
