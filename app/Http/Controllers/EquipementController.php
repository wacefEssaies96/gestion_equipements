<?php

namespace App\Http\Controllers;

use App\Equipement;
use Illuminate\Http\Request;

class EquipementController extends Controller
{
    
    public function index()
    {
        return view('pages.equipements');
    }
    public function liste(Request $request)
    {
        $equip = Equipement::where('code','like','%'.request('code').'%');
        if($request->zone != ''){
            $equip = $equip->where('zone', '=', request('zone'));
        }
        if($request->nom != ''){
            $equip = $equip->where('nom', 'like', '%'.request('nom').'%');
        }
        if($request->n_serie != ''){
            $equip = $equip->where('n_serie', 'like', '%'.request('n_serie').'%');
        }
        if($request->designation != ''){
            $equip = $equip->where('designation', 'like', '%'.request('designation').'%');
        }
        $equip = $equip->get();
        return response()->json($equip);
    }

    public function store(Request $request)
    {
        /**
         * Début enregistrement de l'image.
         * Todo: Changer l'approche d'upload et utiliser les objets
         * file de laravel.
         * bug qui bloque: Laravel reçoit l'image envoyé par axios dans la variable
         * $_POST et non dans $_FILES, ce qui fait que les accès comme $request->file('image')
         * ou $request->image retourne null.
         */
        $imageArr = explode(',',$_POST['image']);
        $extension  = ".png";
        if(str_contains($imageArr[0], 'jpeg')) $extension = ".jpg";
        $filename = "uploads/" . time() . '_'. request('nom') . '_'. rand(1000, 100000) . $extension;
        $image = fopen($filename, "wb");
        fwrite($image, base64_decode($imageArr[1]));
        fclose($image);
     
        $equipement = new Equipement();
        $equipement->nom = request('nom');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        $equipement->image = $filename;
        $equipement->zone = request('zone');
        $equipement->save();
        return $this->refresh();
        
    }

    public function edit($id)
    {
        $equipement = Equipement::find($id);
        return response()->json($equipement);
    }

    
    public function update($id)
    {
        $equipement = Equipement::find($id);
        $equipement->nom = request('nom');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        // $equipement->image = request('image');
        $equipement->zone = request('zone');
        $equipement->save();
        return $this->refresh();  
    }

  
    public function destroy($id)
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
