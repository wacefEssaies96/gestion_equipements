<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\User;
use Carbon\Carbon;
use App\CodePanne;
use App\Equipement;
use App\Technicien;
use App\Historique;
use App\HistSertissage;
use App\HistElectrique;
use App\HistAssemblage;
use App\CodePanneInHist;


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
        $hist = $hist->paginate(10);
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
        $hist->tech_id = request('tech_id');
        $hist->valide = false;

        if(Auth::user()->role == 'ADMIN') {
            if(request('heure_fin') != '' 
                && request('travaille') != '' 
                && request('piece_rechange') != '' 
                && request('commentaire') != '' 
                && request('code_equip') != '')
            { 
                $hist->valide = true; 
            }
            $hist->heure_fin = request('heure_fin');
            $hist->heure_debut = Carbon::now('Africa/Tunis');
            $hist->travaille = request('travaille');
            $hist->piece_rechange = request('piece_rechange');
            $hist->commentaire = request('commentaire');
            $hist->code_equip = request('code_equip');
        }
        $hist->save();

        $userId = request('tech_id');
        $technicien = Technicien::where('user_id', '=', $userId)->first();
        $technicien->status = 'NON DISPONIBLE';
        $technicien->save();

        if(request('code_panne') != null){
            $codePanne = request('code_panne');
            foreach($codePanne as $item){
                $cp = new CodePanneInHist();
                $cp->hist = $hist->id;
                $cp->code_panne = $item;
                $cp->save();
            }
        }
        
        if($hist->zone == 'Assemblage'){
            $histAssemblage = new HistAssemblage();
            $histAssemblage->num_planche = request('num_planche');
            $histAssemblage->historique_id = $hist->id;
            $histAssemblage->save();
        }
        if($hist->zone == 'Sertissage'){
            $histAssemblage = new HistSertissage();
            $histAssemblage->type_travaille = request('type_travaille');
            $histAssemblage->hist_id = $hist->id;
            $histAssemblage->save();
        }
        if($hist->zone == 'Controle éléctrique'){
            $histElectrique = new HistElectrique();
            $histElectrique->nom_support = request('nom_support');
            $histElectrique->hist_id = $hist->id;
            $histElectrique->save();
        }
        if(Auth::user()->role == 'HOTLINE'){
            return $this->getHotlineHistoriques();
        }
        return $this->refresh();
    }

    public function updatefortech($id){
        $codePanne = request('code_panne');
        foreach($codePanne as $item){
            $cp = new CodePanneInHist();
            $cp->hist = $id;
            $cp->code_panne = $item;
            $cp->save();
        }
        $hist = Historique::find($id);
        $hist->heure_fin = request('heure_fin');
        $hist->heure_debut = Carbon::now('Africa/Tunis');
        $hist->travaille = request('travaille');
        $hist->piece_rechange = request('piece_rechange');
        $hist->commentaire = request('commentaire');
        $hist->code_equip = request('code_equip');
        $hist->appelle = 'Cloturé';
        $hist->valide = true;
        $hist->save();
        $userid = Auth::id();
        $technicien = Technicien::where('user_id', '=', $userid)->first();
        $technicien->status = 'DISPONIBLE';
        $technicien->save();
        if(request('num_planche') != ''){
            $histAssemblage = HistAssemblage::where('historique_id', '=', $id)->first();
            $histAssemblage->num_planche = request('num_planche');
            $histAssemblage->save();
        }
        if(request('type_travaille') != ''){
            $histSertissage = HistSertissage::where('hist_id', '=', $id)->first();
            $histSertissage->type_travaille = request('type_travaille');
            $histSertissage->save();
        }
        if(request('nom_support') != ''){
            $histElectrique = HistElectrique::where('hist_id', '=', $id)->first();
            $histElectrique->nom_support = request('nom_support');
            $histElectrique->save();
        }
        
        return $this->refreshTech();
    }
    public function edit($id)
    {
        $hist = Historique::where('id','=',$id)->get();
        $codePanne = CodePanneInHist::where('hist', '=', $id)->get();
        $hist->push($codePanne);
        if($hist[0]->zone == 'Assemblage'){
            $histAssemblage = HistAssemblage::where('historique_id', '=', $id)->get();
            $hist->push($histAssemblage);
        }
        if($hist[0]->zone == 'Sertissage'){
            $histSertissage = HistSertissage::where('hist_id', '=', $id)->get();
            $hist->push($histSertissage); 
        }
        if($hist[0]->zone == 'Controle éléctrique'){
            $histElectrique = HistElectrique::where('hist_id', '=', $id)->get();
            $hist->push($histElectrique); 
        }
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
        $techId = $hist->tech_id;
        
        $technicien = Technicien::where('user_id','=',$techId)->first();
        $technicien->status = 'DISPONIBLE';
        $technicien->save();
        
        $technicien = Technicien::where('user_id','=',request('tech_id'))->first();
        $technicien->status = 'NON DISPONIBLE';
        $technicien->save();

        $hist->tech_id = request('tech_id');
        if(Auth::user()->role == "ADMIN"){
            if(request('heure_fin') != '' 
                && request('travaille') != '' 
                && request('piece_rechange') != '' 
                && request('commentaire') != '' 
                && request('code_equip') != '')
            { 
                $hist->valide = true; 
            }
            $hist->heure_fin = request('heure_fin');
            $hist->heure_debut = Carbon::now('Africa/Tunis');
            $hist->travaille = request('travaille');
            $hist->piece_rechange = request('piece_rechange');
            $hist->commentaire = request('commentaire');
            $hist->code_equip = request('code_equip');
            if(request('code_panne') != null){
                $codePanneToDelete = CodePanneInHist::where('hist', '=', $id)->get();
                foreach($codePanneToDelete as $item){
                    $item->delete();
                }
                $codePanne = request('code_panne');
                foreach($codePanne as $item){
                    $cp = new CodePanneInHist();
                    $cp->hist = $id;
                    $cp->code_panne = $item;
                    $cp->save();
                }
            }
            if(request('num_planche') != ''){
                $histAssemblage = HistAssemblage::where('historique_id', '=', $id)->first();
                $histAssemblage->num_planche = request('num_planche');
                $histAssemblage->save();
            }
            if(request('type_travaille') != ''){
                $histSertissage = HistSertissage::where('hist_id', '=', $id)->first();
                $histSertissage->type_travaille = request('type_travaille');
                $histSertissage->save();
            }
            if(request('nom_support') != ''){
                $histElectrique = HistElectrique::where('hist_id', '=', $id)->first();
                $histElectrique->nom_support = request('nom_support');
                $histElectrique->save();
            }
        }
        $hist->save();

        if(Auth::user()->role == 'HOTLINE'){
            return $this->getHotlineHistoriques();
        }
        return $this->refresh();
        
    }

    public function destroy($id)
    {
        $hist = Historique::find($id);
        $hist->delete();
        $technicien = Technicien::where('user_id','=',$hist->tech_id)->first();
        $technicien->status = 'DISPONIBLE';
        $technicien->save();
        
        if(Auth::user()->role == 'HOTLINE'){
            return $this->getHotlineHistoriques();
        }
        return $this->refresh();

    }
    
    public function refresh(){
        $historiques = Historique::paginate(5);
        return response()->json($historiques);
    }

    public function refreshTech(){
        $historiques = Historique::where('tech_id','=',Auth::id())->where('valide','=',false)->paginate(10);
        return response()->json($historiques);
    }
    public function getHotlineHistoriques(){
        $hist = Historique::where('hotline_id', '=', Auth::id())->paginate(10);
        return response()->json($hist);
    }
    public function getTechniciens($zone){
        $techniciens =  Technicien::with('user')
        ->where('zone','=',$zone)
        ->where('status', '=', 'DISPONIBLE')
        ->get();
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
    public function getUserId(){
        return response()->json(Auth::id());
    }
    public function getAllTechs(){
        $techs = User::where('role', '=', 'TECHNICIEN')->get();
        return response()->json($techs);
    }

}
