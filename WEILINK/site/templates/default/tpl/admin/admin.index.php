<?php defined('InOmniWL') or exit('Access Invalid!');?>

<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/attrpage.css" rel="stylesheet" type="text/css" />

<div class="btn-main">
        <input type="hidden" id="tab_id" name="tab_id" value="1" />
        <label>登录名</label>
        <input type="text" id="admin_name" name="admin_name" class="search_input" value="<?php echo $_GET['admin_name'] ?>" onkeydown="search(event);"/>
        <input type="button" class="button btn-query" value=" 查询  " onclick="initData(1)"/>

</div>
<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="tabs_all" onclick="changeTabs(1, this)" class="choose">管理员<font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_noApp" onclick="changeTabs(2, this)">添加管理员 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(3, this)">权限组 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(4, this)">添加权限组 <font color="red"></font></a></li>
        </ul>
    </div>
    <div class="operationsbox">
        <ul class="operNav">
            <li style="float:right"><a href="javascript:;" >共<span id="count_data"></span>条记录</a></li>
        </ul>
    </div>
    <div class="pro-center-box" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap" width="3%"><input name="select_all" class="selector" type="checkbox" value="" onClick="selectAll(this, 0)" style="vertical-align: middle;"/></th>
                <th scope='col' nowrap="nowrap" width="10%">登录名</th>
                <th scope='col' nowrap="nowrap" width="12%"  class="orderby" title="点击以排序" order_type="asc" onclick="orderbyquery(this, 'product_name')">上次登录</th>
                <th scope='col' nowrap="nowrap" width="6%" class="orderby" title="点击以排序" order_type="asc" onclick="orderbyquery(this, 'product_sn')">登录次数</th>
                <th scope='col' nowrap="nowrap" width="10" class="orderby" title="点击以排序" order_type="asc" onclick="orderbyquery(this, 'barcode')">权限组</th>
                <th scope='col' nowrap="nowrap" width="12%">操作</th>
            </tr>
        </table>
    </div>
	<div id="pageSpace"></div>
</div>	
<script type="text/javascript">
    $(document).ready(function () {
        var curpage = "<?php echo isset($_GET['curpage']) ? $_GET['curpage'] : 1 ?>";
        initData(curpage);
    })
    function del(admin_id){
        if(confirm('<?php echo $lang['nc_ensure_del'];?>')){
            location.href='index.php?act=admin&op=admin_del&admin_id='+admin_id;
        }
    }
    function initData(page) {
        $('#loading-mask').show();
        var url = SITE_SITE_URL + '/index.php?act=admin&op=get_data';
        var admin_name = $('#admin_name').val();
        $.ajax({
            url: url,
            data: {curpage: page,admin_name:admin_name},
            type: 'get',
            dataType: 'json',
            success: function (res) {
                $('#loading-mask').hide();
                $('.hover').remove();
                if (res.status == 1) {
                    var r = '';
                    var html = '';
                    for (var i in res.data) {
                        r = res.data[i];
                        html += '<tr height="30" class="hover">';
                        html += '<td scope="row" align="center" width="3%"><input name="select_all" class="selector" type="checkbox" value="" onClick="selectAll(this,0)" style="vertical-align: middle;"/></td>';
                        html += '<td scope="row" align="center" width="10%">' + r.admin_name + '</td>';
                        html += '<td scope="row" align="center" width="12%">' + r.login_time + '</td>';
                        html += '<td scope="row" align="center" width="6%">' + r.admin_login_num + '</td>';
                        html += '<td scope="row" align="center" width="10%">' + r.gname + '</td>';
                        html += '<td scope="row" align="center" width="12%"> ';
                        if(r.gid == 1){
                            html += '超级管理员不可编辑';
                        }else{
                            html += '<a href="javascript:void(0)" onclick="del(' + r.admin_id + ')"><?php echo $lang['admin_index_del_admin'];?></a> | <a href="index.php?act=admin&op=admin_edit&admin_id='+r.admin_id+'"><?php echo $lang['nc_edit'];?></a>';
                        }
                        html += '</tr>';
                    }
                    
                    $('#pageSpace').show().html(res.page);
                } else {
                    html = '<tr height="30" class="hover">';
                    html += '<td scope="row" align="center" colspan="8">' + res.msg + '</td>';
                    html += '</tr>';
                    $('#pageSpace').hide();
                }
                $('#count_data').html(res.count);
                $('.order-table-box').append(html);
            }
        });
    }
    //点击页码
    function clickpage(page) {
        initData(page);
    }
    //切换标签
    function changeTabs(v, obj) {
        $('.navTwo_menu>li>a').removeClass('choose');
        $(obj).addClass('choose');
        if(v == 1){
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin";
        }else if(v == 2){
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin_add";
        }else if(v == 3){
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=gadmin";
        }else{
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=gadmin_add";
        }
    }
    
    function search(evt) {
        var evt = evt ? evt : (window.event ? window.event : null);//兼容IE和FF
        if (evt.keyCode == 13) {
            initData(1)
        }
    }
</script>
