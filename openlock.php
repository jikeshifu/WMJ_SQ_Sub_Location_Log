<?php
session_start();

require './class/function.php';
require './class/db.class.php';
function mlog($txt,$filename='log.txt') {
    $txt = date('Y/m/d H:i:s').": {$txt}\r\n";
    file_put_contents('./'.$filename, $txt, FILE_APPEND); //追加内容
}
function ajaxReturn($code,$message,$info = ''){
    $return = array();
    $return['code'] = $code;
    $return['msg'] = $message;
    //$return['name'] = $name;
    if(empty($info)){
        $return['info'] = ['list'=>[]];
    }else{
        $return['info'] = $info;//返回数组数据
    }
    header('HTTP/1.1 200 OK');
    header('Status:200 OK');
    header('Content-Type:application/json; charset=utf-8');
    echo json_encode($return);
    exit;
}
function GetDistance($lat1, $lng1, $lat2, $lng2) {
    $radLat1 = $lat1 * 3.1415926 / 180.0;
    $radLat2 = $lat2 * 3.1415926 / 180.0;
    $a = $radLat1 - $radLat2;
    $b = ($lng1 * 3.1415926 / 180.0) - ($lng2 * 3.1415926 / 180.0);
    $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $s = $s * 6378.137;
    $s = round($s * 1000);
    return $s;
}
mlog('openlock');
$post = $_POST;
mlog('$post:'.json_encode($post));
$id = $_POST['id'];
if ($id < 1) {
    ajaxReturn(2,'非法访问');
}
$db = ConnectMysqli::getIntance();
$weixin = $db->getRow('select * from config where k="weixin"');
$wxcfg = json_decode($weixin['v'], true);
$wmj = $db->getRow('select * from config where k="wmj"');
$config = json_decode($wmj['v'], true);
$lock = $db->getRow('select * from locks where id='.$id);
mlog('$lock:'.json_encode($lock));
if (!$lock){
    ajaxReturn(2,'非法访问');
}
//echo $lock['state'];
if (!$lock['state'])
{
    ajaxReturn(2,'设备维护中，暂时无法出入，请稍等片刻');
}
if ($config['aeskey']) {
    $lock['sn'] = aesEncrypt($lock['sn'], $config['aeskey']);
}

$type = $_POST['type'];
$nickname = $_POST['nickname'];
$openid = $_POST['openid'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
if ($lock['lat'] && $lock['lng'] && $lock['distance'] > 0) {
    $distance = GetDistance($_POST['lat'],$_POST['lng'],$lock['lat'],$lock['lng']);
    mlog('$distance1:'.$distance);
    $distance = abs($distance);
    mlog('$distance2:'.$distance);
    if ($distance > $lock['distance']) {
        ajaxReturn(2,'超出开门距离范围，不能开门');
    }
}
$result = httpPost('https://www.wmj.com.cn/api/openlock.html?appid='.$config['appid'].'&appsecret='.$config['appsecret'], $lock['sn']);

$result = json_decode(trim($result, "\xEF\xBB\xBF"), true);

$logdata = array(
    'lock_id'  => $lock['id'],
    'type'     => $type,
    'nickname' => $nickname,
    'openid' => $openid,
    'msg'      => $result['state_msg'],
    'addtime'  => time(),
);
//print_r($logdata);
$db->insert("log", $logdata);

if ($result['state']) {
    ajaxReturn(1,'开门成功');
} else {
    ajaxReturn(2,'开门失败');
}