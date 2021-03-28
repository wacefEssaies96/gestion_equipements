<?php

namespace App\Http\Controllers;

use App\CodePanne;
use Illuminate\Http\Request;

class CodePanneController extends Controller
{
  
    public function index()
    {
        return view('pages.code_pannes');
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
}
