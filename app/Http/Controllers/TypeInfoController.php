<?php

namespace App\Http\Controllers;

use App\TypeInfo;
use Illuminate\Http\Request;

class TypeInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $type = TypeInfo::all();
            return $type;
        } catch (\Throwable $th) {
            return 500;
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
        try {

            $type_visiteur = TypeInfo::where('libelle', $request->libelle)->first();

            if (!$type_visiteur) {

                if (!empty($request->libelle)) {
                    $type = new TypeInfo();
                    $type->libelle = $request->libelle;
                    $type->save();

                    return $type;
                }else {
                    return 400;
                }

            } else {
                return 444;
            }

        } catch (\Throwable $th) {
            return 500;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $type = TypeInfo::where('id', $id)->first();
            if ($type) {
                return $type;
            } else {
                return 404;
            }
            
        } catch (\Throwable $th) {
            return 500;
        }
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $type_visiteur = TypeInfo::where('libelle', $request->libelle)->first();
            $type = TypeInfo::where('id', $id)->first();

            if (empty($request->libelle)) {
                return 400;
            }

            if (!$type) {
                return 404;
            }

            if ($type_visiteur) {
                return 444;
            }
                
            $type->libelle = $request->libelle;
            $type->save();

            return $type;
       
        } catch (\Throwable $th) {
            return 500;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $type = TypeInfo::where('id', $id)->first();

            if ($type) {
                $type->delete();
                return 200;
            }else {
                return 404;
            }
        } catch (\Throwable $th) {
            return 500;
        }
    }
}
