<div class="btn-main" style="height: auto;">
        <input type="hidden" id="tab_id" name="tab_id" value="1" />
        <label>账号</label>
        <input type="text" id="name" class="search_input"  value="<?php echo $_GET['name'] ?>" onkeydown="search(event);" />
        <label>姓名</label>
        <input type="text" id="xm" class="search_input"  value="<?php echo $_GET['xm'] ?>" onkeydown="search(event);" />
        <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(1, this)" class="choose">启用</a></li>
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(2, this)">停用</a></li>
        </ul>
    </div>
    <div class="operationsbox">
	<a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>
    <div class="pro-center-box" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">邮箱</th>
                <th scope='col' nowrap="nowrap">姓氏</th>
                <th scope='col' nowrap="nowrap">名称</th>
                <th scope='col' nowrap="nowrap">电话</th>
                <th scope='col' nowrap="nowrap">地址</th>
                <th scope='col' nowrap="nowrap">登陆次数</th>
                <th scope='col' nowrap="nowrap">最后登录</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
    </div>
    <div id="pageSpace"></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        initData(1);
    });
    function initData(page) {
        $('#loading-mask').show();
        var url = '<?php echo SITE_SITE_URL ?>/index.php?act=member&op=get_data';
        var status=getIdValue('tab_id'),name = getIdValue('name'),phone=getIdValue('phone'),province=$('#province option:selected').text(),city=$('#city option:selected').text(),xm=getIdValue('xm');

        $.ajax({
            url: url,
            data: {curpage: page,status:status,name:name,xm:xm,phone:phone,province:province,city:city},
            type: 'get',
            dataType: 'json',
            success: function (res) {
                $('#loading-mask').hide();
                $('.hover').remove();
                $('.order-table-box tr:gt(0)').remove();
                if (res.status == 1) {
                    var r = '';
                    var html = '';
                    for (var i in res.data) {
                        r = res.data[i];
                        html += '<tr height="30" class="hover">';
                        html += '<td scope="row" align="center">' + r.u_name + '</td>';
                        html += '<td scope="row" align="center">' + r.first_name + '</td>';
                        html += '<td scope="row" align="center">' + r.last_name + '</td>';
                        html += '<td scope="row" align="center">' + r.phone + '</td>';
                        html += '<td scope="row" align="center">' + r.address + '</td>';
                        html += '<td scope="row" align="center">' + r.login_num + '</td>';
                        html += '<td scope="row" align="center">' + r.login_time + '</td>';
                        html += '<td scope="row" align="center"><a href="javascript:;" onclick="repwd(' + r.u_id + ')">密码重置</a>';
                        if(r.status == 1){
                            html += ' | <a href="javascript:;" onclick="op(' + r.u_id + ',2)">停用</a>';
                        }else{
                            html += ' | <a href="javascript:;" onclick="op(' + r.u_id + ',1)">启用</a>';
                        }
                        html += ' | <a href="javascript:;" onclick="setting(' + r.u_id + ')">设置</a>';
                        html += '</td>';
                        html += '</tr>';
                    }
                    $('#pageSpace').show().html(res.page);
                } else {
                    html = '<tr height="30" class="hover">';
                    html += '<td scope="row" align="center" colspan="20">' + res.msg + '</td>';
                    html += '</tr>';
                    $('#pageSpace').hide();
                }
                $('#count_data').html(res.count);
                $('.order-table-box').show().append(html);
                initTable();
            }
        });
    }
    function repwd(u_id){
        layer.confirm('确定执行此操作？',{icon:3},function(){
            var url = '<?php echo urlShop('member','reset_pwd')?>';
            $.getJSON(url,{u_id:u_id},function(res){
                layer.alert(res.msg,function(){
                    layer.closeAll();
                    initData(1);
                });
            });
        });
    }
    function op(u_id,status){
        layer.confirm('确定执行此操作？',{icon:3},function(){
            var url = '<?php echo urlShop('member','change_status')?>';
            $.getJSON(url,{u_id:u_id,status:status},function(res){
                layer.alert(res.msg,function(){
                    layer.closeAll();
                    initData(1);
                });
            });
        });
    }
    function setting(u_id){
        layer.open({
            type: 2,
            id: 'sp_detail',
            title: '设置',
            fix: false,
            maxmin: false,
            //area: ['800px'],
            shadeClose: true, //点击遮罩关闭
            content: '<?php echo urlShop('member','setting')?>&u_id='+u_id
        });
    }
    function show_sub(obj,t){
        var url = "<?php echo urlShop('user','get_area')?>",area_id=$(obj).val(),t=t+1;
        if(!area_id){
            $(obj).nextAll('select').remove();
            return false;
        }
        if(t == 2){
            return false;
        }
        var name = t == 1 ? 'city' : 'area';
        $.getJSON(url,{area_id:area_id},function(res){
            if(res.status){
                $(obj).nextAll('select').remove();
                var html = '<select name="'+name+'" id="'+name+'" class="areas" onchange="show_sub(this,'+t+')">';
                html += '<option value="">请选择</option>';
                for(var i in res.data){
                    html += '<option value="'+res.data[i].area_id+'">'+res.data[i].area_name+'</option>';
                }
                html += '</select>';
                t++;
                $(obj).after(html);
            }else {
                $(obj).nextAll('select').remove();
            }
        });
    }

</script>
