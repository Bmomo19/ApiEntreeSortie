<?php

namespace App\Http\Controllers;

use App\InOut;
use App\Visiteur;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StatistiqueController extends Controller
{
    public function get_hours_avg_academician() {

        $heure_arrive_min = Carbon::createFromTimeString("00:00:00");
        $heure_arrive_max = Carbon::createFromTimeString("00:00:00");
        $heure_depart_min = Carbon::createFromTimeString("00:00:00");
        $heure_depart_max = Carbon::createFromTimeString("00:00:00");

        $arrive = InOut::select(InOut::raw('DATE_FORMAT(date_arrive, "%H:%i:%s") as heure_arrive'), "visiteur_id")
        ->orderBy('heure_arrive')->get();
        $arrive = $arrive->where('visiteur.type_visiteur_id', 3)->groupBy('visiteur.num_piece');
        //$arrive = $arrive->get("date_arrive");

        foreach ($arrive as $item) {
            if ($item != []) {
                $h_min = Arr::first($item);
                $h_min = Carbon::createFromTimeString($h_min['heure_arrive']);
                if ($heure_arrive_min->lessThan($h_min)) {
                    $heure_arrive_min = $h_min;
                }

                $h_max = Arr::last($item);
                $h_max = $h_max[count($h_max) - 1];
                $h_max = Carbon::createFromTimeString($h_max['heure_arrive']);
                if ($h_max->greaterThan($heure_arrive_max)) {
                    $heure_arrive_max = $h_max;
                }
            }
        }

        $depart = InOut::select(InOut::raw('DATE_FORMAT(date_depart, "%H:%i:%s") as heure_depart'), "visiteur_id")
        ->orderBy('heure_depart')->get();
        $depart = $depart->where('visiteur.type_visiteur_id', 1)->groupBy('visiteur.num_piece');
        //$depart = $depart->get("date_depart");

        foreach ($depart as $item) {
            if ($item != []) {

                foreach ($item as $key) {
                    if ($key['heure_depart'] != null) {
                        $h_min = $key;
                        break;
                    }
                }
                
                if ($h_min['heure_depart'] != null) {
                    $h_min = Carbon::createFromTimeString($h_min['heure_depart']);
                    if ($heure_depart_min->lessThan($h_min)) {
                        $heure_depart_min = $h_min;
                    }
                }
                

                $h_max = Arr::last($item);
                $h_max = $h_max[count($h_max) - 1];
                if ($h_max['heure_depart'] != null) {
                    $h_max = Carbon::createFromTimeString($h_max['heure_depart']);
                    if ($h_max->greaterThan($heure_depart_max)) {
                        $heure_depart_max = $h_max;
                    }
                }
            }
        }

        $heure_arrive_moy = get_middle_hour($heure_arrive_min->format('H:i:s'), $heure_arrive_max->format('H:i:s'));
        $heure_depart_moy = get_middle_hour($heure_depart_min->format('H:i:s'), $heure_depart_max->format('H:i:s'));
        

       return [
           "arr" => [
                "min" => $heure_arrive_min,
                "max" => $heure_arrive_max,
                "moy" => $heure_arrive_moy,
           ],
           "dep" => [
                "min" => $heure_depart_min,
                "max" => $heure_depart_max,
                "moy" => $heure_depart_moy,
                ]
           ];
    }


    public function usersIn() {

        try {
            $users = InOut::with('visiteur')->where('date_depart', null)->get();

            if ($users) {
                $visiteurs = [];
                foreach ($users as $item) {
                    if ($item["visiteur"]['type_visiteur_id'] === 1) {
                        $visiteurs[] = $item;
                    }
                }
                $count = count($visiteurs);
                return ['nombre_present' => $count, 'visiteurs' => $visiteurs];
            } else {
                return 400;
            }
            
        } catch (\Throwable $th) {
            return $th;
        }
  
    }


    public function inOutOfWeek() {

        try{
            $d = CarbonImmutable::now()->locale('en_US');
            $week_start = $d->startOfWeek(Carbon::MONDAY)->toDateTimeString();
            $week_end = $d->endOfWeek(Carbon::SUNDAY)->toDateTimeString();

            $users = InOut::with('visiteur')->whereBetween('date_arrive', [$week_start, $week_end])->get();
            $users = $users->groupBy('visiteur_id');
            $users = $users->toArray();

            //dd($users);

            $count = count($users);
            return ['Total' => $count, 'inOut' => $users];
        }catch(\Throwable $th) {
             return 500;
        }

    }
}
