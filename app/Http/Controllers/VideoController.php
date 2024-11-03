<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

use Auth;

use App\Models\Choreography;
use App\Models\Component;
use Illuminate\Support\Str;

class VideoController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update', 'destroy', 'component']);
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
        $this->validate($request, ['parent_id' => 'required', 'parent_class' => 'required']);

        $session_owner = Auth::user();
        $parent_id = $request->input('parent_id');
        $parent_class = $request->input('parent_class');

        $parent = Choreography::findOrFail($parent_id);
        $dance = $parent->dance;


        $params = array(
            'page_title' => 'Video',
            'sub_title' => 'pievienošana',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('dances.index'), 'title'=>'Deju katalogs'],
                ['url'=>route('dances.show', $dance->id), 'title'=>$dance->title],
                ['url'=>route('choreographies.show', $parent_id), 'title'=>$parent->title],
                ['url'=>route('videos.create'), 'title'=>'video pievienošana'],
            ),
            'parent'=>$parent,
            'video_types' => config('constants.video_types'),
        );

        return view('videos.create')->with($params);
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
                'parent_id' => 'required',
                'parent_class' => 'required',
                'video_id' => 'required',
                'title' => 'required',
                'type' => 'required',
            ]
        );

        $parent_id = $request->input('parent_id');
        $parent_class = $request->input('parent_class');

        Video::create($request->all());

        return redirect()->route(Str::kebab(Str::plural($parent_class)) . '.show', $parent_id)->withMessage('Video was saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {

        $params = array(
            'page_title' => 'Edit video info',
            'video' => $video,
        );

        return view('videos.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $this->validate(
            $request,
            [
                'parent_id' => 'required',
                'parent_class' => 'required',
                'video_id' => 'required',
                'title' => 'required',
                'type' => 'required',
            ]
        );

        $parent_id = $request->parent_id;
        $parent_class = $request->parent_class;

        $video->update($request->all());

        return redirect()->route(snake_case($parent_class) . '.show', [ $parent_id, 'vid' => $video->id])->withMessage('Video was saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }

    public function component(Request $request)
    {
        //return $request->all();

        $component_id = $request->input('component_id');

        //return $component_id;

        $this->validate(
            $request,
            [
                'video_id' => 'required',
                'component_id' => 'required',
                'start_' . $component_id => 'required',
                'end_' . $component_id => 'required',
            ]
        );

        $component = Component::findOrFail($component_id);
        $video_id = $request->input('video_id');

        $component->videos()->attach($video_id, [
            'start' => $request->input('start_' . $component_id),
            'end' => $request->input('end_' . $component_id),
        ]);

        $component->videos()->updateExistingPivot($video_id, [
            'start' => $request->input('start_' . $component_id),
            'end' => $request->input('end_' . $component_id),
        ]);

        return redirect()->back()->withMessage('Soļa laiks ir saglabāts');
    }
}
