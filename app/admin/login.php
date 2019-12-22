<?php

if ($_POST) {
	
	if (!$_POST['username'] || !$_POST['password']) {
		
		msg('用户名和密码不能为空', 'admin.php');
	}
	
	$db = ConnectMysqli::getIntance();
	
	$sql = 'select * from admin where username="'.$_POST['username'].'"';
	
	$list = $db->getRow($sql);
	
	if ($list['password'] == md5($_POST['password'])) {
		$_SESSION['id'] = $list['id'];
		$_SESSION['username'] = $list['username'];
		
		header('Location:admin.php');
	} else {
		
		msg('用户名或密码错误', 'admin.php');
	}
}

if ($_GET['do'] == 'out') {
	
	session_unset();
	session_destroy();
	
	msg('已成功退出', 'admin.php');
}

$smarty->display('admin_login.html');
