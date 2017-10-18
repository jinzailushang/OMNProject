<!--<link href="templates/default/css/addOrder.css" rel="stylesheet" type="text/css">-->

<style>
    tr span{float: left;width:100px}
    tr img{padding:3px}
    .rowcol{position: relative;left:100px;border:0 !important}
    .sf {
        position: relative;
        width: 200px;
        height: 114px;
        background: #ececec;
        border-radius: 4px;
    }
    .control-l p {
        color: #999;
        margin: 8px 44px 48px 40px;
        font-size: 14px;
    }
    .sf img {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        max-width: 100%;
        max-height: 100%;
    }
    .sf-fi {
        width: 126px;
        position: relative;
        top: -42px;
        left: 36px;
        cursor: pointer;
    }
    .input-name {
        margin-left: -26px;
        margin-top: -4px;
        width: 200px;
        height: 29px;
    }
    .oddListRow .button {
        width: 58px;
        height: 30px;
        background-color: #004188;
        color: #f4faff;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        margin: 0 auto;
        display: block;
    }
</style>
<table cellspacing="0" cellpadding="0" border="0"   style="padding:10px;width:96%;margin: 5px auto;font-size: 12px">
    <form id="h_form"  method="post" action=""  target="formsme">
        <input type="hidden" value="<?php echo $output['order_id']?>" name="order_id" id="order_id" class="input-name"/>
        <input type="hidden" value="" name="hidden_id_card_front" id="hidden_id_card_front" class="input-name"/>
        <input type="hidden" value="" name="hidden_id_card_back" id="hidden_id_card_back" class="input-name"/>

        
        <tr style="height:35px">
            <td><span>收件人姓名：</span><input type="text" value="<?php echo $output['info']['reciver_name'] ? $output['info']['reciver_name'] : ''?>" name="reciver_name" id="reciver_name" class="input-name"/></td>
        </tr>
        <tr style="height:35px">
            <td><span>身份证号码：</span><input type="text" value="<?php echo $output['info']['identity_code'] ? $output['info']['identity_code'] : ''?>" name="identity_code" id="identity_code" class="input-name"/></td>
        </tr>
    </form>
        <tr>
            <form id="front_form"  method="post" action="" enctype="multipart/form-data" target="formsme">

                <input type="hidden" value="<?php echo $output['order_id']?>" name="order_id" id="order_id" class="input-name"/>
                <td>
                    <label>身份证照片:</label>
                    <div class="control-l" style="    margin-left: 76px;
        margin-top: -12px;" >
                        <div class="sf">
                            <img src="<?php echo $output['info']['id_card_front']?>" onerror="this.src='templates/default/images/sfz.png'" id="id_card_front_img">
                        </div>
                        <p>上传身份证正面照片</p>
                        <input type="file" value="" name="id_card_front" id="id_card_front" class="rowcol sf-fi" onchange="showPic('front_form')">
                    </div>
                </td>
            </form>
            <form id="back_form"  method="post" action="" enctype="multipart/form-data" target="formsme">
                <input type="hidden" value="<?php echo $output['order_id']?>" name="order_id" id="order_id" class="input-name"/>
                <td>
                    <div class="control-l" style="    margin-left: -110px;
        margin-top: 2px;" >
                        <div class="sf">
                            <img src="<?php echo $output['info']['id_card_back']?>"
                                 onerror="src='templates/default/images/sff.png'" id="id_card_back_img">
                        </div>
                        <p>上传身份证反面照片</p>
                        <input type="file" value="" name="id_card_back" id="id_card_back" class="rowcol sf-fi" onchange="showPic('back_form')">
                    </div>
                </td>
                <form>
        </tr>
    </form>
    <tr  class="oddListRow s_jishuhang">
        <td colspan="2" style="text-align: center"><input type="submit" class="button" value="确定" onclick="psubmit()"/> </td>
    </tr>

    <IFRAME  height=0 marginHeight=0 marginWidth=0 scrolling=no width=0 name="formsme"  style="display:none"></IFRAME>
</table>
<script src="templates/default/js/order/IDCard.js"></script>
<script type="text/javascript">
    function showPic(id) {
        var posturl = "<?php echo urlShop('order','load_card')?>";
        $('#'+id).attr("action", posturl).submit();
    }
    function callback(res) {
        if (res.status == 1) {
            $('#id_card_front_img').attr('src',res.path);
        } else if(res.status == 2){
            $('#id_card_back_img').attr('src',res.path);
        }else {
            top.layer.alert(res.msg,{icon:2});
        }
    }
    function psubmit(){
        var  idCardFront = $("#id_card_front_img").attr("src");
        $("#hidden_id_card_front").val(idCardFront);
        var  idCardBack = $("#id_card_back_img").attr("src");
        $("#hidden_id_card_back").val(idCardBack);
        if (!(new IDValidator()).isValid($('#identity_code').val())) {
            top.layer.alert('无效的身份证号码',{icon:2});

            return false;
        }
        var posturl = "<?php echo urlShop('order','update_iden')?>";
        $('#h_form').attr("action", posturl).submit();
    }
    function update_after(res){
        if (res.status == 1) {
            top.layer.alert(res.msg,{icon:1},function(index){
                top.layer.closeAll();
            });
        }
    }

</script>