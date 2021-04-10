<?php

namespace App\Http\Controllers;

use App\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function liste(Request $request)
    {
        return $this->refresh();
    }
    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'designation' => 'required',
        ]);
        $zone = new Zone();
        $zone->designation = request('designation');
        $zone->save();
        return $this->refresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        //
    }

   
    public function edit($id)
    {
        $zone = Zone::find($id);
        return response()->json($zone );
    }

    
    public function update($id)
    {
        $zone = Zone::find($id);
        $zone->designation = request('designation');
        $zone->save();
        return $this->refresh();
    }

    
    public function destroy($id)
    {
        $zone = Zone::find($id);
        if($zone->delete()){
            return $this->refresh();
        }else{
            return response()->json(['erreur'=>'marche pas']);
        }
    }
    public function refresh(){
        $zone = Zone::paginate(10);
        return response()->json($zone );
    }
    
}
