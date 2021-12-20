<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    //Authentification 
    Route::get('auth/{login}/{password}', 'UserController@auth');                           //OK

    //User
    Route::get('users/', 'UserController@index');                                           //OK
    Route::post('users/', 'UserController@store');                                          //OK
    Route::get('users/{id}', 'UserController@show');                                        //OK
    Route::post('users/update/{id}', 'UserController@update');                              //OK
    Route::get('users/delete/{id}', 'UserController@destroy');                              //OK
    Route::post('users/edit-password/{id}', 'UserController@edit_password');                //OK
    //Route::get('users/restrict/', 'UserController@removedTypeUser');

    //Visiteur
    Route::get('visiteurs/', 'VisiteurController@index');                                   //OK
    Route::post('visiteurs/store', 'VisiteurController@storeVisiteur');                     //OK
    Route::post('academicien/store', 'VisiteurController@storeAcademicien');                //OK
    Route::get('visiteurs/{id}', 'VisiteurController@show');                                //OK 
    Route::post('visiteurs/update/{id}', 'VisiteurController@update');                      //OK
    Route::get('visiteurs/delete/{id}', 'VisiteurController@destroy');                      //OK
    Route::post('visiteurs/new-qrcode/{num_piece}', 'VisiteurController@generate_qrcode');

    //In-out
    Route::get('in_out/', 'in_Outcontroller@index');                                        //OK
    Route::get('in_out/{id}/', 'in_Outcontroller@show');                                    //OK
    Route::post('in_out/in/{v_id}/{u_id}', 'in_Outcontroller@in');                          //OK                     
    Route::get('in_out/out/{v_id}/{u_id}', 'in_Outcontroller@out');                           //OK
    //Route::post('in_out/user/', 'in_Outcontroller@store_historique_user');


    //Tri
    Route::get('in_out/get/last_arrived/', 'in_Outcontroller@lastArrived');                 //OK
    Route::get('in_out/get/last_depart/', 'in_Outcontroller@lastDepart'); 


    //Type visiteur
    Route::get('type_visiteur', 'TypeVisiteurController@index');                            //OK
    Route::post('type_visiteur', 'TypeVisiteurController@store');                           //OK
    Route::get('type_visiteur/{id}', 'TypeVisiteurController@show');                        //OK
    Route::post('type_visiteur/update/{id}', 'TypeVisiteurController@update');              //OK
    Route::get('type_visiteur/delete/{id}', 'TypeVisiteurController@destroy');              //OK

    //Type info
    Route::get('type_info', 'TypeInfoController@index');                                    //OK
    Route::post('type_info', 'TypeInfoController@store');                                   //OK
    Route::get('type_info/{id}', 'TypeInfoController@show');                                //OK
    Route::post('type_info/update/{id}', 'TypeInfoController@update');                      //OK
    Route::get('type_info/delete/{id}', 'TypeInfoController@destroy');                      //OK

    // Statistique
    Route::get('statistique/get/avg_hour_academician', 'StatistiqueController@get_hours_avg_academician'); //OK
    Route::get('statistique/get/in/', 'StatistiqueController@usersIn');                               //OK                  //OK
    Route::get('statistique/get/in_out_of_week/', 'StatistiqueController@inOutOfWeek');               //OK

});
