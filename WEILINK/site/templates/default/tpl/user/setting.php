<table cellspacing="0" cellpadding="0" border="0" class="" style="width:70%;margin: 5px auto;font-size: 12px">	
    <tbody>
        <input type="hidden" id="u_id" value="<?php echo $output['u_id']?>">
        <tr height="40"><td>结算方式 <select id="pay_method"  style="width: 70%">
            <option value="1" <?php if($output['info']['pay_method'] == 1) echo 'selected'?>>现付</option>
            <option value="2" <?php if($output['info']['pay_method'] == 2) echo 'selected'?>>充值抵扣</option>
            <option value="3" <?php if($output['info']['pay_method'] == 3) echo 'selected'?>>月结</option>
        </select></td></tr>
        <tr height="40"><td>绑定仓库 <select id="house_id" style="width: 70%">
            <option value="0">请选择</option>
            <?php foreach($output['house_list'] as $row):?>
            <option value="<?php echo $row['tid']?>" <?php if($output['info']['house_id'] == $row['tid']) echo 'selected'?>><?php echo $row['tc_name']?></option>
            <?php endforeach;?>
        </select></td></tr>

        <tr height="40">
            <td align="center" class="layui-layer-setwin">
                <a href="javascript:;" class="btn-enter" style="padding: 0 10px" onclick="psubmit()">保存</a>
                <a href="javascript:;" class="btn-enter" style="padding: 0 10px" onclick="close2()">关闭</a>
            </td>
        </tr>
    </tbody>
</table>
<script type="text/javascript">

    function psubmit(){
        var u_id = $('#u_id').val(),pay_method = $('#pay_method').val(),house_id = $('#house_id').val(),url='<?php echo urlShop('member','setting')?>';
        var index = parent.layer.load();
        $.ajax({
            url:url,
            data:{u_id:u_id,pay_method:pay_method,house_id:house_id,form_submit:'ok'},
            type:'post',
            dataType:'json',
            success:function(res){
                parent.layer.close(index);
                if(res.status){
                    parent.layer.alert(res.msg,{icon:1},function(){
                        parent.layer.closeAll();
                    });
                }else{
                    parent.layer.alert(res.msg,{icon:2});
                }
            }
        });
    }

</script>
