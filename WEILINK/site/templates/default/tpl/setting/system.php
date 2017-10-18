
<div class="btn-main">	
    <ul>
        <li><input type="submit" class="button" value="提交 " style="cursor: pointer;" onclick="psubmit()"/></li>
        <li><input type="reset" class="button" value="重置" style="cursor: pointer;" onclick="location.reload()"/></li>
        <li><input type="reset" class="button" value="返回" style="cursor: pointer;" onclick="javascript:history.go(-1)"/></li>
    </ul>	
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" onclick="changeTabs('base', this)" class="choose">基本设置<font color="red"></font></a></li>
        </ul>
    </div>
    <div class="goods-category-box">
        <input type="hidden" id="curr" value="base" />
        <div class="setting base">
            <form id="base_form"  method="post" action="">
                <input type="hidden" name="form_submit" value="ok" />
                <div class="details-wrap">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="tab1">	
                            <tr>
                                <th scope="row" style="width:200px">是否打开计划任务：</th>
                                <td><input type="text" class="input-name"  name="is_open_scan_action" value="<?php echo $output['setting']['is_open_scan_action']?>" placeholder=""></td>
                            </tr>
                            <tr>
                                <th scope="row">是否可以添加订单：</th>
                                <td><input type="text" class="input-name" name="is_order_add_action" value="<?php echo $output['setting']['is_order_add_action']?>" ></td>
                            </tr>
                             <tr>
                                <th scope="row">仓储失败是否重推：</th>
                                <td><input type="text" class="input-name" name="is_open_storage_failure_error_push" value="<?php echo $output['setting']['is_open_storage_failure_error_push']?>" ></td>
                            </tr>
                        </table>
                </div>
            </form>
        </div>
   
    </div>
</div>


<script type="text/javascript">
    function changeTabs(id,obj){
        $('.navTwo_menu>li>a').removeClass('choose');
        $(obj).addClass('choose');
        $('.setting').hide();
        $('.'+id).show();
        $('#curr').val(id);
    }
    function psubmit(){
        var curr = $('#curr').val();
        $('#'+curr+'_form').submit();
    }
    
    
</script>