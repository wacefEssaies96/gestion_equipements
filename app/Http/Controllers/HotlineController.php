<?php

namespace App\Http\Controllers;

use App\Hotline;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class HotlineController extends Controller
{

    public function index()
    {
        return view('pages.hotlines');
    }
    
    public function liste()
    {
        return $this->refresh();
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'nom' => 'required',
            'prenom' => 'required',
            'pseudo' => 'required|unique:users',
            'password' => 'required:min:6',
            'email' => 'required|email|unique:users',
            'tel' => 'required'
        ]);
        $user = new User();
        $user->nom = request('nom');
        $user->prenom = request('prenom');
        $user->email = request('email');
        $user->pseudo = request('pseudo');
        $password = Hash::make($request['password']);
        $user->password = $password;
        $user->tel = request('tel');
        $user->role = 'HOTLINE';
        $user->save();

        $hotline = new Hotline();
        $hotline->user_id = $user->id;
        $hotline->save();
        if($user){
            return $this->refresh();  
        }
    }

    public function edit($id)
    {
        $hotline = User::find($id);
        return response()->json($hotline);
    }

    public function update($id)
    {
        $hotline = User::find($id);
        $hotline->nom = request('nom');
        $hotline->prenom = request('prenom');
        $hotline->email = request('email');
        $hotline->pseudo = request('pseudo');
        $hotline->tel = request('tel');
        if(request('password') != ''){
            $password = Hash::make(request('password'));
            $hotline->password = $password;
        }
        $hotline->save();
        return $this->refresh();
    }

   
    public function destroy($id)
    {
        $hotline = User::find($id);
        if($hotline->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }

    public function refresh(){
        $hotline =  \DB::table('users')->where('role','=','HOTLINE')->paginate(10);
        return response()->json($hotline);
    }
}
