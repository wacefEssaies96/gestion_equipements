<?php

namespace App\Http\Controllers;

use App\Equipement;
use App\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Imports\EquipementImport;
use Excel;

class EquipementController extends Controller
{
    public function importDataFromExcel(Request $request){
        $file = public_path()."/downloads/equipement.xlsx";
        try{
            Excel::import(new EquipementImport, $file);
        }catch(Exception $ex){
            return response()->json("erreur");
        }  
        return response()->json("c bon");
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
        $equip = $equip->paginate(10);
        return response()->json($equip);
    }

    public function store(Request $request)
    {
        $equipement = new Equipement();
        $equipement->nom = request('nom');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        $imageArr = explode(',',request('image'));
        if($imageArr[0] != 'null'){
            $extension  = ".png";
            if(str_contains($imageArr[0], 'jpeg')) $extension = ".jpg";
            $filename = "uploads/" . time() . '_'. request('nom') . '_'. rand(1000, 100000) . $extension;
            $image = fopen($filename, "wb");
        
            fwrite($image, base64_decode($imageArr[1]));
            fclose($image);
            $equipement->image = $filename;
        }
        $equipement->zone = request('zone');
        $equipement->save();
        $documents = json_decode($request->documents);
        foreach($documents as $item){
            $document = new Document();
            $document->equipement_id = $equipement->id;
            $document->document = $item->filePath;
            $document->type = $item->type;
            $document->save();
        }
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
        if(!str_contains(request('image'), "uploads")) // image changed
        {
            if($equipement->image != ''){
                unlink(realpath($equipement->image)); // détecher le lien ancien de l'image
            }
            $imageArr = explode(',',request('image')); // Decoupe un string en tableau en fonction d'un caractère de base 
            $extension  = ".png";
            if(str_contains($imageArr[0], 'jpeg')) $extension = ".jpg";
            $filename = "uploads/" . time() . '_'. request('nom') . '_'. rand(1000, 100000) . $extension;

            $image = fopen($filename, "wb"); // ouvrir l'image sous form binaire
            fwrite($image, base64_decode($imageArr[1])); // decoder l'image a la base 64
            fclose($image); // fermer le fichier
            $equipement->image = $filename;
        }
        $equipement->nom = request('nom');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        $equipement->zone = request('zone');
        $equipement->save();
        $documents = json_decode(request('documents'));
        foreach($documents as $item){
            $document = Document::where('equipement_id', '=', $id)->where('type', '=', $item->type)->first();
            unlink(realpath($document->document));
            $document->document = $item->filePath;
            $document->save();
        }
        return $this->refresh();  
    }

  
    public function destroy($id)
    {
        $equipement = Equipement::find($id);
        if($equipement->image != null){
            unlink(realpath($equipement->image));
        }
        $documents = Document::where('equipement_id', '=', $id)->get();
        foreach($documents as $document){
            unlink(realpath($document->document));
        }
        if($equipement->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
        $equipement=Equipement::paginate(10);
        return response()->json($equipement);
    }
    public function getEquipementByCode($value){
        $equipement = Equipement::where('code','=',$value)->get();
        return response()->json($equipement);
    }

    public function getEquipements($zone){
        $equipements = Equipement::where('zone','=',$zone)->get();
        return response()->json($equipements);
    }
    public function getEquipById($id){
        $e = Equipement::where('id', '=', $id)->first();
        return response()->json($e);
    }
}
