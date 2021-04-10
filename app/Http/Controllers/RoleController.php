<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function liste(Request $request)
    {
        return $this->refresh();
    }
   
    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $this->validate($request,[
            'designation' => 'required',
        ]);
        $role = new Role();
        $role->designation = request('designation');
        $role->save();
        return $this->refresh();
    }


    public function show(Role $role)
    {
        //
    }


    public function edit($id)
    {
        $role = Role::find($id);
        return response()->json($role );
    }

    
    public function update($id)
    {
        $role = Role::find($id);
        $role->designation = request('designation');
        $role->save();
        return $this->refresh();
    }

   
    public function destroy($id)
    {
        $role = Role::find($id);
        if($role->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
        $role = Role::paginate(10);
        return response()->json($role );
    }
}
