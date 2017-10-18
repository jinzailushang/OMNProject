<?php defined('InOmniWL') or exit('Access Invalid!');?>

<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/attrpage.css" rel="stylesheet" type="text/css" />

<div class="btn-main">
    
</div>
<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="tabs_all" onclick="changeTabs(1, this)">管理员<font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_noApp" onclick="changeTabs(2, this)"  >添加管理员 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(3, this)" class="choose">权限组 <font color="red"></font></a></li>
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
                <th scope='col' nowrap="nowrap" width="3%"></th>
                <th scope='col' nowrap="nowrap" width="12%">权限组</th>
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
    function del(gid){
        if(confirm('<?php echo $lang['gadmin_del_confirm'];?>')){
            location.href='index.php?act=admin&op=gadmin_del&gid='+gid;
        }
    }
    function initData(page) {
        $('#loading-mask').show();
        var url = SITE_SITE_URL + '/index.php?act=admin&op=get_admin_data';
        $.ajax({
            url: url,
            data: {curpage: page},
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
                        if(r.child > 0){
                            html += '<td scope="row"  width="3%" style="padding-left:10px"><a href="javascript:;" onclick="show_child('+r.gid+',this,1)">[+]</a></td>';
                        }else{
                            html += '<td scope="row"  width="3%" style="padding-left:10px">[-]</td>';
                        }
                        html += '<td scope="row" align="center" width="12%">' + r.gname + '</td>';
                        html += '<td scope="row" align="center" width="12%"> ';
                        if(r.parent_id > 0){
                            html += '<a href="index.php?act=admin&op=gadmin_set&gid=' + r.gid + '"><?php echo $lang['nc_edit'];?></a> | <a href="javascript:;" onclick="del(' + r.gid + ')"><?php echo $lang['admin_index_del_admin'];?></a>';
                        }else{
                            html += '超管禁止编辑 | 超管禁止删除';
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
    function show_child(gid,obj,t){
        $('#loading-mask').show();
        var kk = t+1;
        var left = kk * 15;
        var url = SITE_SITE_URL + '/index.php?act=admin&op=get_admin_data';
        $.ajax({
            url: url,
            data: {curpage: 1,gid:gid},
            type: 'get',
            dataType: 'json',
            success: function (res) {
                $('#loading-mask').hide();
                if (res.status == 1) {
                    var r = '';
                    var html = '';
                    for (var i in res.data) {
                        r = res.data[i];
                        html += '<tr height="30" class="child_'+r.gid+'">';
                        if(r.child > 0){
                            html += '<td style="padding-left:'+left+'px"><a href="javascript:;" onclick="show_child('+r.gid+',this,'+kk+')">[+]</a></td>';
                        }else{
                            html += '<td style="padding-left:'+left+'px">[-]</td>';
                        }
                        html += '<td align="center">' + r.gname + '</td>';
                        html += '<td align="center">';
                        html += '<a href="index.php?act=admin&op=gadmin_set&gid='+r.gid+'">编辑</a> | ';
                        html += '<a href="javascript:;" onclick="del('+r.gid+')">删除</a>';
                        html += '</td>';
                        html += '</tr>';
                    }
                    $(obj).closest('tr').after(html);
                    $(obj).closest('td').html('<a href="javascript:;" onclick="hide_child('+gid+',\'child_'+gid+'\',this,'+t+')">[-]</a>');
                } 
            }
        });
    }
    function hide_child(gid,cls,obj,t){
        var url = SITE_SITE_URL + '/index.php?act=admin&op=get_gadmin_subid';
        $.getJSON(url,{gid:gid},function(res){
            if(res.status){
                for(var i in res.data){
                    $('.child_'+res.data[i]).remove();
                }
                $(obj).closest('td').html('<a href="javascript:;" onclick="show_child('+gid+',this,'+t+')">[+]</a>');
            }
        });
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
</script>