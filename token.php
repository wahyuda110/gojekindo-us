<?php

// Created By : Gidhan Bagus Algary

// Header
$secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'X-AppVersion: 3.33.1';
$headers[] = "X-Uniqueid: ac94e5d0e7f3f".rand(100,999);
$headers[] = 'X-Location: -6.9726247,110.4043687';

echo "Nomer HP: ";
$number = trim(fgets(STDIN));
$numbers = $number[0].$number[1];
$numberx = $number[5];
if($numbers == "08") { 
	$number = str_replace("08","628",$number);
}
$login = curl('https://api.gojekapi.com/v3/customers/login_with_phone', '{"phone":"+' . $number . '"}', $headers);
$logins = json_decode($login[0]);
if($logins->success == true) {
	echo "OTP: ";
	$otp = trim(fgets(STDIN));
	$data1 = '{"scopes":"gojek:customer:transaction gojek:customer:readonly","grant_type":"password","login_token":"' . $logins->data->login_token . '","otp":"' . $otp . '","client_id":"gojek:cons:android","client_secret":"' . $secret . '"}';
	$verif = curl('https://api.gojekapi.com/v3/customers/token', $data1, $headers);
	$verifs = json_decode($verif[0]);
	if($verifs->success == true) {
		$token = $verifs->data->access_token;
		$headers[] = 'Authorization: Bearer '.$token;
		$live = "token-login.txt";
    $fopen1 = fopen($live, "a+");
    $fwrite1 = fwrite($fopen1, "".$token." \n");
    fclose($fopen1);
    echo "[+] File Token saved in ".$live." \n";
	} else {
		die("OTP salah goblok!");
	}
} else {
	die("ERROR - Nomer belum kedaftar goblok!");
}

function curl($url, $fields = null, $headers = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($fields !== null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        if ($headers !== null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $result   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return array(
            $result,
            $httpcode
        );
	}
