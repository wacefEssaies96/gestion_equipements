<?php

use Illuminate\Support\Facades\Route;


//Authentification
Route::group(['middleware' => ['auth' , 'ADMIN']],function() {
    Route::get('/', function(){
        return view('dashboard');
    });
  
});

//Responsable
Route::get('/responsables', 'ResponsableController@index');
Route::post('/responsables', 'ResponsableController@store');
Route::get('/responsables/liste', 'ResponsableController@liste');
Route::get('/responsables/edit/{id}', 'ResponsableController@edit');
Route::patch('/responsables/edit/{id}', 'ResponsableController@update');
Route::delete('/responsables/{id}', 'ResponsableController@destroy');

//Hotline
Route::get('/hotlines', 'HotlineController@index');
Route::post('/hotlines', 'HotlineController@store');
Route::get('/hotlines/liste', 'HotlineController@liste');
Route::get('/hotlines/edit/{id}', 'HotlineController@edit');
Route::patch('/hotlines/edit/{id}', 'HotlineController@update');
Route::delete('/hotlines/{id}', 'HotlineController@destroy');

//Technicien
Route::get('/techniciens', 'TechnicienController@index');
Route::post('/techniciens', 'TechnicienController@store');
Route::get('/techniciens/liste', 'TechnicienController@liste');
Route::get('/techniciens/edit/{id}', 'TechnicienController@edit');
Route::patch('/techniciens/edit/{id}', 'TechnicienController@update');
Route::delete('/techniciens/{id}', 'TechnicienController@destroy');

//Equipement
Route::get('/equipements', 'EquipementController@index');
Route::post('/equipements', 'EquipementController@store');
Route::get('/equipements/liste', 'EquipementController@liste');
Route::get('/equipements/edit/{id}', 'EquipementController@edit');
Route::patch('/equipements/edit/{id}', 'EquipementController@update');
Route::delete('/equipements/{id}', 'EquipementController@destroy');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
