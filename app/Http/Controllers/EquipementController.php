<?php

namespace App\Http\Controllers;

use App\Equipement;
use App\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Imports\EquipementImport;
use App\Exports\EquipementExport;
use Excel;

class EquipementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function importDataFromExcel(Request $request){
        $file = public_path()."/downloads/equipement.xlsx";
        try{
            Excel::import(new EquipementImport, $file);
        }catch(Exception $ex){
            return response()->json("erreur");
        }  
        return $this->refresh();
    }

    public function storeFromFile(Request $request){
        $path = $request->file;
        Excel::import(new EquipementImport, $path);
        return $this->refresh();
    }
    public function export() {
        return Excel::download(new EquipementExport, 'equipement.xlsx');
    }

    public function liste(Request $request)
    {
        $equip = Equipement::where('code','like','%'.request('code').'%');
        if($request->zone != ''){
            $equip = $equip->where('zone', '=', request('zone'));
        }
        if($request->constructeur != ''){
            $equip = $equip->where('constructeur', 'like', '%'.request('constructeur').'%');
        }
        if($request->code_categorie != ''){
            $equip = $equip->where('code_categorie', 'like', '%'.request('code_categorie').'%');
        }
        if($request->n_serie != ''){
            $equip = $equip->where('n_serie', 'like', '%'.request('n_serie').'%');
        }
        if($request->designation != ''){
            $equip = $equip->where('designation', 'like', '%'.request('designation').'%');
        }
        $equip = $equip->with('documents')->paginate(10);
        return response()->json($equip);
    }

    public function store(Request $request)
    {
        $equipement = new Equipement();
        $equipement->code_categorie = request('code_categorie');
        $equipement->desi_cat = request('desi_cat');
        $equipement->emplacement = request('emplacement');
        $equipement->date_acq = request('date_acq');
        $equipement->constructeur = request('constructeur');
        $equipement->etat = request('etat');
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
        if(request('image') != null){
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
        }
        $equipement->desi_cat = request('desi_cat');
        $equipement->code_categorie = request('code_categorie');
        $equipement->emplacement = request('emplacement');
        $equipement->date_acq = request('date_acq');
        $equipement->constructeur = request('constructeur');
        $equipement->etat = request('etat');
        $equipement->code= request('code');
        $equipement->n_serie = request('n_serie');
        $equipement->designation = request('designation');
        $equipement->zone = request('zone');
        $equipement->save();
        $documents = json_decode(request('documents'));
        foreach($documents as $item){
            $document = Document::where('equipement_id', '=', $id)->where('type', '=', $item->type)->first();
            if($document){
                unlink(realpath($document->document));
            }
            else{
                $document = new Document();
                $document->equipement_id = $id;
                $document->type = $item->type;
            }
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
        $equipement=Equipement::with('documents')->paginate(10);
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
}
