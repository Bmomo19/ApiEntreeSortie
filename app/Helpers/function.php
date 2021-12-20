<?php

use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Osms\Osms;
use Twilio\Rest\Client;

function savePicture($emplacement, $fileName, $file) {

    $listExt = ['png', 'jpg', 'jpeg', 'gif'];
    $ext = $file->extension();


    if (in_array($ext, $listExt)) {
        
        $path = $file->storeAs(
            $emplacement, $fileName . "." . $ext
        );

        return $path; 
    }
    else {
        
       return false;
    }


}


function send_sms_with_twilio($number, $message) {

    $account_sid = 'AC8ce40a5adfdcdb2530bd1ba9cd2f3962';
    $auth_token = 'ab0fe74fcf850fbd300d6a33833b43e7';

    $twilio_number = "+19798594904";

    $client = new Client($account_sid, $auth_token);
    $client->messages->create(
        // Where to send a text message (your cell phone?)
        $number,
        array(
            'from' => $twilio_number,
            'body' => $message
        )
    );
}


function send_sms_with_osms($number, $msg) {
    $config = array(
        'clientId' => 'UXACVDrQ6Ks9e3Jo32oDN1K7agAacErI',
        'clientSecret' => 'K99fBSa6iXMAmdIs'
    );

    
    $osms = new Osms($config);
    
    // retrieve an access token
    $response = $osms->getTokenFromConsumerKey();

    //dd($response);
    
    if (!empty($response['access_token'])) {
        $senderAddress = 'tel:+22579512947';
        $receiverAddress = 'tel:+225'.$number;
        $message = $msg;
        $senderName = 'ODA InOut';
    
        $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
    } else {
        dd($response);
    }
}


function get_middle_hour($h_less, $h_greater) {
    CarbonPeriod::macro('middle', function () {
        return $this->getStartDate()->average($this->getEndDate());
      });
      return CarbonPeriod::since($h_less)->until($h_greater)->middle();
}