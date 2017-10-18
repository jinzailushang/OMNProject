<?php defined('InOmniWL') or exit('Access Invalid!');?>

<div class="btn-main">	
    <ul>
        <li><input type="submit" class="button" value="提交 " style="cursor: pointer;" onclick="psubmit()"/></li>
        <li><input type="reset" class="button" value="返回" style="cursor: pointer;" onclick="javascript:history.go(-1)"/></li>
    </ul>	
</div>
<div class="clear"></div>

<div class="center">

    <div class="goods-category-box">
        <div class="setting base">
            <form id="base_form"  method="post" action="">
                <input type="hidden" name="form_submit" value="ok" />
                <div class="details-wrap">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="tab1">	
                            <tr>
                                <th scope="row" style="width:200px">旧密码：</th>
                                <td><input type="password" class="input-name"  name="old_pw" id="old_pw"  value="" placeholder=""></td>
                            </tr>
                            <tr>
                                <th scope="row">新密码：</th>
                                <td><input type="password" class="input-name" name="new_pw" id="new_pw"  value="" ></td>
                            </tr>
                            <tr>
                                <th scope="row">确认密码：</th>
                                <td><input type="password" class="input-name"  name="new_pw2" id="new_pw2"  value="" ></td>
                            </tr>

                        </table>
                </div>
            </form>
        </div>
    </div>
    
</div>


<script type="text/javascript">
    function psubmit(){
        var old_pw = $.trim($('#old_pw').val()),new_pw = $.trim($('#new_pw').val()),new_pw2 = $.trim($('#new_pw2').val()),err='';
        if(!old_pw){
            err +="请输入旧密码！\n";
        }
        if(!new_pw){
            err +="请输入新密码！\n";
        }
        if(new_pw != new_pw2){
            err +="新密码与确认密码不一致！\n";
        }
         if(err){
             alert(err);
             return false;
         }
        $('#base_form').submit();
    }
  
</script>