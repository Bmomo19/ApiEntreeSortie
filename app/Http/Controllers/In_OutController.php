<?php

namespace App\Http\Controllers;

use App\InOut;
use App\User;
use App\Visiteur;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class In_OutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
        {
            try {
                $inOut = InOut::with(['visiteur', 'user'])
                        ->orderBy('updated_at', 'desc')
                        ->get();
    
                return $inOut;
            } catch (\Throwable $th) {
                return 500;
            }
        }

    public function show($id)
    {
        try{
            $visiteur = Visiteur::with('inOut')->where('num_piece', $id)->first();

            if ($visiteur) {
                return $visiteur;
            } else {
                return 404;
            }

        }catch(\Throwable $th) {
            return 500;
        }

    }

    public function in(Request $request, $v_id, $u_id) {

        try {

            $visiteur = Visiteur::where('num_piece', $v_id)->first();
            $user =     User::where('identifiant', $u_id)->first();

            if ($visiteur && $user) {

                $in = new InOut();
                $in->motif          = $request->input('motif');
                $in->date_arrive    = now();
                $in->visiteur_id    = $visiteur->id;
                $in->resp_entree = $user->prenoms . " " . $user->nom;
                $in->save();

                if ($request->input('user_id') && $request->input('user_id') != "" && $request->input('user_id') != null) {
                    $user_visited =     User::where('id', $request->input('user_id'))->first();

                    if ($user_visited) {
                        
                        if ($user_visited->tel != null) {
                            $number = $user_visited->tel;
                            $msg = "NOTIFICATION e-Register ODA\n\nVous avez une visite de ". $visiteur->prenoms. " " . $visiteur->nom;

                            send_sms_with_osms($number, $msg);
                        }
                    }
                }

                return $in;

            } else {
                return 404;
            }

        } catch (\Throwable $th) {
            return 500;
        }
    }
    public function out($v_id, $u_id) {

        try {

            $visiteur = Visiteur::where('num_piece', $v_id)->first();
            $user_out =     User::where('identifiant', $u_id)->first();

            if ($visiteur && $user_out) {

                $out = InOut::where('visiteur_id', $visiteur->id)->where('date_depart', null)->get();
                $out = $out->last(); 
                $user_in =     User::where('id', $out->entree_user_id)->first();

                //dd($out->date_depart);
                if ($out) {
                    
                    if (empty($out->date_arrive)) {
                        return 460;
                    }

                    $out->date_depart = now();
                    $out->resp_sortie = $user_out->prenoms . " " . $user_out->nom;
                    $out->save();

    
                    return ["data" => $out, "responsable_entree" => $user_in, "responsable_sortie" => $user_out];
                }else {
                    return 460;
                }


            } else {
                return 404;
            }

        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function lastArrived() {

        try {
            $users = InOut::with('visiteur')->orderBy('date_arrive', 'desc')->take(10)->get();
            return $users;
        } catch (\Throwable $th) {
            return 500;
        } 
    }

    public function lastDepart() {
        try {
            $users = InOut::with('visiteur')->where('date_depart','!=', null)->orderBy('date_depart', 'desc')->take(10)->get();
            return $users;
        } catch (\Throwable $th) {
            return 500;
        }
    }

    

}
