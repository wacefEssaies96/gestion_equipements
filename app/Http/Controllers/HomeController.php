<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Technicien;
use App\Hotline;
use App\Responsable;
use App\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('app');
    }
    public function getCount(){
        $nbTech = Technicien::all()->count();
        $nbHotline = Hotline::all()->count();
        $nbResp = Responsable::all()->count();
        $nbProd = Responsable::all()->count();
        return response()->json([$nbHotline,$nbResp,$nbTech,$nbProd]);
    }
    public function welcome()
    {
        $viewData = $this->loadViewData();
        return response()->json($viewData);
    }
}
