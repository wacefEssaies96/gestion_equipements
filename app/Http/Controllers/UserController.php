<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function searchUser(Request $request){

        $user = User::where('nom','like','%'.request('nom').'%');
        $user = $user->where('prenom','like','%'.request('prenom').'%');
        $user = $user->where('tel','like','%'.request('tel').'%');
        $user = $user->where('email','like','%'.request('email').'%');
        if($request->role == 'HOTLINE'){
            $user = $user->where('role', '=', 'HOTLINE');
        }
        if($request->role == 'RESPONSABLE'){
            $user = $user->where('role', '=', 'RESPONSABLE');
        }
        if($request->role == 'PRODUCTION'){
            $user = $user->where('role', '=', 'PRODUCTION');
        }
        if($request->role == 'TECHNICIEN'){
            $user = $user->join('techniciens','users.id','=','techniciens.user_id');
            $user = $user->where('role', '=', 'TECHNICIEN');
            if(request('zone') != ''){
                $user = $user->where('techniciens.zone', '=', request('zone'));
            }
        }
        $user = $user->paginate(10);
        return response()->json($user);
    }
}
