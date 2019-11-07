<?php
#AUTO CLAIM VOC GOJEK + tf 1rp 
#MASUKIN AKUN YANG UDAH VERIF 
#Created By Alip Dzikri X Apri AMsyah
#####################################

$secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'X-AppVersion: 3.27.0';
$headers[] = "X-Uniqueid: ac94e5d0e7f3f".rand(111,999);
$headers[] = 'X-Location: -6.405821,106.064193';
       
		echo "[+] NOMOR : ";
		$number = trim(fgets(STDIN));
		$numbers = $number[0].$number[1];
		$numberx = $number[5];
		if($numbers == "08") { 
			$number = str_replace("08","628",$number);
		} elseif ($numberx == " ") {
			$number = preg_replace("/[^0-9]/", "",$number);
			$number = "1".$number;
		}
		$nama = nama();
		$email = strtolower(str_replace(" ", "", $nama) . mt_rand(100,999) . "@gmail.com");
		$data1 = '{"name":"' . $nama . '","email":"' . $email . '","phone":"+' . $number . '","signed_up_country":"ID"}';
		$reg = curl('https://api.gojekapi.com/v5/customers', $data1, $headers);
		$regs = json_decode($reg[0]);
		// Verif OTP
		if($regs->success == true) {
			echo "[+] OTP: ";
			$otp = trim(fgets(STDIN));
			$data2 = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $regs->data->otp_token . '"},"client_secret":"' . $secret . '"}';
			$verif = curl('https://api.gojekapi.com/v5/customers/phone/verify', $data2, $headers);
			$verifs = json_decode($verif[0]);
			if($verifs->success == true) {
				// Claim Voucher
				$token = $verifs->data->access_token;
				$headers[] = 'Authorization: Bearer '.$token;
				 $live = "token-tumbal.txt";
    $fopen1 = fopen($live, "a+");
    $fwrite1 = fwrite($fopen1, "".$token." \n"."".$number." \n");
    fclose($fopen1);
    echo "[+] File Token saved in ".$live." \n";
    echo "[+]Process Redeem GOFOODBOBA07 \n";
                sleep(5);
				$data3 = '{"promo_code":"GOFOODBOBA07"}';
				$claim = curl('https://api.gojekapi.com/go-promotions/v1/promotions/enrollments', $data3, $headers);
				$claims = json_decode($claim[0]);
					echo $claims->data->message;
					echo "\n";
					echo "Tunggu 5 Detik!\n";
					sleep(5);
					echo "[+]Process Redeem COBAINGOJEK \n";
				$data4 = '{"promo_code":"COBAINGOJEK"}';
				$claim1 = curl('https://api.gojekapi.com/go-promotions/v1/promotions/enrollments', $data4, $headers);
				$claims1 = json_decode($claim1[0]);
					echo $claims1->data->message;
					}
					}
    function nama()
 {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	// $rand = json_decode($rnd_get, true);
	preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
	return $name[2][mt_rand(0, 14) ];
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