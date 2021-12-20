<?php

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (Faker $faker) {

    // $url = $_SERVER['DOCUMENT_ROOT'].'/img/qr_code/';
    // $qrcode = new BaconQrCodeGenerator();

    // for ($i=0; $i < 50; $i++) {
    
    //     $mat = 'S'.$faker->unique()->numberBetween($min = 1, $max = 200);
        
    //     //QrCode::format('png')->size(250)->generate('ItSolutionStuff.com', $url.'qr_code.png');

        
    //     $qrcode->format('png')->size(250)->generate($mat, $url.$mat.'_qr.png');
    // }

    // $path = asset('img/qr_code/'.$mat.'_qr.png');
    return view('welcome');
});

Route::get('test', function() {

    // $d = CarbonImmutable::now()->locale('en_US');
    // $week_start = $d->startOfWeek(Carbon::MONDAY)->toDateTimeString();
    // $week_end = $d->endOfWeek(Carbon::SUNDAY)->toDateTimeString();

    // $week_start->toDateTimeString();
    //$week_end->toDateTimeString();
    //return 'Debut: '.$week_start.'<br>'.'Fin: '.$week_end;

    // $number = "79512947";
    // $message = "Message test Appli InOut";
    // send_sms_with_osms($number, $message);

    // $hour = Carbon::createFromTimeString("10:10:10");
    // return $hour;
});
