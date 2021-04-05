<?php

namespace App\Http\Controllers;

use App\Production;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProductionController extends Controller
{
    
    public function index()
    {
        return view('pages.productions');
    }

    public function liste()
    {
        return $this->refresh();
    }
    public function verifEmail($value){
        $production = User::where('email','=',$value)->get();
        return response()->json($production);
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
        $user->role = 'PRODUCTION';
        $user->save();

        $production = new Production();
        $production->user_id = $user->id;
        $production->save();
        if($user){
            return $this->refresh();  
        }
    }


    public function show(Production $production)
    {
        //
    }

    
    public function edit($id)
    {
        $production = User::find($id);
        return response()->json($production);
    }

    
    public function update($id)
    {
        $production = User::find($id);
        $production->nom = request('nom');
        $production->prenom = request('prenom');
        $production->email = request('email');
        $production->tel = request('tel');
        if(request('password') != ''){
            $password = Hash::make(request('password'));
            $production->password = $password;
        }
        $production->save();
        return $this->refresh();
    }


    public function destroy($id)
    {
        $production = User::find($id);
        if($production->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
        $production =  \DB::table('users')->where('role','=','PRODUCTION')->paginate(10);
        return response()->json($production);
    }
}