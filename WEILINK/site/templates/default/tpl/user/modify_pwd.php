<?php defined('InOmniWL') or exit('Access Invalid!');?>

<div class="btn-main">	
    <ul>
        <li><input type="submit" class="button" value="提交 " style="cursor: pointer;" onclick="psubmit()"/></li>
        <li><input type="reset" class="button" value="返回" style="cursor: pointer;" onclick="javascript:history.go(-1)"/></li>
    </ul>	
</div>
<div class="clear"></div>

<div class="center">

    <div class="wrap">
		<div class="tabmenu">
			<ul class="tab pngFix">
				<li class="active">
					<a href="#">修改密码</a>
				</li>
			</ul>
		</div>
        <div class="ncm-default-form">
            <form id="base_form"  method="post" action="">
                <input type="hidden" name="form_submit" value="ok" />
				<dl>
					<dt>旧密码：</dt>
					<dd>
					  <span class="w400">
						<input type="password" class="input-name"  name="old_pw" id="old_pw"  value="" placeholder="">
					  </span>
					  <span>&nbsp;&nbsp;</span>
					</dd>
				</dl>
				<dl>
					<dt>新密码：</dt>
					<dd>
					  <span class="w400">
						<input type="password" class="input-name" name="new_pw" id="new_pw"  value="" >
					  </span>
					  <span>&nbsp;&nbsp;</span>
					</dd>
				</dl>
				<dl>
					<dt>确认密码：</dt>
					<dd>
					  <span class="w400">
						<input type="password" class="input-name"  name="new_pw2" id="new_pw2"  value="" >
					  </span>
					  <span>&nbsp;&nbsp;</span>
					</dd>
				</dl>
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