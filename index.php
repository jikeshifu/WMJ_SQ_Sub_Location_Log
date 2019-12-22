<?php
session_start();

require './class/function.php';
require './class/db.class.php';

require './lib/Smarty.class.php';
require './lib/JsSdk.class.php';
function mlog($txt,$filename='log.txt') {
    $txt = date('Y/m/d H:i:s').": {$txt}\r\n";
    file_put_contents('./'.$filename, $txt, FILE_APPEND); //追加内容
}
$smarty = new Smarty;
$data_state = 1;
$data_msg = '正在开门中';
$data_img = '/static/images/loading.gif';


$db = ConnectMysqli::getIntance();

$weixin = $db->getRow('select * from config where k="weixin"');

$wxcfg = json_decode($weixin['v'], true);

$wmj = $db->getRow('select * from config where k="wmj"');

$config = json_decode($wmj['v'], true);
$lockid = $_GET['id'];
$lock = [];
if ($lockid > 0) {
    $lock = $db->getRow('select * from locks where id='.$lockid);
}
if (!$lock)
{
    $data_state = 0;
    $data_msg = '非法访问';
}
//echo $lock['state'];
if (!$lock['state'])
{
    $data_state = 0;
    $data_msg = '设备维护中，暂时无法出入，请稍等片刻';
}

if(!$_GET['code'])
{
	$redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
	$redirect_uri = urlencode($redirect_uri);
	$jump_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$wxcfg['appid']."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo#wechat_redirect";
	header("Location:".$jump_url);
}

$output = httpGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wxcfg['appid'].'&secret='.$wxcfg['appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code');

$output = json_decode($output, true);
mlog('$output:'.json_encode($output));
$user_info = httpGet('https://api.weixin.qq.com/sns/userinfo?access_token='.$output['access_token'].'&openid='.$output['openid'].'&lang=zh_CN');

$user_info = json_decode($user_info, true);
mlog('$user_info:'.json_encode($user_info));
//echo $user_info['openid'];
$jssdk             = new JsSdk();
$access_token = $jssdk->getAccessToken($wxcfg['appid'], $wxcfg['appsecret']);
//echo $access_token;

$user_info_m = httpGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$user_info['openid'].'&lang=zh_CN');

$user_info_m = json_decode($user_info_m, true);
mlog('$user_info_m:'.json_encode($user_info_m));

$signPackage       = $jssdk->getSignPackage($wxcfg['appid'], $wxcfg['appsecret']);
mlog('$signPackage:'.json_encode($signPackage));
// $this->signPackage = $signPackage;
if(!$user_info_m['subscribe'])
{   //print_r( $user_info_m);
    $data_state = 0;
    $data_msg = '长按二维码识别关注后可再次扫码开门';
    $data_img = '/static/images/getqrcode.jpg';
}
$type = $_GET['type'];
$nickname = $user_info['nickname'];
$openid = $user_info['openid'];
// if($data['state'])
// {
// 	if ($config['aeskey']) {
// 		$lock['sn'] = aesEncrypt($lock['sn'], $config['aeskey']);
// 	}
// 	$result = httpPost('https://www.wmj.com.cn/api/openlock.html?appid='.$config['appid'].'&appsecret='.$config['appsecret'], $lock['sn']);

// 	$result = json_decode(trim($result, "\xEF\xBB\xBF"), true);

// 	$logdata = array(
// $lock_id'  => $lock['id'],
// 		'type'     => $_GET['type'],
// 		'nickname' => $user_info['nickname'],
//         'openid' => $user_info['openid'],
// 		'msg'      => $result['state_msg'],
// 		'addtime'  => time(),
// 	);
// 	//print_r($logdata);
// 	$db->insert("log", $logdata);

// 	if ($result['state'])
//     {
// 		$data = array(
// 			'state' => 1,
// 			'msg'   => '开门成功',
// 		);
// 	} else
//     {
// 		$data = array(
// 			'state' => 0,
// 			'msg'   => '开门失败',
// 		);
// 	}
// }
$data = array(
    'state' => $data_state,
    'msg'   => $data_msg,
    'img'   => $data_img
);
$jspost = [];
$jspost['type'] = $type;
$jspost['nickname'] = $nickname;
$jspost['openid'] = $openid;
$jspost['id'] = $lockid;
// mlog('jspost:'.json_encode($jspost));
$smarty->assign('jspost', $jspost);
$smarty->assign('signPackage', $signPackage);
$smarty->assign('data', $data);
$smarty->display('index.html');
