<?php
/* Smarty version 3.1.30, created on 2019-12-22 21:45:01
  from "/www/wwwroot/lbcc.weimenjin.cn/templates/admin_edit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5dff735d6153e3_29283921',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a561b85cceeadf7758b64f1e6e041bf30a070195' => 
    array (
      0 => '/www/wwwroot/lbcc.weimenjin.cn/templates/admin_edit.html',
      1 => 1577022296,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:admin_header.html' => 1,
  ),
),false)) {
function content_5dff735d6153e3_29283921 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:admin_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="main">
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">编辑门禁设备</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-auth-small" action="admin.php?ac=edit" method="post">
                                <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['id'];?>
">
                                <input type="text" name="name" class="form-control" placeholder="请输入设备名称" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['name'];?>
">
                                <br>
                                <input type="text" name="sn" class="form-control" placeholder="请输入设备序列号" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['sn'];?>
">
                                <br>
                                <input type="text" name="sim" class="form-control" placeholder="如果是GPRS版，请输入设备SIM卡号" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['sim'];?>
">
                                <br>
                                <input type="text" name="distance" class="form-control" placeholder="开门距离/米" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['distance'];?>
">
                                <br>
                                <input type="text" name="lat" id="lat" class="form-control" placeholder="纬度" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['lat'];?>
">
                                <br>
                                <input type="text" name="lng" id="lng" class="form-control" placeholder="经度" value="<?php echo $_smarty_tpl->tpl_vars['locks']->value['lng'];?>
">
                                <br>
                                <button type="submit" class="btn btn-primary">编辑设备</button>
                            </form>
                            <div style="font-size:14px;line-height:30px;height:30px;">点击地图，拾取经纬度坐标</div>
                            <div id="container" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div>
<?php echo '<script'; ?>
 charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&key=LJOBZ-VFRHF-Q3FJR-JDAWT-KTNIZ-GQFBJ"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript">
        var lat = "<?php echo $_smarty_tpl->tpl_vars['locks']->value['lat'];?>
";
        var lng = "<?php echo $_smarty_tpl->tpl_vars['locks']->value['lng'];?>
";
        var citylocation,map,marker = null;
        var markersArray = '';
        var init = function() {
            var center = new qq.maps.LatLng(lat,lng);
            map = new qq.maps.Map(document.getElementById('container'),{
                center: center,
                zoom: 13
            });
            //获取城市列表接口设置中心点
            citylocation = new qq.maps.CityService({
                complete : function(result){
                    console.log(result);
                    map.setCenter(result.detail.latLng);
                }
            });
            var label = new qq.maps.Label({
                 map: map,
                 offset: new qq.maps.Size(15,-12),
                 draggable: false,
                 clickable: false
            });
            //调用searchLocalCity();方法    根据用户IP查询城市信息。
            if (lat == '' || lng == '') {
                citylocation.searchLocalCity();
            }
            qq.maps.event.addListener(map, "click", function (e) {
                var anchor = new qq.maps.Point(16, 16),
                size = new qq.maps.Size(32, 32),
                origin = new qq.maps.Point(0, 0);
                if (markersArray != '') {
                    markersArray.setMap(null);
                }
                var icon = new qq.maps.MarkerImage('/static/images/map.png', size, origin, anchor);
                var marker=new qq.maps.Marker({
                    icon: icon,
                    position:e.latLng,
                    map:map
                });
                markersArray = marker;
                // console.log(e);
                $("#lat").val(e.latLng.lat);
                $("#lng").val(e.latLng.lng);
                var latlng = e.latLng;
                label.setPosition(latlng);
                label.setContent(latlng.getLat().toFixed(6) + "," + latlng.getLng().toFixed(6));
            });
        }
        window.onload = function() {
            init();
        }
    <?php echo '</script'; ?>
>
</div>
    <?php echo '<script'; ?>
 src="static/assets/js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="static/assets/js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <!-- <?php echo '<script'; ?>
 src="static/assets/js/common.js"><?php echo '</script'; ?>
> -->
</body>
</html>
<?php }
}
