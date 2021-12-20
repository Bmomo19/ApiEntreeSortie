<?php

namespace App\Http\Controllers;

use App\InfoComplementaire;
use App\TypeInfo;
use App\TypeVisiteur;
use App\Visiteur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class VisiteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $visiteurs = Visiteur::with(['infoComplementaire', 'typeVisiteur'])->get();
            return $visiteurs;

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
    public function storeVisiteur(Request $request)
    {
        try{


            $mat = Visiteur::where('num_piece', $request->input('num_piece'))->first();
            $type_visiteur = TypeVisiteur::where('libelle', $request->input('type_visiteur'))->first();
            $type_info_piece = TypeInfo::where('libelle', 'Piece')->first();
            $type_info_photo = TypeInfo::where('libelle', 'Photo')->first();
            $type_info_qrcode = TypeInfo::where('libelle', 'Qrcode')->first();

            if ($mat) {
                return 444;
            }

            if (!$request->input('photo')) {
                $path_photo = Storage::url('users/user.jpg');
            }


            $code = $request->input('num_piece')."_".Date('dmYHis');

            $qr_code = new BaconQrCodeGenerator();
            $qr_code = $qr_code->format('png')->size(250)->generate($code);
            
            Storage::put('qr_code/'.$request->input('num_piece').'_qrCode.png', $qr_code);
            $path_qrcode = Storage::url('qr_code/'.$request->input('num_piece').'_qrCode.png');
            

                $visiteur = new Visiteur();
                $visiteur->nom = $request->input('nom');
                $visiteur->prenoms = $request->input('prenoms');
                $visiteur->num_piece = $request->input('num_piece');
                $visiteur->type_piece = $request->input('type_piece');
                $visiteur->type_visiteur_id = $type_visiteur->id;
                $visiteur->save();

                if (!empty($request->piece)) {
                    $path_piece = Storage::url(savePicture('piece', $request->input('num_piece').'_piece', $request->file('piece')));
                    $info_piece = new InfoComplementaire();
                    $info_piece->valeur = $path_piece;
                    $info_piece->type_info_id = $type_info_piece->id;

                    $visiteur->infoComplementaire()->save($info_piece);
                }

                $info_photo = new InfoComplementaire();
                $info_photo->valeur = $path_photo;
                $info_photo->type_info_id = $type_info_photo->id;

                $info_qrcode = new InfoComplementaire();
                $info_qrcode->valeur = $path_qrcode;
                $info_qrcode->date_debut = $request->input('date_debut') ?: now();
                $date_fin = Carbon::now()->addDay();
                $info_qrcode->date_fin = $request->input('date_fin') ?: $date_fin;
                $info_qrcode->type_info_id = $type_info_qrcode->id;

                $visiteur->infoComplementaire()->saveMany([$info_photo, $info_qrcode]);

                return $visiteur;

        }catch(\Throwable $th) {
            return 500;
        }
    }


    public function storeAcademicien(Request $request)
    {
        try{

            $mat = Visiteur::where('num_piece', $request->input('num_piece'))->first();
            $type_visiteur = TypeVisiteur::where('libelle', 'Academicien')->first();

            if ($mat) {
                return 444;
            }

            if (empty($request->photo)) {
                $path_photo = Storage::url('users/user.jpg');
            }else {
                $path_photo = Storage::url(savePicture('users', $request->input('num_piece').'_photo', $request->file('photo')));
                $type_info_photo = TypeInfo::where('libelle', 'Photo')->first();
            }

                // $url = $_SERVER['DOCUMENT_ROOT'].'/img/qr_code/';
                // //QrCode::format('png')->size(250)->generate('ItSolutionStuff.com', $url.'qr_code.png');
                $code = $request->input('num_piece')."_".Date('dmYHis');
                $qr_code = new BaconQrCodeGenerator();
                $qr_code = $qr_code->format('png')->size(250)->generate($code);
                
                Storage::put('qr_code/'.$request->input('num_piece').'_qrCode.png', $qr_code);
                $path_qrCode = Storage::url('qr_code/'.$request->input('num_piece').'_qrCode.png');
                $type_info_qrcode = TypeInfo::where('libelle', 'Qrcode')->first();

                
                $visiteur = new Visiteur();
                $visiteur->nom = $request->input('nom');
                $visiteur->prenoms = $request->input('prenoms');
                $visiteur->num_piece = $request->input('num_piece');
                $visiteur->type_piece = $request->input('type_piece');
                $visiteur->type_visiteur_id = $type_visiteur->id;
                $visiteur->save();

                $info = new InfoComplementaire();
                $info->valeur = $path_photo;
                $info->type_info_id = $type_info_photo->id;

                $infos = new InfoComplementaire();
                $infos->valeur = $path_qrCode;
                $info->date_debut = $request->input('date_debut');
                $info->date_fin = $request->input('date_fin');
                $infos->type_info_id = $type_info_qrcode->id;

                $visiteur->infoComplementaire()->saveMany([$info, $infos]);
                return $visiteur;

        }catch(\Throwable $th) {
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
            $visiteur = Visiteur::with('infoComplementaire')->where('num_piece', $id)->first();

            if ($visiteur) {
                return $visiteur;
            }
            else {
                $vtr = Visiteur::with('infoComplementaire')->where('nom', 'like', $id.'%')->orWhere('prenoms', 'like', $id.'%')->get();

                if ($vtr) {
                    return $vtr;
                } else {
                   return 404;
                }
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
    public function update(Request $request, $num_piece)
    {
        try {

            //return $request->all();
            $visiteur = Visiteur::with('infoComplementaire')->where('num_piece', $num_piece)->first();
            
            $type_info_photo = TypeInfo::where('libelle', 'Photo')->first();
            $type_info_qrcode = TypeInfo::where('libelle', 'Qrcode')->first();

            if (!empty($request->photo)) {
                $photo = InfoComplementaire::where('visiteur_id', $visiteur->id)
                                            ->where('type_info_id', $type_info_photo->id)
                                            ->first();

                $path_photo = Storage::url(savePicture('users', $num_piece.'_photo', $request->file('photo')));
                $photo->valeur = $path_photo;
                $photo->save();

            }

            if ($visiteur) {
                $type = TypeVisiteur::where('libelle', $request->input('type_visiteur'))->first();
                $type_visiteur = $type ?: $visiteur->type_visiteur_id;

                $visiteur->nom = $request->input('nom');
                $visiteur->prenoms = $request->input('prenoms');
                $visiteur->type_visiteur_id = $type_visiteur->id;
                $visiteur->save();

                // $qrcode = InfoComplementaire::where('visiteur_id', $visiteur->id)
                //                             ->where('type_info_id', $type_info_qrcode->id)
                //                             ->first();
                
                // $qrcode->date_debut = $request->input('date_debut');
                // $qrcode->date_fin = $request->input('date_fin');

                // $qrcode->save();

                // $visiteur->infoComplementaire()->save($qrcode);
                // $visiteur->save();

                return $visiteur;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($num_piece)
    {
        try {
            $visiteur = Visiteur::with('infoComplementaire')->where('num_piece', $num_piece)->first();
            if ($visiteur) {
                $infos = InfoComplementaire::with('visiteur')->where('visiteur_id', $visiteur->id)->get();
                $visiteur->delete();

                foreach ($infos as $info) {
                    $info->delete();
                }
                $info->delete();
                return 200;
            }
            else {
                return 404;
            }
        } catch (\Throwable $th) {
            return 500;
        }
    }


    /*
     * Generate QrCode
     */
    public function generate_qrcode(Request $request, $num_piece) {
        try {
            $visiteur = Visiteur::with('infoComplementaire')->where('num_piece', $num_piece)->first();

            if ($visiteur) {
                $type_info_qrcode = TypeInfo::where('libelle', 'Qrcode')->first();

                $qrcode = InfoComplementaire::where('visiteur_id', $visiteur->id)
                                            ->where('type_info_id', $type_info_qrcode->id)
                                            ->first();

                $code = $num_piece."_".Date('dmYHis');

                $qr_code = new BaconQrCodeGenerator();
                $qr_code = $qr_code->format('png')->size(250)->generate($code);
                
                Storage::put('qr_code/'.$num_piece.'_qrCode.png', $qr_code);
                $path_qrcode = Storage::url('qr_code/'.$num_piece.'_qrCode.png');
                
                $qrcode->valeur = $path_qrcode;
                $qrcode->date_debut = $request->input('date_debut');
                $qrcode->date_fin = $request->input('date_fin');
                $qrcode->save();

                return $visiteur;

            } else {
                return 404;
            }
            
        } catch (\Throwable $th) {
            return 500;
        }
    }


}
