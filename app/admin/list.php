<?php

$db = ConnectMysqli::getIntance();

$wmj = $db->getRow('select * from config where k="wmj"');
	
$config = json_decode($wmj['v'], true);

if ($_GET['do'] == 'del') {
	
	$lock = $db->getRow('select * from locks where id='.$_GET['id']);
	
	if (!$lock) {
		msg('非法访问', 'admin.php?ac=list');
	}
	
	if ($config['aeskey']) {
		$lock['sn'] = aesEncrypt($lock['sn'], $config['aeskey']);
	}
	
	$result = httpPost('https://www.wmj.com.cn/api/dellock.html?appid='.$config['appid'].'&appsecret='.$config['appsecret'], $lock['sn']);
 
	$result = json_decode(trim($result, "\xEF\xBB\xBF"), true);
	
	if ($result['state']) 
    {
		$db->deleteOne("locks", "id=".$_GET['id']);
	}
    else
    {
      $db->deleteOne("locks", "id=".$_GET['id']);
      msg('设备不属于当前配置的appid和appsecret,只删除本库数据', 'admin.php?ac=list');
    }
	msg($result['state_msg'], 'admin.php?ac=list');
}

if ($_GET['do'] == 'state') {
	
	$db->update('locks', array('state' => $_GET['state']), 'id='.$_GET['id']);
	header('Location:admin.php?ac=list');
	die;
}

$count = $db->getRow('select COUNT(*) as count from locks');

if ($count) {
	
	$pageshow = 10;

	$page_max = ceil($count['count'] / $pageshow); 

	$page = intval($_GET['page']);

	if ($page <= 0) $page = 1;

	if ($page > $page_max) $page = $page_max;

	$pagesize = ($page - 1) * $pageshow;

	$sql = 'select * from locks limit '.$pagesize.','.$pageshow;

	$list = $db->getAll($sql);

	foreach ($list as $key => $value) {
		
		if ($config['aeskey']) {
			$value['sn'] = aesEncrypt($value['sn'], $config['aeskey']);
		}
		$postlock = httpPost('https://www.wmj.com.cn/api/lockstate.html?appid='.$config['appid'].'&appsecret='.$config['appsecret'], $value['sn']);
	 
		$result = json_decode(trim($postlock, "\xEF\xBB\xBF"), true);
		
		if ($result['data']['online']) {
			$list[$key]['online'] = '<span class="label label-success">在线</span>';
		} else {
			$list[$key]['online'] = '<span class="label label-warning">离线</span>';
		}
		if ($value['state']) {
			$list[$key]['state'] = '<a href="admin.php?ac=list&do=state&id='.$value['id'].'&state=0"><span class="label label-success">启用中</span></a>';
		} else {
			$list[$key]['state'] = '<a href="admin.php?ac=list&do=state&id='.$value['id'].'&state=1"><span class="label label-warning">停用中</span></a>';
		}
	}

	if ($page_max != 1) {
		
		$page_str = '<a href="javascript:;" class="btn btn-default btn-sm">'.$page.' / '.$page_max.'</a>&nbsp;&nbsp;';

		if ($page == 1) {
			$page_str .= '<a href="javascript:;" class="btn btn-default btn-sm">上一页</a>&nbsp;&nbsp;<a href="admin.php?ac=list&page='.($page+1).'" class="btn btn-default btn-sm">下一页</a>';
		} elseif ($page == $page_max) {
			$page_str .= '<a href="admin.php?ac=list&page='.($page-1).'" class="btn btn-default btn-sm">上一页</a>&nbsp;&nbsp;<a href="javascript:;" class="btn btn-default btn-sm">下一页</a>';
		} else {
			$page_str .= '<a href="admin.php?ac=list&page='.($page-1).'" class="btn btn-default btn-sm">上一页</a>&nbsp;&nbsp;<a href="admin.php?ac=list&page='.($page+1).'" class="btn btn-default btn-sm">下一页</a>';
		}
	}
}

$smarty->assign('page_str', $page_str);

$smarty->assign('list', $list);

$smarty->assign('nav_list', 'active');

$smarty->display('admin_list.html');