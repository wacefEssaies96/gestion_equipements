<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Technicien;
use App\Hotline;
use App\Responsable;

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
        $nbTech = Technicien::all()->count();
        $nbHotline = Hotline::all()->count();
        $nbResp = Responsable::all()->count();
        return view('pages.dashboard',[
            'nbTech' => $nbTech,
            'nbHotline' => $nbHotline,
            'nbResp' => $nbResp
        ]);
    }
}
