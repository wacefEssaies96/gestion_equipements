<?php

use Illuminate\Support\Facades\Route;
use App\User;

    Route::get('/', 'HomeController@index');
    Route::get('/getCount', 'HomeController@getCount');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/onedrive', function()
    {
        return redirect('/#/equipements');
    });
    Route::get('/mark-as-read/{user}', function(User $user)
    {
        $user->unreadNotifications->markAsRead();
        return response()->json('Marked as read');
    });

    //chat
    Route::get('/contacts', 'MessageController@get');
    Route::get('/conversation/{id}', 'MessageController@getConversation');
    Route::post('/conversation/send', 'MessageController@send');

    //desactiver un user
    Route::get('/users/{id}/enable', 'UserController@enable');
    Route::get('/users/{id}/disable', 'UserController@disable');

    //users
    Route::get('/users', 'UserController@liste');
    Route::post('/users', 'UserController@store');
    Route::get('/users/edit/{id}', 'UserController@edit');
    Route::delete('/users/{id}', 'UserController@destroy');
    Route::patch('/users/edit/{id}', 'UserController@update');
    Route::get('/users/verifcode/{value}', 'UserController@verifCode');

    Route::get('/historiques/techniciens/{zone}', 'UserController@getTechniciens');
    Route::get('/historiques/hotline', 'HistoriqueController@getHotlineHistoriques');
    Route::get('/historiques/techs', 'UserController@getAllTechs');
    Route::get('/users/verifemail/{value}', 'UserController@verifEmail');
    Route::post('/users/search', 'UserController@searchuser');
    Route::get('/historiques/ht','UserController@getUserId');

    Route::get('/getAccessData', 'HomeController@welcome');
    Route::get('/signin', 'AuthController@signin');
    Route::get('/callback', 'AuthController@callback');
    Route::get('/signout', 'AuthController@signout');
    Route::get('/getalldata', 'OneDriveController@getAllData');
    Route::get('/getdatabyid/{id}', 'OneDriveController@getDataById');
    Route::get('/download/data/{id}', 'OneDriveController@downloadEquipementData');
    Route::get('/download/file/{id}/{file}', 'OneDriveController@downloadFile');
    Route::post('/importDataFromExcel','EquipementController@importDataFromExcel');

    //Zone
    Route::post('/zones', 'ZoneController@store');
    Route::post('/zones/liste', 'ZoneController@liste');
    Route::get('/zones/edit/{id}', 'ZoneController@edit');
    Route::patch('/zones/edit/{id}', 'ZoneController@update');
    Route::delete('/zones/{id}', 'ZoneController@destroy');
 
    //Feedback
    Route::post('/feedbacks', 'FeedbackController@store');
    Route::get('/feedbacks/liste', 'FeedbackController@liste');
    Route::get('/feedbacks/edit/{id}', 'FeedbackController@edit');
    Route::patch('/feedbacks/edit/{id}', 'FeedbackController@update');
    Route::delete('/feedbacks/{id}', 'FeedbackController@destroy');

    //Equipement
    Route::get('equipements/export', 'EquipementController@export');
    Route::post('/equipements/excel', 'EquipementController@storeFromFile');
    Route::post('/equipements', 'EquipementController@store');
    Route::post('/equipements/liste', 'EquipementController@liste');
    Route::get('/equipements/edit/{id}', 'EquipementController@edit');
    Route::patch('/equipements/edit/{id}', 'EquipementController@update');
    Route::delete('/equipements/{id}', 'EquipementController@destroy');

    //Code Panne
    Route::get('code-pannes/export', 'CodePanneController@export');
    Route::post('/code-pannes/excel', 'CodePanneController@storeFromFile');
    Route::post('/code_pannes', 'CodePanneController@store');
    Route::get('/code_pannes/liste-all', 'CodePanneController@getAllCp');
    Route::post('/code_pannes/liste', 'CodePanneController@liste');
    Route::get('/code_pannes/edit/{id}', 'CodePanneController@edit');
    Route::patch('/code_pannes/edit/{id}', 'CodePanneController@update');
    Route::delete('/code_pannes/{id}', 'CodePanneController@destroy');

    //Feedback
    Route::post('/feedbacks', 'FeedbackController@store');
    Route::get('/feedbacks/liste', 'FeedbackController@liste');
    Route::get('/feedbacks/edit/{id}', 'FeedbackController@edit');
    Route::patch('/feedbacks/edit/{id}', 'FeedbackController@update');
    Route::delete('/feedbacks/{id}', 'FeedbackController@destroy');

    //Hitorique
    Route::get('/hist/tech/liste', 'HistoriqueController@listeTech');  
    Route::post('/historiques/liste', 'HistoriqueController@liste');
    Route::post('/historiques/liste/hotline', 'HistoriqueController@listeHotline'); 
    Route::post('/historiques', 'HistoriqueController@store');
    Route::get('/historiques/edit/{id}', 'HistoriqueController@edit');
    Route::patch('/historiques/edit/{id}', 'HistoriqueController@update');
    Route::patch('/histtech/edit/{id}', 'HistoriqueController@updatefortech');
    Route::delete('/historiques/{id}', 'HistoriqueController@destroy');
    Route::get('/histtech/confirmAppelle/{id}', 'HistoriqueController@confirmAppelle');


    Route::post('/equipements/doc/delete', 'DocumentController@deleteData');
    Route::get('/code_pannes/{value}', 'CodePanneController@getCodePanneByCode');
    Route::get('/equipements/{value}', 'EquipementController@getEquipementByCode');
    Route::get('/equipements/document/{id}/{type}', 'DocumentController@getDocument');
    Route::get('/equip/{id}', 'EquipementController@getEquipById');

    //Historique
    Route::get('/hist-sertissage/{id}', 'HistoriqueController@getHistSertissage');
    Route::get('/historiques/equipement/zone/{zone}', 'EquipementController@getEquipements');
    Route::get('/historiques/code-panne/{zone}', 'CodePanneController@getCodePannes');

    Auth::routes();

