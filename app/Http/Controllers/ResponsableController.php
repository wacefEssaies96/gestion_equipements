<?php

namespace App\Http\Controllers;

use App\Responsable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResponsableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.responsables');
    }

    public function liste()
    {
        return $this->refresh();
    }
    public function verifEmail($value){
        $responsable = User::where('email','=',$value)->get();
        return response()->json($responsable);
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
        $user->role = 'RESPONSABLE';
        $user->save();

        $responsable = new Responsable();
        $responsable->user_id = $user->id;
        $responsable->save();
        if($user){
            return $this->refresh();  
        }
    }

    public function show(Responsable $responsable)
    {
        //
    }

    public function edit($id)
    {
        $responsable = User::find($id);
        return response()->json($responsable);
    }

    public function update($id){
        $responsable = User::find($id);
        $responsable->nom = request('nom');
        $responsable->prenom = request('prenom');
        $responsable->email = request('email');
        $responsable->tel = request('tel');
        if(request('password') != ''){
            $password = Hash::make(request('password'));
            $responsable->password = $password;
        }
        $responsable->save();
        return $this->refresh();
    }

    public function destroy($id){
        $responsable = User::find($id);
        if($responsable->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }

    public function refresh(){
        $responsable =  \DB::table('users')->where('role','=','RESPONSABLE')->paginate(1);
        return response()->json($responsable);
    }
}
