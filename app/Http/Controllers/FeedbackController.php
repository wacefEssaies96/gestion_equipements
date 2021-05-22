<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Feedback;
use App\Equipement;
use App\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function liste(Request $request)
    {
        if(count($request->all()) < 1) return $this->refresh();
        else{
            $feedbacks = Feedback::join('equipements', 'code_equip', '=', 'equipements.id')->select('feedback.*','equipements.designation');
            if($request->jour != '') $feedbacks = $feedbacks->whereDate('feedback.jour','like','%'.$request->jour.'%');
            if($request->zone != '') $feedbacks = $feedbacks->where('feedback.zone','=',$request->zone);
            if($request->equipement != '') $feedbacks = $feedbacks->where('equipements.designation','like','%'.$request->equipement.'%');
            $feedbacks = $feedbacks->paginate();
            return response()->json($feedbacks);
        }

    }

    public function store(Request $request)
    {
        $feedback = new Feedback();
        $feedback->jour= Carbon::now('Africa/Tunis');
        $feedback->zone = request('zone');
        $feedback->code_equip = request('equipement');
        $feedback->commentaire = request('commentaire');
        $feedback->prod_id = Auth::id();
        $feedback->save();
        return $this->refresh();      
    }
    
    public function edit($id)
    {
        $feedback = Feedback::find($id);
        return response()->json($feedback);
    }
    public function update($id)
    {
        $feedback = Feedback::find($id);
        $feedback->zone = request('zone');
        $feedback->code_equip = request('code_equip');
        $feedback->commentaire = request('commentaire');
        $feedback->save();
        return $this->refresh();
    }
    public function destroy($id)
    {
        $feedback = Feedback::find($id);
        if($feedback->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
        $feedback = Feedback::
        join('equipements', 'code_equip', '=', 'equipements.id')
        ->select('feedback.*', 'equipements.designation')
        ->paginate(10);
        return response()->json($feedback);
    }

}