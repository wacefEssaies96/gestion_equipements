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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('app');
    }
    public function getCount(){
        $nbTech = Technicien::all()->count();
        $nbHotline = Hotline::all()->count();
        $nbResp = Responsable::all()->count();
        return response()->json([$nbHotline,$nbResp,$nbTech]);
    }
    public function welcome()
    {
        $viewData = $this->loadViewData();

        return response()->json($viewData);
    }
}
