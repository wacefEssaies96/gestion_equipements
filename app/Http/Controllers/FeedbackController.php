<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Equipement;
use App\Production;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{

    public function index()
    {
        return view('pages.feedbacks');
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

        $feedback = new Feedback();
        $feedback->jour= request('jour');
        $feedback->zone = request('zone');
        $feedback->code_equip = request('equipement');
        $feedback->commentaire = request('commentaire');
        $feedback->prod_id = Auth::id();
        $feedback->save();
        return $this->refresh();

      
    }
    

    public function show(Feedback $feedback)
    {
        //
    }

   
    public function edit($id)
    {
        $feedback = Feedback::find($id);
        return response()->json($feedback );
    }

   
    public function update($id)
    {
        $feedback = Feedback::find($id);
        $feedback->jour = request('jour');
        $feedback->zone = request('zone');
        $feedback->equipement = request('equipement');
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
        $feedback = Feedback::paginate(10);
        return response()->json($feedback );
    }
    // public function refresh(){
    //     $feedback = DB::table('feedback')
    //                     ->join('equipements', 'feedback.code_equip', '=', 'equipements.id')
    //                     ->select('feedback.*', 'equipements.nom')
    //                     ->paginate(10);
    // /*
    //     select * from feedback, equiements where feedback.code_equip=equipements.id limit 10
    // */
    // }
    // public function getProdectionFeedback ($value){
    //     $fb = Feedback::where('id','=',$value)->get();
    //     return response()->json($fb);
    // }
}