<?php
function setm($txt,$filename='cache.txt') {
    // $txt = date('Y/m/d H:i:s')."{$txt}\r\n";
    file_put_contents('./'.$filename, $txt); // , FILE_APPEND 追加内容
}
function getm($filename='cache.txt') {
    $str = file_get_contents('./'.$filename);
    return  json_decode($str,true);
}
function setTicket($txt,$filename='Ticketcache.txt') {
    // $txt = date('Y/m/d H:i:s')."{$txt}\r\n";
    file_put_contents('./'.$filename, $txt); // , FILE_APPEND 追加内容
}
function getTicket($filename='Ticketcache.txt') {
    $str = file_get_contents('./'.$filename);
    return  json_decode($str,true);
}
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: jssdk.class.php 29558 2015-02-25 16:34:34Z dzapp $
 */
class JsSdk {
	function __construct() {
		//$this->appid = C('WEIXIN_APPID');
		//$this->appsecret = C('WEIXIN_APPSECRET');
	}

	function getSignPackage($appid,$appsecret)
    {
        //print_r('getSignPackage'.$appid);
		$jsapiTicket = $this->getJsApiTicket($appid,$appsecret);

		//$url = "http://cloud.wmj.com.cn".$_SERVER['REQUEST_URI'];
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$timestamp = time();
		$nonceStr = $this->createNonceStr();

		$string = "jsapi_ticket=".$jsapiTicket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url;
		$signature = sha1($string);

		$signPackage = array(
			"appId"     => $appid,
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
       //print_r($signPackage);
       //exit();
		return $signPackage;
	}

	function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	function getJsApiTicket($appid,$appsecret)
    {
        // print_r("jsapi_ticket</br>");
        //exit();
		$data = getTicket();
		// print_r($data['expire_time']."</br>");
		// print_r(time()."</br>");
		// print_r("jsapi_ticket".$data['jsapi_ticket']."</br>");
        //print_r($data['jsapi_ticket']."jsapi_ticket</br>");
        //$myfile = fopen("jsapi_ticket.txt", "a+") or die("Unable to open file!");
        //$txt = date('Y-m-d H:i:s')."^_^".rand(100,1000)."\r\n";
        //fwrite($myfile, $txt);
        //fclose($myfile);
		if ($data['expire_time'] < time())
        {
        	// print_r("jsapi_ticket_expire_time");
			$accessToken = $this->getAccessToken($appid,$appsecret);
			//$accessToken = getAccessToken($appid,$appsecret);

			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode(httpGet($url));
			// print_r($res);
			//exit();
			$ticket = $res->ticket;
			if ($ticket) {
				$data['expire_time'] = time() + 3600;
				$data['jsapi_ticket'] = $ticket;
				setTicket(json_encode($data));
			}
		}else{
			$ticket = $data['jsapi_ticket'];
		}
		return $ticket;
	}

	function getAccessToken($appid,$appsecret)
    {

		$data = getm();
		if ($data['expire_time'] < time()) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$res = json_decode(httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data['expire_time'] = time() + 3600;
				$data['access_token'] = $access_token;
				setm(json_encode($data));
                // S('access_token'.$appid, $data, 3600);
                $myfile = fopen("./data/accesstokenlog.txt", "a+") or die("Unable to open file!");
                fwrite($myfile, date( "Y-m-d H:i",time())."---".$appid."在JsSDK.class.php生成了新的access_token:".$access_token."\n");
                fclose($myfile);
			}
		} else {
			$access_token = $data['access_token'];
		}
		return $access_token;
	}
}
