<?php
function dp($str) {
	echo '<pre>';
	print_r($str);
	exit();
}

function msg($msg, $url) {
	echo '<script language="javascript" type="text/javascript">';
	echo 'alert("'.$msg.'");';
	echo 'window.location.href="'.$url.'"';
	echo '</script>';
	exit();
}

function httpGet($url) {

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_URL, $url);

	$res = curl_exec($curl);
	curl_close($curl);

	return $res;
}

function httpPost($url, $str) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_POSTFIELDS, $str);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($str))
    );
    $res = curl_exec ($curl);
    curl_close($curl);
 
    return $res;
}
/*
function getAccessToken($appid, $appsecret) {
	
	$access_token = file_get_contents('access_token.txt');
	
	$access_token = json_decode($access_token, true);

	if($access_token['token_time'] > (time()-3600)) {
		
		$token = $access_token['token'];
		
	} else {
		
		$access_token = httpGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);

		$access_token = json_decode($access_token, true);

		if ($access_token['access_token']) {
			file_put_contents('access_token.txt', json_encode(array('token' => $access_token['access_token'], 'token_time' => time())));
			
			$token = $access_token['access_token'];
		}
	}

	return $token;
}
*/
/*
 * AES加密 -- 加密采用128位CBC模式加密
 */
function aesEncrypt ($value, $key) {
    $padSize = 16 - (strlen($value) % 16);
    $value   = $value . str_repeat(chr($padSize), $padSize) ;
    $output  = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, str_repeat(chr(0), 16));
 
    return base64_encode($output);
}