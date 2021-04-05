<?php

namespace App\Http\Controllers;

use App\User;
use App\Technicien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicienController extends Controller
{

    public function index()
    {
        return view('pages.techniciens');
    }
    public function liste()
    {
        return $this->refresh();
    }
    public function verifEmail($value){
        $technicien = User::where('email','=',$value)->get();
        return response()->json($technicien);
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'nom' => 'required',
            'prenom' => 'required',
            'password' => 'required:min:6',
            'email' => 'required|email|unique:users',
            'tel' => 'required'
        ]);
        $user = new User();
        $user->nom = request('nom');
        $user->prenom = request('prenom');
        $user->email = request('email');
        $password = Hash::make($request['password']);
        $user->password = $password;
        $user->tel = request('tel');
        $user->role = 'TECHNICIEN';
        $user->save();

        $technicien = new Technicien();
        $technicien->user_id = $user->id;
        $technicien->qualification = request('qualification');
        $technicien->h_debut_service = request('h_debut_service');
        $technicien->h_fin_service = request('h_fin_service');
        $technicien->zone = request('zone');
        $technicien->status = 'DISPONIBLE';
        $technicien->save();
        if($user){
            return $this->refresh();  
        }
    }

    public function edit($id)
    {
        $technicien = Technicien::join('users','techniciens.user_id','=','users.id')->where('techniciens.user_id','=',$id)->get();
        return response()->json($technicien);
    }

    public function update($id){
        $user = User::find($id);
        $user->nom = request('nom');
        $user->prenom = request('prenom');
        $user->email = request('email');
        $user->tel = request('tel');
        if(request('password') != ''){
            $password = Hash::make(request('password'));
            $user->password = $password;
        }
        $user->save();

        $technicien = Technicien::where('user_id','=',$id)->first();
        $technicien->zone = request('zone');
        $technicien->qualification = request('qualification');
        $technicien->h_debut_service = request('h_debut_service');
        $technicien->h_fin_service = request('h_fin_service');
        $technicien->save();
        return $this->refresh();
    }

    public function destroy($id){
        $technicien = User::find($id);
        if($technicien->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }

    public function refresh(){
        $technicien =  User::join('techniciens','users.id','=','techniciens.user_id')
        ->where('role','=','TECHNICIEN')->paginate(10);
        return response()->json($technicien);
    }
}
