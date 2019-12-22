<?php

$db = ConnectMysqli::getIntance();

if ($_POST) {
	
	if ($_POST['wmj']) {
		
		$data = array(
			'appid'     => $_POST['appid'],
			'appsecret' => $_POST['appsecret'],
			'aeskey'    => $_POST['aeskey'],
		);
		
		$db->update('config', array('v' => json_encode($data)), 'k="wmj"');
	}
	
	if ($_POST['weixin']) {
		
		$data = array(
			'appid'     => $_POST['appid'],
			'appsecret' => $_POST['appsecret'],
		);
		
		$db->update('config', array('v' => json_encode($data)), 'k="weixin"');
	}
	if ($_POST['domaintxt'])
    {
      if($_FILES["file"] && $_FILES["file"]["type"] == "text/plain") 
      {
		move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
	  }
	}
    if ($_POST['getqrcode'])
    {
      $upload_dir = './static/images/getqrcode.jpg';
      
      if($_FILES["file"] && ($_FILES["file"]["type"] == "image/jpeg")) 
      {
        //msg($_FILES["file"]["name"], 'admin.php');
		move_uploaded_file($_FILES["file"]["tmp_name"],$upload_dir);
	  }
	}
	msg('设置成功', 'admin.php');
}

//wmj
$wmj = $db->getRow('select * from config where k="wmj"');

if (!$wmj) {
	$db->insert('config', array('k' => 'wmj'));
}

$wmj_config = json_decode($wmj['v'], true);

$smarty->assign("wmj", $wmj_config);

//weixin
$weixin = $db->getRow('select * from config where k="weixin"');

if (!$weixin) {
	$db->insert('config', array('k' => 'weixin'));
}

$weixin_config = json_decode($weixin['v'], true);

$smarty->assign("weixin", $weixin_config);

$smarty->assign('nav_setting', 'active');

$smarty->display('admin_setting.html');
