<?php

use Illuminate\Support\Facades\Route;


// Admin Authentification
// Route::group(['middleware' => ['auth' , 'ADMIN']],function() {
    Route::get('/', 'HomeController@index');
    //onedrive
    // Route::get('/onedrive', function(){
    //     return redirect('/')
    // });
    Route::get('/onedrive', function()
    {
        return redirect('/');
    });
    Route::get('/getAccessData', 'HomeController@welcome');
    Route::get('/signin', 'AuthController@signin');
    Route::get('/callback', 'AuthController@callback');
    Route::get('/signout', 'AuthController@signout');
    Route::get('/getalldata', 'OneDriveController@getAllData');
    Route::get('/getdatabyid/{id}', 'OneDriveController@getDataById');
    Route::get('/download/data/{id}', 'OneDriveController@downloadEquipementData');
    Route::post('/importDataFromExcel','EquipementController@importDataFromExcel');
    
    //Responsable
    Route::get('/responsables/verifemail/{value}', 'ResponsableController@verifEmail');
    // Route::get('/responsables', 'ResponsableController@index');
    Route::post('/responsables', 'ResponsableController@store');
    Route::get('/responsables/liste', 'ResponsableController@liste');
    Route::get('/responsables/edit/{id}', 'ResponsableController@edit');
    Route::patch('/responsables/edit/{id}', 'ResponsableController@update');
    Route::delete('/responsables/{id}', 'ResponsableController@destroy');

    //Hotline
    Route::post('/users/search', 'UserController@searchuser');
    // Route::get('/hotlines', 'HotlineController@index');
    Route::post('/hotlines', 'HotlineController@store');
    Route::get('/hotlines/liste', 'HotlineController@liste');
    Route::get('/hotlines/edit/{id}', 'HotlineController@edit');
    Route::patch('/hotlines/edit/{id}', 'HotlineController@update');
    Route::delete('/hotlines/{id}', 'HotlineController@destroy');

    //Technicien
    // Route::get('/techniciens', 'TechnicienController@index');
    Route::post('/techniciens', 'TechnicienController@store');
    Route::get('/techniciens/liste', 'TechnicienController@liste');
    Route::get('/techniciens/edit/{id}', 'TechnicienController@edit');
    Route::patch('/techniciens/edit/{id}', 'TechnicienController@update');
    Route::delete('/techniciens/{id}', 'TechnicienController@destroy');

    //Equipement
    // Route::get('/equipements', 'EquipementController@index');
    Route::post('/equipements', 'EquipementController@store');
    Route::post('/equipements/liste', 'EquipementController@liste');
    Route::get('/equipements/edit/{id}', 'EquipementController@edit');
    Route::patch('/equipements/edit/{id}', 'EquipementController@update');
    Route::delete('/equipements/{id}', 'EquipementController@destroy');
    Route::get('/equipements/{value}', 'EquipementController@getEquipementByCode');

    //Code Panne
    // Route::get('/code_pannes', 'CodePanneController@index');
    Route::post('/code_pannes', 'CodePanneController@store');
    Route::post('/code_pannes/liste', 'CodePanneController@liste');
    Route::get('/code_pannes/edit/{id}', 'CodePanneController@edit');
    Route::patch('/code_pannes/edit/{id}', 'CodePanneController@update');
    Route::delete('/code_pannes/{id}', 'CodePanneController@destroy');

// });
// Route::group(['middleware'=>['auth']], function(){

    //HitoriqueTech
    Route::get('/hist/tech/liste', 'HistoriqueController@listeTech');
    Route::patch('/histtech/edit/{id}', 'HistoriqueController@updatefortech');
    Route::get('/historiques/equipement/zone/{zone}', 'HistoriqueController@getEquipements');
    Route::get('/historiques/code-panne/{zone}', 'HistoriqueController@getCodePannes');

    //Historique /historiques/tech
    Route::get('/historiques/ht','HistoriqueController@getUserId');
    Route::get('/historiques/techniciens/{zone}', 'HistoriqueController@getTechniciens');
    Route::get('/historiques/techs', 'HistoriqueController@getAllTechs');
    Route::get('/historiques/hotline', 'HistoriqueController@getHotlineHistoriques');


    // Route::get('/historiques', 'HistoriqueController@index');
    Route::post('/historiques', 'HistoriqueController@store');
    Route::post('/historiques/liste', 'HistoriqueController@liste');
    Route::get('/historiques/edit/{id}', 'HistoriqueController@edit');
    Route::patch('/historiques/edit/{id}', 'HistoriqueController@update');
    Route::delete('/historiques/{id}', 'HistoriqueController@destroy');

// });

//getCodePanneByCode
Route::get('/code_pannes/{value}', 'CodePanneController@getCodePanneByCode');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
