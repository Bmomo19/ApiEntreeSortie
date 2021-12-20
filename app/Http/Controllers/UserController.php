<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $users = User::all();
            return $users;

        } catch (\Throwable $th) {
            return 500 ;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            $identifiant = User::where('identifiant', $request->identifiant)->first();
            $login = User::where('login', $request->login)->first();
            if ($identifiant) {
                return 444;
            }

            if($login) {
                return 444;
            }

            
            
                $path = Storage::url('users/user.jpg');

                $user = new User();
                $user->identifiant = $request->input('identifiant');
                $user->nom = $request->input('nom');
                $user->prenoms = $request->input('prenoms');
                $user->tel = $request->input('tel');
                $user->role = $request->input('role');
                $user->login = $request->input('login');
                $user->password = Hash::make($request->input('password'));
                $user->photo = $path;
                $user->save();
                return $user;

        }catch(\Throwable $th) {
            return 500;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('identifiant', $id)->first();

        if ($user) {
            return $user;
        }
        else {
            return 404;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   try {
            //return $request->all();
            $user = User::where('identifiant', $id)->first();

            if ($user) {
                
                $path = false;
                if (!empty($request->photo)) {
                    $path = Storage::url(savePicture('users',$user->identifiant.'_users',$request->file('photo')));
                }

                $user->nom = $request->input('nom') ?: $user->nom;
                $user->prenoms = $request->input('prenoms') ?: $user->prenoms;
                $user->tel = $request->input('tel')  ?: $user->tel;
                $user->role = $request->input('role')  ?: $user->role;
                $user->photo = $path ?: $user->photo;
                $user->save();

                return $user;
            }
            else {
                return 404;
            }

        } catch (\Throwable $th) {
            return 500;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::where('identifiant', $id)->first();
            if ($user) {
                $user->delete();
                return 200;
            }
            else {
                return 404 ;
            }
        } catch (\Throwable $th) {
            return 500;
        }


    }


    //Authentification
    public function auth($login, $password) {
        try {
            $user = User::where('login', $login)->first();

            if ($user) {
                if (Hash::check($password, $user->password)) {
                    return $user;
                }else{
                    return 404;
                }
            }else{
                return 404;
            }
        } catch (\Throwable $th) {
            return 500;
        }

    }

    //modifer un password
    public function edit_password(Request $request, $id) {
        
        try {
            $user = User::where('identifiant', $id)->first();

            if ($user) {
                $password = Hash::make($request->input('new_password'));
                $user->password = $password;
                $user->save();
                return 200;
            }
            else {
                return 404;
            }
        } catch (\Throwable $th) {
            return 500;
        }
    }

}
