<div class="btn-main" style="height: auto;">
        <input type="hidden" id="tab_id" name="tab_id" value="0" />
        <label>物流单号</label>
        <input type="text" id="scode" class="search_input"  value="<?php echo $_GET['scode'] ?>" onkeydown="search(event);" />

        <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" onclick="changeTabs(0, this)" class="choose">未使用</a></li>
            <li><a href="javascript:void(0);" onclick="changeTabs(1, this)">已使用</a></li>
        </ul>
    </div>
    <div class="operationsbox">
        <ul class="operNav">
            <li><input type="text" class="input-name"  id="num" name="num" value="" placeholder="请输入数量" style="margin-right:6px;"><a class="button"  href="javascript:;" onclick="build()" style="padding:3px">生成物流单号</a></li>
        </ul>
	<a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>
    <div class="pro-center-box" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
                <th scope='col' nowrap="nowrap">使用状态</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">使用时间</th>
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
        var url = '<?php echo SITE_SITE_URL ?>/index.php?act=shipment_code&op=get_data';
        var status = getIdValue('tab_id'),scode = getIdValue('scode');

        $.ajax({
            url: url,
            data: {curpage: page,status:status,scode:scode},
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
                        html += '<td scope="row" align="center">' + r.scode + '</td>';
                        html += '<td scope="row" align="center">威廉速递</td>';
                        html += '<td scope="row" align="center">' + r.status + '</td>';
                        html += '<td scope="row" align="center">' + r.ctime + '</td>';
                        html += '<td scope="row" align="center">' + r.utime + '</td>';
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
    //生成物流单号
    function build(){
        var url = "<?php echo urlShop('shipment_code','build')?>";
        var num = getIdValue('num');
        var reg = /^[0-9]\d*$/;
        if(!num || num == 0 || !reg.test(num) || num > 1000){
            layer.alert('请输入一个大于0少于1000的数字！',{icon:2});
            return false;
        }
        $.ajax({
            url:url,
            data:{num:num},
            type:'post',
            dataType:'json',
            success:function(res){
                if(res.status){
                    layer.alert('成功生成物流单号！',{icon:1},function(index){
                        layer.close(index);
                        $('#num').val('');
                        initData(1);
                    });
                }else{
                    layer.alert('生成物流单号失败！',{icon:2});
                }
            }
        });
    }

</script>
