<?php

$db = ConnectMysqli::getIntance();

if ($_POST) {
	$sql = 'select * from admin where id='.$_SESSION['id'];
	
	$admin = $db->getRow($sql);
	
	if ($admin['password'] != md5($_POST['oldpwd'])) {
		msg('原始密码错误', 'admin.php?ac=pwd');
	}
	
	if ($_POST['pwd'] != $_POST['pwd2']) {
		msg('两次输入的密码不匹配', 'admin.php?ac=pwd');
	}
	
	$db->update('admin', array('password' => md5($_POST['pwd'])), 'id='.$_SESSION['id']);
	
	msg('密码修改成功，下次登录生效', 'admin.php?ac=pwd');
}


$smarty->assign('pwd_list', 'active');

$smarty->display('admin_pwd.html');