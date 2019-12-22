<?php
$db = ConnectMysqli::getIntance();
$id = isset($_GET['id']) ? $_GET['id'] :$_POST['id'];
if (!$id) {
	msg('门禁不存在', 'admin.php?ac=list');
}
$sql = 'select * from locks where id='.$id;
$locks = $db->getRow($sql);
if (!$locks) {
	msg('门禁不存在', 'admin.php?ac=list');
}
if ($_POST) {
	$sn = trim($_POST['sn']);
	$name = trim($_POST['name']);
	if (!$sn || !$name) {
		msg('名称和序列号不能为空', 'admin.php?ac=edit&id='.$id);
	}
	// 判断去重
	$findsql = "select * from locks where sn='$sn'";
	// var_dump($findsql);
	$findlocks = $db->getAll($findsql);
	// var_dump($findlocks);
	// exit;
	if (count($findlocks) > 1) {
		msg('设备已经存在，请勿重复添加', 'admin.php?ac=edit&id='.$id);
	}
	if ($findlocks[0]['id'] != $id) {
		msg('设备已经存在，请勿重复添加', 'admin.php?ac=edit&id='.$id);
	}
	$wmj = $db->getRow('select * from config where k="wmj"');
	$config = json_decode($wmj['v'], true);
	if (!$config['appid']) {
		msg('请配置微门禁参数', 'admin.php');
	}
	$sn = $_POST['sn'];
	if ($config['aeskey']) {
		$sn = aesEncrypt($sn, $config['aeskey']);
	}
	// $postlock = httpPost('https://www.wmj.com.cn/api/postlock.html?appid='.$config['appid'].'&appsecret='.$config['appsecret'], $sn);
	// $result = json_decode(trim($postlock, "\xEF\xBB\xBF"), true);
	$lat = trim($_POST['lat']);
	$lng = trim($_POST['lng']);
	$distance = trim($_POST['distance']);
	$distance = (int)$distance;
	// if ($result['state']) {
		$data = array(
			'name'  => $_POST['name'],
			'sn'    => $_POST['sn'],
			'state' => 1,
			'distance' => $distance,
			'lat' => $lat,
			'lng' => $lng
		);
		if ($_POST['sim']) {
			$data['sim'] = $_POST['sim'];
		}
		$upres = $db->update('locks', $data, 'id='.$id); //  int(1)
		// var_dump($upres);
		// exit;
	// }
	if ($upres) {
		msg('编辑设备成功', 'admin.php?ac=edit&id='.$id);
	}else{
		msg('编辑设备失败', 'admin.php?ac=edit&id='.$id);
	}
}
$smarty->assign('locks', $locks);
$smarty->assign('nav_list', 'active');
$smarty->display('admin_edit.html');