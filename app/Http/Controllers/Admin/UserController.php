<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use DataTables;
use Auth;

class UserController extends Controller
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
            'page_title' => 'User List',
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>'#', 'title'=>'Admin'],
                ['url'=>route('admin.user.index'), 'title'=>'Users'],
            ),
        );

        return view('admin.users.index')->with($params);
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


        return view('admin.users.show')->with($params);
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
        $user->delete();
        return redirect()->route('admin.user.index')->withMessage('User deleted!');
    }

    public function datatable ()
    {

        $users = DB::table('users')
            //->whereNull('users.deleted_at')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->groupBy(['users.id', 'users.name', 'users.email'])
            ->select([
                'users.name',
                'users.id',
                'users.email',
                DB::raw("GROUP_CONCAT(roles.display_name SEPARATOR ', ')  AS roles"),

            ]);


        return DataTables::of($users)
            ->editColumn('name', function($item){
                return '<a href="'.route('admin.user.show', $item->id).'" >'.$item->name.'</a>';
            })
            ->rawColumns(['name'])
            ->make(true);
    }
}
