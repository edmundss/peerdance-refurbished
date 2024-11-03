<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

use Auth;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $session_owner = Auth::user();
        $roles = Role::all();

        $params = array(
            'session_owner' => $session_owner,
            'page_title' => 'Lomas',
            'roles' => $roles,
        );

        return view('roles.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $session_owner = Auth::user();

        $params = array(
            'session_owner' => $session_owner,
            'page_title' => 'Jaunas lomas izveide',
        );

        return view('roles.create')->with($params);       
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
            array(
                'name'=>'required',
                'display_name'=>'required',
            )
        );

        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $description = $request->input('description');

        $role = Role::create([
            'name' => $name,
            'display_name' => $display_name,
            'description' => $description,
        ]);

        return redirect()->route('role.show', $role->id)->withMessage('Jauna loma ir pievienota.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $session_owner = Auth::user();
        $permissions = Permission::all();

        $params = array(
            'session_owner' => $session_owner,
            'page_title' => 'Lomas',
            'sub_title' => 'kartiņa',
            'role' => $role,
            'permissions' => $permissions,
        );

        return view('roles.show')->with($params);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
