<?php
###Ini Copyright###
###https://github.com/osyduck/Gojek-Register###
###Ini Recode###
###https://fb.com/ciblek.tamvan###

include ("post.php");
include ("patch.php");
	function login($no)
	{
	$data = '{"phone":"+'.$no.'"}';
	$register = post("/v4/customers/login_with_phone", "", $data);
	if ($register['success'] == 1)
		{
		return $register['data']['login_token'];
		}
	  else
		{
      save("error_log.txt", json_encode($register));
		return false;
		}
	}
function veriflogin($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
	$verif1 = post("/v4/customers/login/verify", "", $data);
	if ($verif1['success'] == 1)
		{
		$access = $verif1['data']['access_token'];
        $cust = $verif1['data']['customer']['id'];
		$my_mail = $verif1['data']['customer']['email'];
		$my_name = $verif1['data']['customer']['name'];
		$result = array($cust,$my_mail,$my_name,$access);
		
		return $result;
		}
	  else
		{
      save("error_log.txt", json_encode($verif1));
		return false;
		}
	}
function claim($token,$nomor,$my_mail,$my_name)
	{
	$Pdata = '{"email":"'.$my_mail.'","name":"'.$my_name.'","phone":"+'.$nomor.'"}';
	$claim = patch($token, $Pdata);
	print_r ($claim);
	if ($claim[0]['success'] == 1)
		{
		$message_patch = $claim[0]['data']['message'];
		$GPToken = $claim[1]['GPToken'];
		$result = array($message_patch,$GPToken);
		return $result;
		}
	  else
		{
      save("error_log.txt", json_encode($claim));
		return false;
		}
	}
function verifclaim($token,$cust,$nomor,$code,$gptoken)
	{
	$data = '{"id":"'.$cust.'","phone":"+'.$nomor.'","verificationCode":"'.$code.'"}';
	$claim1 = post("/v4/customer/verificationUpdateProfile", $token, $data, $gptoken, $cust);
	if ($claim1['success'] == 1)
		{
		return $claim1['data']['message'];
		}
	  else
		{
      save("error_log.txt", json_encode($claim1));
	  echo "error";
		return false;
		}
	}

echo "Enter Number: ";
$nope = trim(fgets(STDIN));
$login = login($nope);
if ($login == false)
	{
	echo "Failed to Get OTP!\n";
	}
  else
	{
	echo "Enter Your OTP: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif1 = veriflogin($otp, $login);
	if ($verif1 == false)
		{
		echo "Failed to Login with Your Number!\n";
		}
	  else
		{
		print_r ($verif1);
		echo "Ready to Recycle\n";
        echo "Enter Number: ";
        $nomor = trim(fgets(STDIN));
	    $claim = claim($verif1[3],$nomor,$verif1[1],$verif1[2]);
		if ($claim == false)
			{
			echo "Failed to Recycle\n";
			}
		  else
			{
			print_r ($claim[0]);
			echo "\n\nEnter OTP: ";
			
        $OTP = trim(fgets(STDIN));
	    $vclaim = verifclaim($verif1[3],$verif1[0],$nomor,$OTP,$claim[1]);
	print_r ($vlaim[0]);
	echo "\n";
         }
		}
	}
	?>
