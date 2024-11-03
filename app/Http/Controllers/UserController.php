<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use Auth;
use DB;

use App\User;
use  App\Models\Role;
use  App\Models\Picture;

class UserController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy', 'toggle']);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $params = [
            'session_owner' => Auth::user(),
            'user' => $user,
            'header_size' => 'header-xl  profile-header',
            'appclass' =>'page-profile',
            'roles' => Role::orderBy('name')->get(),
        ];


        return view('users.show')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    
    public function get_expertise($id)
    {

        $colors = ["#5867C3", "#1C86BF", "#28BEBD", "#FEB38D", "#EE6E73", "#EC407A", "#F8C200", "#5867C3", "#1C86BF", "#28BEBD", "#FEB38D", "#EE6E73", "#EC407A", "#F8C200"];

        $data = [];

        $expertise = DB::table('dances')
            ->leftJoin('steps', 'dances.id', '=', 'steps.dance_id')
            ->leftJoin('step_user', 'steps.id', '=', 'step_user.step_id')
            ->where('step_user.user_id', $id)
            /*
            */
            ->groupBy('dances.id')
            ->select([
                DB::raw('COUNT(steps.id) AS steps'),
                'dances.title'
            ])
            ->get();

        foreach ($expertise as $key => $e) {
            $data['labels'][] = $e->title;
            $data['datasets']['data'][] = $e->steps;
            $data['datasets']['backgroundColor'][] = $colors[$key];
        }

        return $data;
    }



        // update add/remove user's role
    public function updateRoles(Request $request) {

        $assign = $request->input('assign');
        $user_id = $request->input('user_id');
        $user = User::findOrFail($user_id);
        $role_id = $request->input('role_id');

        //add or remove roles
        if ($assign == 'true') {
            $user->roles()->attach($role_id);
            return 'Loma ir piešķirta!';
        } else {
            $user->roles()->detach($role_id);
            return 'Loma ir noņemta!';
        }
    }

    public function upload_avatar(Request $request)
    {
        $session_owner = Auth::user();
        $picture_model = Picture::create(['parent_class' => 'User' ,'parent_id' => $session_owner->id]);

        try {
            $picture = $request->file('avatar');

            $img = Image::make($picture);

            $path = 'image/avatars/' . $picture_model->id . '/';
            $result = File::makeDirectory($path, 0775, true, true);

            $img->save($path . 'original.jpg', 80);

            $full = $img->widen(450);
            $full->save($path . 'full.jpg', 80);

            $thumb = $img->fit(250, 250);
            $thumb->save($path . 'big.jpg', 80);

            $thumb = $img->fit(128, 128);
            $thumb->save($path . 'thumb.jpg', 80);

            $thumb = $img->fit(35, 35);
            $thumb->save($path . 'xs.jpg', 80);

            return redirect()->back()->withMessage('Your new avatar is saved.');
        } catch (\Exception $e) {
            $picture_model->delete();
            return redirect()->back()->withErrors(['Can`t save the picture. Could be broken file, file too large or unsupported format.', $e->getMessage()]);
        }
    }

    public function upload_cover(Request $request)
    {
        $session_owner = Auth::user();
        $picture_model = Picture::create(['parent_class' => 'ProfileCover' ,'parent_id' => $session_owner->id]);

        try {
            $picture = $request->file('cover');

            $img = Image::make($picture);

            $path = 'image/covers/' . $picture_model->id . '/';
            $result = File::makeDirectory($path, 0775, true, true);

            $img->save($path . 'original.jpg', 80);

            $thumb = $img->fit(1600, 686);
            $thumb->save($path . 'lg.jpg', 80);

            $thumb = $img->fit(800, 343);
            $thumb->save($path . 'md.jpg', 80);

            $thumb = $img->fit(220, 135);
            $thumb->save($path . 'sm.jpg', 80);

            $thumb = $img->fit(140, 265);
            $thumb->save($path . 'sm-vertical.jpg', 80);

            $thumb = $img->fit(300, 300);
            $thumb->save($path . 'square.jpg', 80);

            return redirect()->back()->withMessage('Your new cover picture is saved.');
        } catch (\Exception $e) {
            $picture_model->delete();
            return redirect()->back()->withErrors(['Can`t save the picture. Could be broken file, file too large or unsupported format.', $e->getMessage()]);
        }
    }

    
}
