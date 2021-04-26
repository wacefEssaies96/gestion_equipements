<?php

namespace App\Http\Controllers;

use Excel;
use App\CodePanne;
use App\Exports\CodePanneExport;
use App\Imports\CodePannesImport;
use Illuminate\Http\Request;

class CodePanneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function storeFromFile(Request $request){
        $path = $request->file;
        Excel::import(new CodePannesImport, $path);
        $this->fetchingData();
        return $this->refresh();
    }
    public function export() {
        return Excel::download(new CodePanneExport, 'disney.xlsx');
    }
    public function fetchingData(){
        $codePannes = CodePanne::all();
        foreach($codePannes as $item){
            $code = str_replace(["0","1","2","3","4","5","6","7","8","9"],"",$item->code);
            if($code == "CE"){
                $item->zone = "Controle éléctrique";
            }
            if($code == "OS" || $code == "OS(C/U)"){
                $item->zone = "Sertissage";
            }
            if($code == "MC"){
                $item->zone = "Coupe";
            }
            if($code == "PA" || $code == "MU" || $code == "R" || $code == "PG" || $code == "MK"){
                $item->zone = "Assemblage";
            }
            if($code == "DR" || $code == "MQ" || $code == "DG" || $code == "IN" || $code == "PR" || $code == "BT" || $code == "DN" || $code == "MS" || $code == "AB"){
                $item->zone = "Préparation";
            }
            $item->save();
        }
    }

    public function liste(Request $request)
    {
        $cp = CodePanne::where('code','like','%'.request('code').'%');
        if($request->zone != ''){
            $cp = $cp->where('zone', '=', request('zone'));
        }
        if($request->designation != ''){
            $cp = $cp->where('designation', 'like', '%'.request('designation').'%');
        }
        $cp = $cp->paginate(10);
        return response()->json($cp);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'code' => 'required',
            'designation' => 'required',
            'zone' => 'required',
        ]);
        $code_panne = new CodePanne();
        $code_panne->code= request('code');
        $code_panne->designation = request('designation');
        $code_panne->zone = request('zone');
        $code_panne->save();
        return $this->refresh();
    }

    public function edit($id)
    {
        $code_panne = CodePanne::find($id);
        return response()->json($code_panne );
    }


    public function update($id)
    {
        $code_panne = CodePanne::find($id);
        $code_panne->code= request('code');
        $code_panne->designation = request('designation');
        $code_panne->zone = request('zone');
        $code_panne->save();
        return $this->refresh();
    }

    
    public function destroy($id)
    { 
        $code_panne  = CodePanne::find($id);
        if($code_panne->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
        $code_panne = CodePanne::paginate(10);
        return response()->json($code_panne );
    }
    public function getCodePanneByCode($value){
        $cp = CodePanne::where('code','=',$value)->get();
        return response()->json($cp);
    }
    public function getCodePannes($zone){
        $codePannes = CodePanne::where('zone','=',$zone)->get();
        return response()->json($codePannes);
    }
}
