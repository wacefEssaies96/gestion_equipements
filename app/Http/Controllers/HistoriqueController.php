<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Notification;

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
use App\Notifications\HistoriqueAdded;
use App\Notifications\TechConfirmedHist;
use App\Notifications\TechEditedHist;
use App\Notifications\AppelleNonCloture;

class HistoriqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listeTech(){
        return $this->refreshTech();
    }

    public function liste(Request $request)
    {
        $hist = Historique::where('historiques.id','like','%'.request('num_bt').'%');
        $hist = $hist->join('equipements','historiques.code_equip', '=', 'equipements.id')
        ->select('historiques.*','equipements.designation','equipements.n_serie','equipements.emplacement','equipements.code_categorie','equipements.code');
        if($request->zone != ''){
            $hist = $hist->where('historiques.zone', '=', request('zone'));
        }
        if($request->appelle != ''){
            $hist = $hist->where('historiques.appelle', '=', request('appelle'));
        }
        if($request->tech_id != null){
            $hist = $hist->where('historiques.tech_id', '=', request('tech_id'));
        }
        if($request->date_debut != ''){
            $hist = $hist->whereTime('historiques.heure_debut', request('date_debut'));
        }
        if($request->date_fin != ''){
            $hist = $hist->whereTime('historiques.heure_fin', request('date_fin'));
        }
        if($request->code_categorie != ''){
            $hist = $hist->where('equipements.code_categorie','like', '%'.request('code_categorie').'%');
        }
        if($request->code_equip != ''){
            $hist = $hist->where('equipements.code','like','%'.request('code_equip').'%');
        }
       
        $hist = $hist->paginate(10);
        return response()->json($hist);
    }
    public function listeHotline(Request $request)
    {
        $hist = Historique::where('historiques.id','like','%'.request('num_bt').'%');
        $hist = $hist->join('equipements','historiques.code_equip', '=', 'equipements.id')
        ->select('historiques.*','equipements.designation','equipements.n_serie','equipements.emplacement','equipements.code_categorie','equipements.code')
        ->where('hotline_id', '=', Auth::id());
        if($request->zone != ''){
            $hist = $hist->where('historiques.zone', '=', request('zone'));
        }
        if($request->appelle != ''){
            $hist = $hist->where('historiques.appelle', '=', request('appelle'));
        }
        if($request->tech_id != null){
            $hist = $hist->where('historiques.tech_id', '=', request('tech_id'));
        }
        if($request->date_debut != ''){
            $hist = $hist->whereTime('historiques.heure_debut', request('date_debut'));
        }
        if($request->date_fin != ''){
            $hist = $hist->whereTime('historiques.heure_fin', request('date_fin'));
        }
        if($request->code_categorie != ''){
            $hist = $hist->where('equipements.code_categorie','like', '%'.request('code_categorie').'%');
        }
        if($request->code_equip != ''){
            $hist = $hist->where('equipements.code','like','%'.request('code_equip').'%');
        }
        
        $hist = $hist->paginate(10);
        return response()->json($hist);
    }

    public function store(Request $request)
    {
        $hist = new Historique();
        $hist->heure_demande = request('heure_demande');
        $hist->hotline_id = Auth::id();
        $hist->zone = request('zone');
        $hist->jour = Carbon::now('Africa/Tunis');
        $hist->appelle = 'Non cloturé';
        $hist->tech_id = request('tech_id');
        $hist->code_equip = request('code_equip');
        $hist->description_demande = request('description_demande');
        $hist->type_travaille = request('type_travaille');
        $hist->valide = false;

        if(Auth::user()->role == 'ADMIN') {
            if(request('travaille') != '' && request('piece_rechange') != ''){ 
                $hist->appelle = 'Cloturé';
                $hist->valide = true; 
                $hist->heure_fin = Carbon::now('Africa/Tunis');
                $hist->heure_debut = Carbon::now('Africa/Tunis');
                $hist->travaille = request('travaille');
                $hist->piece_rechange = request('piece_rechange');
            }
        }
        $hist->save();
        
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
            $histAssemblage->hist_id = $hist->id;
            $histAssemblage->save();
        }
        if($hist->zone == 'Controle éléctrique'){
            $histElectrique = new HistElectrique();
            $histElectrique->nom_support = request('nom_support');
            $histElectrique->hist_id = $hist->id;
            $histElectrique->save();
        }
        
        $technicien = User::find(request('tech_id'));
        Notification::send($technicien, new HistoriqueAdded($hist));

        $admin = User::where('role', '=' ,'ADMIN')->first();
        Notification::send($admin,new AppelleNonCloture($hist));

        $hotline = User::
        where('id', '=', $hist->hotline_id)
        ->first();
        Notification::send($hotline,new AppelleNonCloture($hist));

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
        $hist->heure_fin = Carbon::now('Africa/Tunis');
        $heure_arret = strtotime($hist->heure_fin) - strtotime($hist->heure_debut);
        $hist->heure_arret = date("H:i:s",$heure_arret);
        
        $hist->travaille = request('travaille');
        $hist->piece_rechange = request('piece_rechange');
        $hist->appelle = 'Cloturé';
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
        if(request('nom_support') != ''){
            $histElectrique = HistElectrique::where('hist_id', '=', $id)->first();
            $histElectrique->nom_support = request('nom_support');
            $histElectrique->save();
        }
        $users = User::where('role', '=','ADMIN')->first();
        Notification::send($users, new TechEditedHist($hist));

        $users = User::where('id', '=', $hist->hotline_id)->first();
        Notification::send($users, new TechEditedHist($hist));
        
        return $this->refreshTech();
    }
    public function confirmAppelle($id){
        $hist = Historique::find($id);
        if($hist->heure_debut == null){
            $hist->heure_debut = Carbon::now('Africa/Tunis');
            $heure_attente = strtotime($hist->heure_debut) - strtotime($hist->heure_demande);
            $hist->heure_attente = date("H:i:s",$heure_attente);
            $hist->valide = true;
            $hist->save();
            $userId = $hist->tech_id;
            $technicien = Technicien::where('user_id', '=', $userId)->first();
            $technicien->status = 'NON DISPONIBLE';
            $technicien->save();

            $users = User::where('role', '=','ADMIN')->first();
            Notification::send($users, new TechConfirmedHist($hist));
            
            $users = User::where('id', '=', $hist->hotline_id)->first();
            Notification::send($users, new TechConfirmedHist($hist));

            return $this->refreshTech();
        }
        return response()->json('erreur');
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
        $hist->heure_demande = request('heure_demande');
        $hist->hotline_id = Auth::id();
        $hist->zone = request('zone');
        $hist->code_equip = request('code_equip');
        $hist->description_demande = request('description_demande');
        $hist->type_travaille = request('type_travaille');

        $techId = $hist->tech_id;

        $technicien = Technicien::where('user_id','=',$techId)->first();
        $technicien->status = 'DISPONIBLE';
        $technicien->save();

        $hist->tech_id = request('tech_id');
        if(Auth::user()->role == "ADMIN"){ 
            $hist->appelle = request('appelle');
            if( request('travaille') != '' && request('piece_rechange') != ''){ 
                $hist->valide = true; 
                $hist->heure_fin = Carbon::now('Africa/Tunis');
                $hist->heure_debut = Carbon::now('Africa/Tunis');
                $hist->travaille = request('travaille');
                $hist->piece_rechange = request('piece_rechange');
            }
            
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
        $historiques = Historique::join('equipements','historiques.code_equip', '=', 'equipements.id')
        ->select('historiques.*','equipements.designation','equipements.n_serie','equipements.emplacement','equipements.code_categorie','equipements.code')
        ->paginate(10);
        return response()->json($historiques);
    }

    public function refreshTech(){
        $historiques = Historique::join('equipements','historiques.code_equip', '=', 'equipements.id')
        ->select('historiques.*','equipements.designation','equipements.n_serie','equipements.emplacement','equipements.code_categorie','equipements.code')
        ->where('tech_id','=',Auth::id())->where('heure_fin','=',null)->paginate(10);
        return response()->json($historiques);
    }
    public function getHotlineHistoriques(){
        $hist = Historique::join('equipements','historiques.code_equip', '=', 'equipements.id')
        ->select('historiques.*','equipements.designation','equipements.n_serie','equipements.emplacement','equipements.code_categorie','equipements.code')
        ->where('hotline_id', '=', Auth::id())->paginate(10);
        return response()->json($hist);
    }

    public function getHistSertissage($id){
        $hist = HistSertissage::where('hist_id','=',$id)->first();
        return response()->json($hist);
    }
    
}
