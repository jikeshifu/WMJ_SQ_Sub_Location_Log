<?php
/* Smarty version 3.1.30, created on 2019-12-22 21:53:22
  from "/www/wwwroot/lbcc.weimenjin.cn/templates/index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5dff75521e0c70_10041588',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c8737c32bb670f72023fa96908d6a3992a5937c3' => 
    array (
      0 => '/www/wwwroot/lbcc.weimenjin.cn/templates/index.html',
      1 => 1577022797,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5dff75521e0c70_10041588 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!doctype html>
<html lang="cn" class="fullscreen-bg">
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    </head>
    <body>
        <div style="margin-top: 120px; text-align: center; font-size: 24px;">
            <img src="<?php echo $_smarty_tpl->tpl_vars['data']->value['img'];?>
?id=1000" style="width: 90px;"/>
            <p><?php echo $_smarty_tpl->tpl_vars['data']->value['msg'];?>
</p>
        </div>
        <?php echo '<script'; ?>
 src="/static/assets/js/jquery.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"><?php echo '</script'; ?>
>
        <!-- <?php echo '<script'; ?>
 type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"><?php echo '</script'; ?>
> -->
        <?php echo '<script'; ?>
 type="text/javascript">
            wx.config({
                //debug: true,
                appId: '<?php echo $_smarty_tpl->tpl_vars['signPackage']->value['appId'];?>
',
                timestamp: <?php echo $_smarty_tpl->tpl_vars['signPackage']->value['timestamp'];?>
,
                nonceStr: '<?php echo $_smarty_tpl->tpl_vars['signPackage']->value['nonceStr'];?>
',
                signature: '<?php echo $_smarty_tpl->tpl_vars['signPackage']->value['signature'];?>
',
                jsApiList: [
                    'getLocation',
                ]
            });
            wx.ready(function () {
                wx.getLocation({
                    type: 'gcj02',
                    success: function (res) {
                        var latitude = res.latitude;
                        var longitude = res.longitude;
                        // alert('res:'+JSON.stringify(res));
                        $.ajax({
                            url:'/openlock.php',
                            data:{
                                'lat' : latitude,
                                'lng' : longitude,
                                'id' : "<?php echo $_smarty_tpl->tpl_vars['jspost']->value['id'];?>
",
                                'type' : "<?php echo $_smarty_tpl->tpl_vars['jspost']->value['type'];?>
",
                                'nickname' : "<?php echo $_smarty_tpl->tpl_vars['jspost']->value['nickname'];?>
",
                                'openid' : "<?php echo $_smarty_tpl->tpl_vars['jspost']->value['openid'];?>
"
                            },
                            type:'post',
                            dataType:'json',
                            success:function(data) {
                                if (data.code == 1) {
                                    $("img").attr("src",'/static/images/success.png');
                                    $("p").html('开门成功');
                                }else{
                                    $("img").attr("src",'/static/images/error.png');
                                    $("p").html(data.msg);
                                    // alert('data:'+JSON.stringify(data));
                                }
                            },
                            error: function (aaa) {
                                // alert('aaa:'+JSON.stringify(aaa));
                            }
                        });
                    },
                    fail: function (res) {
                        // alert('fail-res:'+JSON.stringify(res));
                    }
                });
            });
        <?php echo '</script'; ?>
>
    </body>
</html><?php }
}
