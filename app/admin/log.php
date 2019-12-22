<?php

$db = ConnectMysqli::getIntance();

$count = $db->getRow('select COUNT(*) as count from log');

if ($count) {
	
	$pageshow = 50;

	$page_max = ceil($count['count'] / $pageshow); 

	$page = intval($_GET['page']);

	if ($page <= 0) $page = 1;

	if ($page > $page_max) $page = $page_max;

	$pagesize = ($page - 1) * $pageshow;

	$sql = 'select * from log,locks where log.lock_id=locks.id order by addtime desc limit '.$pagesize.','.$pageshow;

	$list = $db->getAll($sql);
	
	foreach($list as $key => $value) {
		$list[$key]['type'] = $value['type'] == 1 ? '进' : '出';
		$list[$key]['addtime'] = date('Y-m-d H:i:s', $value['addtime']);
	}
	
	if ($page_max != 1) {
		
		$page_str = '<a href="javascript:;" class="btn btn-default btn-sm">'.$page.' / '.$page_max.'</a>&nbsp;&nbsp;';

		if ($page == 1) {
			$page_str .= '<a href="javascript:;" class="btn btn-default btn-sm">上一页</a>&nbsp;&nbsp;<a href="admin.php?ac=log&page='.($page+1).'" class="btn btn-default btn-sm">下一页</a>';
		} elseif ($page == $page_max) {
			$page_str .= '<a href="admin.php?ac=log&page='.($page-1).'" class="btn btn-default btn-sm">上一页</a>&nbsp;&nbsp;<a href="javascript:;" class="btn btn-default btn-sm">下一页</a>';
		} else {
			$page_str .= '<a href="admin.php?ac=log&page='.($page-1).'" class="btn btn-default btn-sm">上一页</a>&nbsp;&nbsp;<a href="admin.php?ac=log&page='.($page+1).'" class="btn btn-default btn-sm">下一页</a>';
		}
	}
}

$smarty->assign('page_str', $page_str);

$smarty->assign('list', $list);

$smarty->assign('log_list', 'active');

$smarty->display('admin_log.html');