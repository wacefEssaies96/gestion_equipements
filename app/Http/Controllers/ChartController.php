<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipement;
use App\Technicien;
use App\Historique;
use App\CodePanne;
use App\CodePanneInHist;
use App\Exports\DureeArretExport;
use Excel;

class ChartController extends Controller
{
    public function export(Request $request){
        $export = $request->data;
        return Excel::download(new DureeArretExport($export), 'dureearret.xlsx');
    }
    public function countEquipement(Request $request){

        $data = [];
        $labels = [];
        $counts = [];
        $codes = [];
        $count = [];
        
        $equip = Equipement::select('id','zone' ,'code','desi_cat', 'designation')
        ->where('zone', '=', $request->zone)
        ->where('desi_cat', '=', $request->categorie)
        ->orderBy('zone','asc')
        ->orderBy('desi_cat','asc')
        ->get();

        for($i = 0 ; $i < count($equip) ; $i++){

            array_push($labels,$equip[$i]->code);
            array_push($counts,[]);
            $codepannes = [];

            $hist = Historique::select('id')->where('code_equip', '=', $equip[$i]->id)->get();
           
            foreach($hist as $item){
                $cp = CodePanneInHist::
                select('code_panne')
                ->where('hist', '=', $item->id)
                ->get();
                array_push($counts[$i],$cp);
            }
            
            for($j = 0 ; $j < count($counts[$i]) ; $j++){
                for($k = 0 ; $k < count($counts[$i][$j]) ; $k++){
                    array_push($codepannes,$counts[$i][$j][$k]->code_panne);
                }
            }
            sort($codepannes);
            $counts[$i] = [];
            array_push($counts[$i],$codepannes);

            if(!empty($counts[$i][0])){
                $values = array_count_values($counts[$i][0]);
                arsort($values);
                $popular = array_slice($values, 0, 1, true);
                $id = array_keys($popular);

                $code = CodePanne::where('id','=',$id)->first();
                foreach($popular as $item){
                    array_push($codes,$code->designation);
                    array_push($count,$item);
                }
            }else{
                array_push($codes,'NULL');
                array_push($count,0);
            }
        }
        
        array_push($data,$labels);
        array_push($data,$codes);
        array_push($data,$count);
        array_push($data,"Code panne le plus fréquent ");  
        return response()->json($data);

    }
    public function countCategorie(Request $request){
        $data = [];
        $labels = [];
        $counts = [];
        
        $hist = Equipement::select('zone' ,'desi_cat', 'designation')
        ->where('zone', '=', $request->zone)
        ->orderBy('zone','asc')
        ->orderBy('desi_cat','asc')
        ->get();
        for($i = 0 ; $i < count($hist) ; $i++){
            if ($i == 0){
                array_push($labels,$hist[$i]->desi_cat);
                array_push($counts,1); 
            }
            else{
                if($hist[$i-1]->desi_cat == $hist[$i]->desi_cat){
                    // if($hist[$i-1]->designation != $hist[$i]->designation){
                        $counts[count($counts)-1] += 1;
                    // }
                } 
                else{
                    array_push($labels,$hist[$i]->desi_cat);
                    array_push($counts,1);
                }
            }
        }

        array_push($data,$labels);
        array_push($data,$counts);
        array_push($data,"Nombre des équipements dans la zone ".$request->zone);  
        return response()->json($data);
    }
    public function nbrCategorieZone(){
        $data = [];
        $labels = [];
        $counts = [];
        
        $hist = Equipement::select('zone' ,'desi_cat')->orderBy('zone','asc')->orderBy('desi_cat','asc')->get();

        for($i = 0 ; $i < count($hist) ; $i++){
            if ($i == 0){
                array_push($labels,$hist[$i]->zone);
                array_push($counts,1); 
            }
            else{
                if($hist[$i-1]->zone == $hist[$i]->zone){
                    if($hist[$i-1]->desi_cat != $hist[$i]->desi_cat){
                        $counts[count($counts)-1] += 1;
                    }
                } 
                else{
                    array_push($labels,$hist[$i]->zone);
                    array_push($counts,1);
                }
            }
        }

        array_push($data,$labels);
        array_push($data,$counts);
        array_push($data,"Nombre de categorie par zone");  
        return response()->json($data);
    }

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
