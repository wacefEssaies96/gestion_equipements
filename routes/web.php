<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('dashboard');
});
Route::get('/responsables', 'ResponsableController@index');
Route::post('/responsables', 'ResponsableController@store');
Route::get('/responsables/liste', 'ResponsableController@liste');
Route::get('/responsables/edit/{id}', 'ResponsableController@edit');
Route::patch('/responsables/edit/{id}', 'ResponsableController@update');
Route::delete('/responsables/{id}', 'ResponsableController@destroy');


Route::get('/products', 'SinglePageController@index');
Route::post('/products', 'SinglePageController@store');
Route::get('/products/edit/{id}', 'SinglePageController@edit');
Route::patch('/products/edit/{id}', 'SinglePageController@update');
Route::delete('/products/{id}', 'SinglePageController@destroy');

