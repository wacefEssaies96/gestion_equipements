<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipement;
use App\Technicien;
use App\Historique;
use App\CodePanne;
use App\CodePanneInHist;

class ChartController extends Controller
{
    public function nbrArretZone(Request $request){
        $data = [];
        $assemblage = Historique::where('zone','=','Assemblage')->count();
        $sertissage = Historique::where('zone','=','Sertissage')->count();
        $ce = Historique::where('zone','=','Controle éléctrique')->count();
        $coupe = Historique::where('zone','=','Coupe')->count();
        $preparation = Historique::where('zone','=','Préparation')->count();
        array_push($data,$assemblage);
        array_push($data,$sertissage);
        array_push($data,$ce);
        array_push($data,$coupe);
        array_push($data,$preparation);
        return response()->json($data);
    }
    public function nbrArret(Request $request){
        $data = [];
        $labels = [];
        $counts = [];
        if($request->type == 'equipement'){
            $hist = Historique::
            join('equipements', 'historiques.code_equip', '=', 'equipements.id')
            ->select('equipements.code')
            ->orderBy('equipements.code','asc')
            ->get();
            for($i = 0 ; $i < count($hist) ; $i++){
                if ($i == 0){
                    array_push($labels,$hist[$i]->code);
                    array_push($counts,1); 
                }
                else{
                    if($hist[$i-1] == $hist[$i]){
                        $counts[count($counts)-1] += 1;
                    } 
                    else{
                        array_push($labels,$hist[$i]->code);
                        array_push($counts,1);
                    }
                }
            }
        }
        if($request->type == 'categorie'){
            $hist = Historique::
            join('equipements', 'historiques.code_equip', '=', 'equipements.id')
            ->select('equipements.desi_cat')
            ->orderBy('equipements.desi_cat','asc')
            ->get();
            for($i = 0 ; $i < count($hist) ; $i++){
                if ($i == 0){
                    array_push($labels,$hist[$i]->desi_cat);
                    array_push($counts,1); 
                }
                else{
                    if($hist[$i-1] == $hist[$i]){
                        $counts[count($counts)-1] += 1;
                    } 
                    else{
                        array_push($labels,$hist[$i]->desi_cat);
                        array_push($counts,1);
                    }
                }
            }
        }
        if($request->type == 'codePanne'){
            $hist = CodePanneInHist::
            join('code_pannes', 'code_panne_in_hists.code_panne', '=', 'code_pannes.id')
            ->select('code_pannes.designation')
            ->orderBy('code_pannes.designation','asc')
            ->get();
            for($i = 0 ; $i < count($hist) ; $i++){
                if ($i == 0){
                    array_push($labels,$hist[$i]->designation);
                    array_push($counts,1); 
                }
                else{
                    if($hist[$i-1] == $hist[$i]){
                        $counts[count($counts)-1] += 1;
                    } 
                    else{
                        array_push($labels,$hist[$i]->designation);
                        array_push($counts,1);
                    }
                }
            }
        }
        array_push($data,$labels);
        array_push($data,$counts);  
        return response()->json($data);
    }

    public function dureeArretZone(Request $request){
        $data = [];
        $labels = [];
        $counts = [];
        $values = [];
        $hist = Historique::orderBy('zone','asc')->get();

        for($i = 0 ; $i < count($hist) ; $i++){
            if ($i == 0){
                array_push($labels,$hist[$i]->zone);
                array_push($counts,strtotime($hist[$i]->heure_arret)); 
            }
            else{
                if($hist[$i-1]->zone == $hist[$i]->zone){
                    $counts[count($counts)-1] += strtotime($hist[$i]->heure_arret);
                } 
                else{
                    array_push($labels,$hist[$i]->zone);
                    array_push($counts,strtotime($hist[$i]->heure_arret));
                }
            }
        }
        foreach($counts as $item){
            $item = date("H:i:s", $item);
            $item = explode(':',$item);
            $item[0] = $item[0]*60;
            $item[2] = $item[2]/60;
            $item = array_sum($item);
            array_push($values,$item);
        }

        array_push($data,$labels);
        array_push($data,$values);
        return response()->json($data);
    }
    public function dureeArret(Request $request){
        $data = [];
        $labels = [];
        $counts = [];
        $values = [];
        if($request->type == 'equipement'){
            $hist = Historique::
            join('equipements', 'historiques.code_equip', '=', 'equipements.id')
            ->select('equipements.code', 'historiques.heure_arret')
            ->orderBy('equipements.code','asc')
            ->get();
            for($i = 0 ; $i < count($hist) ; $i++){
                if ($i == 0){
                    array_push($labels,$hist[$i]->code);
                    array_push($counts,strtotime($hist[$i]->heure_arret)); 
                }
                else{
                    if($hist[$i-1]->code == $hist[$i]->code){
                        $counts[count($counts)-1] += strtotime($hist[$i]->heure_arret);
                    } 
                    else{
                        array_push($labels,$hist[$i]->code);
                        array_push($counts,strtotime($hist[$i]->heure_arret));
                    }
                }
            }
        }
        if($request->type == 'categorie'){
            $hist = Historique::
            join('equipements', 'historiques.code_equip', '=', 'equipements.id')
            ->select('equipements.desi_cat', 'historiques.heure_arret')
            ->orderBy('equipements.desi_cat','asc')
            ->get();
            for($i = 0 ; $i < count($hist) ; $i++){
                if ($i == 0){
                    array_push($labels,$hist[$i]->desi_cat);
                    array_push($counts,strtotime($hist[$i]->heure_arret)); 
                }
                else{
                    if($hist[$i-1]->desi_cat == $hist[$i]->desi_cat){
                        $counts[count($counts)-1] += strtotime($hist[$i]->heure_arret);
                    } 
                    else{
                        array_push($labels,$hist[$i]->desi_cat);
                        array_push($counts,strtotime($hist[$i]->heure_arret));
                    }
                }
            }
        }
        if($request->type == 'codePanne'){
            $hist = CodePanneInHist::
            join('code_pannes', 'code_panne_in_hists.code_panne', '=', 'code_pannes.id')
            ->join('historiques', 'code_panne_in_hists.hist', '=', 'historiques.id')
            ->select('code_pannes.designation' , 'historiques.heure_arret')
            ->orderBy('code_pannes.designation','asc')
            ->get();
            for($i = 0 ; $i < count($hist) ; $i++){
                if ($i == 0){
                    array_push($labels,$hist[$i]->designation);
                    array_push($counts,strtotime($hist[$i]->heure_arret)); 
                }
                else{
                    if($hist[$i-1]->designation == $hist[$i]->designation){
                        $counts[count($counts)-1] += strtotime($hist[$i]->heure_arret);
                    } 
                    else{
                        array_push($labels,$hist[$i]->designation);
                        array_push($counts,strtotime($hist[$i]->heure_arret));
                    }
                }
            }
        }
        foreach($counts as $item){
            $item = date("H:i:s", $item);
            $item = explode(':',$item);
            $item[0] = $item[0]*60;
            $item[2] = $item[2]/60;
            $item = array_sum($item);
            array_push($values,$item);
        }
        array_push($data,$labels);
        array_push($data,$values);
        return response()->json($data);
    }
}
