<?php
/* Smarty version 3.1.30, created on 2019-12-22 21:25:43
  from "/www/wwwroot/lbcc.weimenjin.cn/templates/admin_setting.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5dff6ed706d8e8_44436574',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a339bea7d300a250326bc981a6e8c8c32c700315' => 
    array (
      0 => '/www/wwwroot/lbcc.weimenjin.cn/templates/admin_setting.html',
      1 => 1565157216,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_header.html' => 1,
    'file:admin_footer.html' => 1,
  ),
),false)) {
function content_5dff6ed706d8e8_44436574 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:admin_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="main">
	<div class="main-content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title">微信公众号设置</h3>
						</div>
						<div class="panel-body">
							<form class="form-auth-small" action="admin.php?ac=setting" method="post">
								<input type="hidden" name="weixin" value="weixin"/>
								<input type="text" name="appid" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['weixin']->value['appid'];?>
" placeholder="请输入公众号的APPID">
								<br/>
								<input type="text" name="appsecret" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['weixin']->value['appsecret'];?>
" placeholder="请输入公众号的APPSECRET">
								<br/>
								<button type="submit" class="btn btn-primary">保 存</button>
							</form>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title">微门禁API接入设置</h3>
						</div>
						<div class="panel-body">
							<form class="form-auth-small" action="admin.php?ac=setting" method="post">
								<input type="hidden" name="wmj" value="wmj"/>
								<input type="text" name="appid" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['wmj']->value['appid'];?>
" placeholder="请输入微门禁提供的APPID">
								<br>
								<input type="text" name="appsecret" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['wmj']->value['appsecret'];?>
" placeholder="请输入微门禁提供的APPSECRET">
								<br>
								<input type="text" name="aeskey" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['wmj']->value['aeskey'];?>
" placeholder="如果需要加密的话，请输入微门禁提供的AES加密密钥">
								<br>
								<button type="submit" class="btn btn-primary">保 存</button>
							</form>
						</div>
					</div>
				</div>
                <div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title">上传域名验证文件(txt)</h3>
						</div>
						<div class="panel-body">
							<form class="form-auth-small" action="admin.php?ac=setting" method="post" enctype="multipart/form-data">
								<input type="hidden" name="domaintxt" value="domaintxt"/>
								<input type="file" name="file" id="file" />
								<br>
								<button type="submit" class="btn btn-primary">保 存</button>
							</form>
						</div>
					</div>
				</div>
                <div class="col-md-12">
					<div class="panel">
						<div class="panel-heading">
							<h3 class="panel-title">上传公众号二维码图片(注:只能上传.jpg)</h3>
						</div>
						<div class="panel-body">
							<form class="form-auth-small" action="admin.php?ac=setting" method="post" enctype="multipart/form-data">
								<input type="hidden" name="getqrcode" value="getqrcode"/>
								<input type="file" name="file" id="file" />
								<br>
								<button type="submit" class="btn btn-primary">保存</button>
							</form>
						</div>
					</div>
				</div>
              
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php $_smarty_tpl->_subTemplateRender("file:admin_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
