<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Technicien;
use App\Historique;
use App\User;
use App\CodePanne;
use App\Equipement;
use App\HistAssemblage;
use App\CodePanneInHist;
use Carbon\Carbon;
use App\HistSertissage;
use App\HistElectrique;


class HistoriqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.historique');
    }

    public function listeTech(){
        return $this->refreshTech();
    }
    public function getAllTechs(){
        $techs = User::where('role', '=', 'TECHNICIEN')->get();
        return response()->json($techs);
    }
    public function liste(Request $request)
    {
        $hist = Historique::where('num_bt','like','%'.request('num_bt').'%');
        if($request->zone != ''){
            $hist = $hist->where('zone', '=', request('zone'));
        }
        if($request->appelle != ''){
            $hist = $hist->where('appelle', '=', request('appelle'));
        }
        if($request->tech_id != null){
            $hist = $hist->where('tech_id', '=', request('tech_id'));
        }
        if($request->jour != ''){
            $hist = $hist->whereDate('jour', request('jour'));
        }
        $hist = $hist->get();
        return response()->json($hist);
    }

    public function store(Request $request)
    {
        $hist = new Historique();
        $hist->num_bt = request('num_bt');
        $hist->heure_demande = request('heure_demande');
        $hist->hotline_id = Auth::id();
        $hist->zone = request('zone');
        $hist->jour = request('jour');
        $hist->appelle = 'Non cloturé';
        $hist->valide = false;
        $hist->tech_id = request('tech_id');
        $hist->save();
        if($hist){
            return $this->refresh(); 
        }
    }
    public function getUserId(){
        return response()->json(Auth::id());
    }
    public function updatefortech($id){
        $hist = Historique::find($id);
        $hist->heure_fin = request('heure_fin');
        $hist->heure_debut = Carbon::now('Africa/Tunis');
        $hist->travaille = request('travaille');
        $hist->piece_rechange = request('piece_rechange');
        $hist->commentaire = request('commentaire');
        $hist->code_equip = request('code_equip');
        $hist->valide = true;
        $hist->save();
        $codePanne = request('code_panne');
        foreach($codePanne as $item){
            $test = new CodePanneInHist();
            $test->hist = $id;
            $test->code_panne = $item;
            $test->save();
        }
        if($hist->zone == 'Assemblage'){
            $histAssemblage = new HistAssemblage();
            $histAssemblage->num_planche = request('num_planche');
            $histAssemblage->hist_id = $id;
            $histAssemblage->save();
        }
        if($hist->zone == 'Sertissage'){
            $histAssemblage = new HistSertissage();
            $histAssemblage->type_travaille = request('type_travaille');
            $histAssemblage->hist_id = $id;
            $histAssemblage->save();
        }
        if($hist->zone == 'Electrique'){
            $histElectrique = new HistElectrique();
            $histElectrique->nom_support = request('nom_support');
            $histElectrique->hist_id = $id;
            $histElectrique->save();
        }
        return $this->refresh();
    }
    public function edit($id)
    {
        $hist = Historique::find($id);
        return response()->json($hist);
    }

    public function update($id)
    {
        $hist = Historique::find($id);
        $hist->num_bt = request('num_bt');
        $hist->heure_demande = request('heure_demande');
        $hist->hotline_id = Auth::id();
        $hist->zone = request('zone');
        $hist->jour = request('jour');
        $hist->appelle = request('appelle');
        $hist->tech_id = request('tech_id');
        $hist->save();
        return $this->refresh();
    }

    public function destroy($id)
    {
        $hist = Historique::find($id);
        if($hist->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'Erreur serveur']);
        }
    }
    
    public function refresh(){
        $historiques = Historique::all();
        return response()->json($historiques);
    }

    public function refreshTech(){
        $historiques = Historique::where('tech_id','=',Auth::id())->where('valide','=',false)->get();
        return response()->json($historiques);
    }

    public function getTechniciens($zone){
        $techniciens =  Technicien::with('user')->where('zone','=',$zone)->get();
        return response()->json($techniciens);
    }
    
    public function getEquipements($zone){
        $equipements = Equipement::where('zone','=',$zone)->get();
        return response()->json($equipements);
    }

    public function getCodePannes($zone){
        $codePannes = CodePanne::where('zone','=',$zone)->get();
        return response()->json($codePannes);
    }

}
