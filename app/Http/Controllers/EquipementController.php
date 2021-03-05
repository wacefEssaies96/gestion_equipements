<?php

namespace App\Http\Controllers;

use App\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EquipementController extends Controller
{
    
    public function index()
    {
        return view('equipements');
    }
    public function liste()
    {
        return $this->refresh();
    }

    
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'nom' => 'required',
            'code' => 'required',
            'n_serie' => 'required',
            'designation' => 'required',
            'image' => 'required',
            'zone' => 'required'
        ]);
        $equipement = new Equipement();
        $equipement->id = request('id');
        $equipement->nom = request('nom');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        $equipement->image = request('image');
        $equipement->zone = request('zone');
        $equipement->save();
        return $this->refresh();  
        
    }

    
    public function show(Equipement $equipement)
    {
        //
    }


    public function edit($id)
    {
        $equipement = Equipement::find($id);
        return response()->json($equipement);
    }

    
    public function update($id)
    {
        $equipement = new Equipement();
        $equipement->id = request('id');
        $equipement->nom = request('nom');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        $equipement->image = request('image');
        $equipement->zone = request('zone');
        $equipement->save();
        return $this->refresh();  
        
    }

  
    public function destroy(Equipement $equipement)
    {
        $equipement = Equipement::find($id);
        if($equipement->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
         $equipement=Equipement::all();
         return response()->json($equipement);
     }
}
