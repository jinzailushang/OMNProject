
<div class="btn-main">	
    <ul>
        <li><input type="submit" class="button" value="提交 " style="cursor: pointer;" onclick="psubmit()"/></li>
        <li><input type="reset" class="button" value="返回" style="cursor: pointer;" onclick="javascript:history.go(-1)"/></li>
    </ul>	
</div>

<div class="clear"></div>

<div class="center">
    <div class="pro-center-con">
        <!-- begin goods-tabs-->

        <div class="goods-category-box">
            <div class="goods-details">
                <form id="house_form"  method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="form_submit" value="ok" />
                    <input type="hidden" name="u_id" value="<?php echo $output['info']['u_id']?>" />
                    <div class="goods-details-title">上传身份证</div>
                    <div class="details-wrap">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th scope="row">客户订单号：</th>
                                <td><input type="text" value="" name="customer_code" id="customer_code" class="input-name" onkeydown="search(event);"/></td>
                            </tr>

                            <tr>
                                <th scope="row">收件人姓名：</th>
                                <td><input type="text" value="" name="first_name" id="first_name" class="input-name"/></td>
                            </tr>
                            <tr>
                                <th scope="row">身份证号码：</th>
                                <td><input type="text" value="" name="last_name" id="last_name" class="input-name"/></td>
                            </tr>
                            
                            <tr>
                                <th scope="row">身份证正面：</th>
                                <td>
                                    <p><img src="" id="id_card_front_img"/></p>
                                    <input type="file" value="" name="id_card_front" id="id_card_front"  class="input-name"/>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">身份证反面：</th>
                                <td>
                                    <p><img src="" id="id_card_back_img"/></p>
                                    <input type="file" value="" name="id_card_back" id="id_card_back"  class="input-name"/>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function psubmit(){
        var province = getIdValue('province'),city = getIdValue('city'),area = getIdValue('area');
        if(province){
            if(!city || !area){
                layer.alert('所在地区请选择完整！',{icon:2});
                return false;
            }
        }
        $('#house_form').submit();
    }
</script>