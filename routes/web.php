<?php

use Illuminate\Support\Facades\Route;


// Admin Authentification
// Route::group(['middleware' => ['auth' , 'ADMIN']],function() {
    
    Route::get('/', 'HomeController@index');
    Route::get('/getCount', 'HomeController@getCount');
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
    Route::post('/responsables', 'ResponsableController@store');
    Route::get('/responsables/liste', 'ResponsableController@liste');
    Route::get('/responsables/edit/{id}', 'ResponsableController@edit');
    Route::patch('/responsables/edit/{id}', 'ResponsableController@update');
    Route::delete('/responsables/{id}', 'ResponsableController@destroy');

    //Production
    Route::post('/productions', 'ProductionController@store');
    Route::get('/productions/liste', 'ProductionController@liste');
    Route::get('/productions/edit/{id}', 'ProductionController@edit');
    Route::patch('/productions/edit/{id}', 'ProductionController@update');
    Route::delete('/productions/{id}', 'ProductionController@destroy');

    //Feedback
    Route::post('/feedbacks', 'FeedbackController@store');
    Route::get('/feedbacks/liste', 'FeedbackController@liste');
    Route::get('/feedbacks/edit/{id}', 'FeedbackController@edit');
    Route::patch('/feedbacks/edit/{id}', 'FeedbackController@update');
    Route::delete('/feedbacks/{id}', 'FeedbackController@destroy');
    

    //Hotline
    Route::post('/users/search', 'UserController@searchuser');
    Route::post('/hotlines', 'HotlineController@store');
    Route::get('/hotlines/liste', 'HotlineController@liste');
    Route::get('/hotlines/edit/{id}', 'HotlineController@edit');
    Route::patch('/hotlines/edit/{id}', 'HotlineController@update');
    Route::delete('/hotlines/{id}', 'HotlineController@destroy');

    //Technicien
    Route::post('/techniciens', 'TechnicienController@store');
    Route::get('/techniciens/liste', 'TechnicienController@liste');
    Route::get('/techniciens/edit/{id}', 'TechnicienController@edit');
    Route::patch('/techniciens/edit/{id}', 'TechnicienController@update');
    Route::delete('/techniciens/{id}', 'TechnicienController@destroy');

    //Equipement
    Route::post('/equipements', 'EquipementController@store');
    Route::post('/equipements/liste', 'EquipementController@liste');
    Route::get('/equipements/edit/{id}', 'EquipementController@edit');
    Route::patch('/equipements/edit/{id}', 'EquipementController@update');
    Route::delete('/equipements/{id}', 'EquipementController@destroy');
    Route::get('/equipements/{value}', 'EquipementController@getEquipementByCode');

    //Code Panne
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
