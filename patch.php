<?php
function patch($token = null,$Pdata = null)
{
$header[] = "Host: api.gojekapi.com";
$header[] = "User-Agent: okhttp/3.10.0";
$header[] = "Accept: application/json";
$header[] = "Accept-Language: en-ID";
$header[] = "Content-Type: application/json; charset=UTF-8";
$header[] = "X-AppVersion: 3.30.2";
$header[] = "X-UniqueId: ".time()."57".mt_rand(1000,9999);
$header[] = "Connection: keep-alive";
$header[] = "X-User-Locale: en_ID";
//$header[] = "X-Location: -6.3894201,106.0794195";
//$header[] = "X-Location-Accuracy: 3.0";
if ($token):
$header[] = "Authorization: Bearer $token";
endif;
$c = curl_init("https://api.gojekapi.com/v4/customers");
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	if ($Pdata):
    curl_setopt($c, CURLOPT_CUSTOMREQUEST, 'PATCH');
	curl_setopt($c, CURLOPT_POSTFIELDS, $Pdata);
    curl_setopt($c, CURLOPT_POST, true);
    endif;
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($c);
    $httpcode = curl_getinfo($c);
    if (!$httpcode)
        return false;
    else 
	{
		//$header = substr($response,0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
		$headers = [];
		$response = rtrim($response);
		$data = explode("\n",$response);
		$headers['status'] = $data[0];
		array_shift($data);

			foreach($data as $part){

					//some headers will contain ":" character (Location for example), and the part after ":" will be lost, Thanks to @Emanuele
					$middle = explode(":",$part,2);

					//Supress warning message if $middle[1] does not exist, Thanks to @crayons
					if ( !isset($middle[1]) ) { $middle[1] = null; }

					$headers[trim($middle[0])] = trim($middle[1]);
				}

		$body   = substr($response, curl_getinfo($c, CURLINFO_HEADER_SIZE));
	}

    $json = json_decode($body, true);
	$result = array($json,$headers);
	return $result;
}
?>
