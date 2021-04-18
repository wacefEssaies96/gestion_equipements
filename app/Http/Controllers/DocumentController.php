<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getDocument($id,$type){
        $document = Document::
        where('equipement_id', '=', $id)
        ->where('type', '=', $type)
        ->first();
        if(!$document) return response()->json('introuvable');
        return response()->json($document);
    }
    public function getDoc($id){
        $document = Document::find($id)->first();
        if(!$document) return response()->json('introuvable');
        return response()->json($document);
    }
    public function deleteData(Request $request){
        $documents = json_decode($request->documents);
        foreach($documents as $item){
            unlink(realpath($item->filePath));
        }
        return 1;
    }
}
