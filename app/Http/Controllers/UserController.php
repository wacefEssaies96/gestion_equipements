<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Production;
use App\Technicien;
use App\Hotline;
use App\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function liste(){
        return $this->refresh();
    }

    public function verifEmail($value){
        $user = User::where('email','=',$value)->get();
        return response()->json($user);
    }

    public function searchUser(Request $request){

        $user = User::where('nom','like','%'.request('nom').'%');
        $user = $user->where('prenom','like','%'.request('prenom').'%');
        $user = $user->where('tel','like','%'.request('tel').'%');
        $user = $user->where('email','like','%'.request('email').'%');
        $user = $user->where('role', 'like', '%'.request('role').'%');
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
    
    public function getUserId(){
        return response()->json(Auth::id());
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->nom = request('nom');
        $user->prenom = request('prenom');
        $user->email = request('email');
        $password = Hash::make($request['password']);
        $user->password = $password;
        $user->tel = request('tel');
        $user->role = request('role');
        $user->save();
        $this->createPersonel($request,$user->id);
        if($user){
            return $this->refresh();  
        }
    }
    
    public function update($id){
        $user = User::find($id);
        $user->nom = request('nom');
        $user->prenom = request('prenom');
        $user->email = request('email');
        $user->tel = request('tel');

        $role = $user->role;
        if($role != request('role')){
            $this->deletePersonel($role,$user->id);
            $this->createPersonel(request(),$user->id);
        }

        $user->role = request('role');
        if(request('password') != ''){
            $password = Hash::make(request('password'));
            $user->password = $password;
        }
        $user->save();
        return $this->refresh();
    }
    public function edit($id){

        $user = User::find($id);
        if($user->role != 'TECHNICIEN'){
            return response()->json($user);
        }
        else{
            $technicien = Technicien::join('users','techniciens.user_id','=','users.id')->where('techniciens.user_id','=',$id)->first();
            return response()->json($technicien);
        }
    }

    public function destroy($id){

        $user = User::find($id);
        if($user->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'Utilisateur ne peut pas être supprimer !']);
        }

    }
    public function refresh(){
        $users = User::paginate(10);
        return response()->json($users);
    }

    public function createPersonel($request,$id){
        if($request->role == 'RESPONSABLE'){
            $person = new Responsable();
        }
        if($request->role == 'HOTLINE'){
            $person = new Hotline();
        }
        if($request->role == 'PRODUCTION'){
            $person = new Production();
        }
        if($request->role == 'TECHNICIEN'){
            $person = new Technicien();
            $person->status = "DISPONIBLE";
            $person->zone = $request->zone;
            $person->qualification = $request->qualification;
            $person->h_debut_service = $request->h_debut_service;
            $person->h_fin_service = $request->h_fin_service;
        }
        if($person){
            $person->user_id = $id;
            $person->save();
            return "Personel created !";
        }
    }

    public function deletePersonel($role,$id){
        
        if($role == 'RESPONSABLE'){
            $person = Responsable::where('user_id','=',$id)->first();
        }
        if($role == 'HOTLINE'){
            $person = Hotline::where('user_id','=',$id)->first();
        }
        if($role == 'PRODUCTION'){
            $person = Production::where('user_id','=',$id)->first();
        }
        if($role == 'TECHNICIEN'){
            $person = Technicien::where('user_id','=',$id)->first();
        }

        $person->delete();
        return "Personel modified !";
    }

    public function getAllTechs(){
        $techs = User::where('role', '=', 'TECHNICIEN')->get();
        return response()->json($techs);
    }
    public function getTechniciens($zone){
        $techniciens =  Technicien::with('user')
        ->where('zone','=',$zone)
        ->where('status', '=', 'DISPONIBLE')
        ->get();
        return response()->json($techniciens);
    }
}
