<script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/layer.js"></script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/laydate/laydate.js"></script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/jquery.validate.min.js"></script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/messages_zh.js"></script>
<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/login.css" rel="stylesheet" type="text/css">
<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/register.css" rel="stylesheet" type="text/css">
<script>
  $(function () {
    //            邮箱找回
    $(".retrieve-email").click(function () {
      $("#pb-fine").show();
      $("#ph-fine").hide();
      $(this).removeClass("on");
      $(this).siblings().addClass("on")
    });
    //            手机找回
    $(".find-mobile").click(function () {
      $("#pb-fine").hide();
      $("#ph-fine").show();
      $(this).removeClass("on");
      $(this).siblings().addClass("on")
    });
    //            密码重置
    $(".ph-fine").click(function () {
      //$(".find-back").hide();
      //$(".password-reset").show();
    });
    //            重置成功
    $(".confirm-reset").click(function () {
      //$(".password-reset").hide();
      //$(".password-reset-succeed").show();
    });
    //            激活邮箱
    $(".pb-fine").click(function () {
      /*$(".find-back").hide();
      $(".pb-password-reset-succeed").show();
      return false;*/
    });
    //            邮箱密码重置
    $(".pb-sure,.pb-login").click(function () {
      /*$(".find-back,.pb-password-reset-succeed").hide();
      $(".password-reset").show();
      return false;*/
      location.href='/site/index.php?act=login&op=login';
    });
    //立即登录,跳转到登录页面


    $('.test-code').bind('click', function(){
      var $this = $(this);
      $this.prop('disabled', true);
      $.ajax({
        url: '<?php echo SITE_SITE_URL; ?>/index.php?act=login&op=sendCode',
        data: {phone: $('#ph_name_phone').val(), action:'findPassword'},
        type: 'post',
        dataType: 'json',
        success:function(res) {
          if (res.status == 0) {
            layer.alert(res.msg, {icon:3}, function (index) {
              $this.prop('disabled', false);
              layer.close(index);
            });
          } else if(res.data < 1) {
            $this.text('获取验证码').prop('disabled', false);
          } else {
            //$this.val(res.data+'秒后可重发');
            smsDownTime($this,res.data);
          }
        }
      });
    });
//    点击忘记密码，默认跳转到手机找回页面
    $(".find-mobile").trigger("click")
  });
</script>
<div class="login-banner">
  <div class="m-loginBody">
    <div class="m-loginWrap clearfix find-back">
      <div class="loginForm clearfix">
        <div class="login-title clearfix">
          <h2 class="find-mobile on">手机找回</h2>
          <h4 class="clearfix retrieve-email">邮箱找回</h4>
        </div>
        <div class="loginForm-box">
          <!--                    邮箱找回-->
          <div class="login-box" id="pb-fine">
            <form method="post" id="form-resetpassword-email" class="form clr">
              <?php Security::getToken(); ?>
              <input type="hidden" name="form_submit" value="ok"/>
              <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo SHOP_SITE_URL; ?>"/>
              <input type="hidden" name="nchash" id="nchash" value="<?php echo getNchash(); ?>"/>
              <div class="field ph-hide username-field pb-fine-input">
                <label for="TPL_username_1">
                  <i class="ico ico-user" title=""></i>
                </label>
                <input name="ph_name" class="fine-text" autocomplete="off" value="" placeholder="请输入电子邮箱"
                       id="ph_name_email"/>
                            <span class="nickx">
                                <i class="iconfont"></i>
                            </span>
              </div>
              <div class="submit">
                <button type="submit" class="J_Submit pb-fine">找　回</button>
              </div>
            </form>
            <div class="shell">
              <a href="<?php echo SITE_SITE_URL?>/index.php?act=login&op=login">想起来了，直接登录>></a>
            </div>
          </div>

          <!--                    手机找回-->
          <div class="login-box" id="ph-fine" style="display:none;">
            <form method="post" id="form-resetpassword-phone" class="form clr">
              <?php Security::getToken(); ?>
              <input type="hidden" name="form_submit" value="ok"/>
              <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo SHOP_SITE_URL; ?>"/>
              <input type="hidden" name="nchash" id="nchash" value="<?php echo getNchash(); ?>"/>
              <div class="field ph-hide username-field">
                <label for="TPL_username_1">
                  <i class="ico ico-user" title=""></i>
                </label>
                <input name="ph_name_phone" class="fine-text" autocomplete="off" value="" placeholder="请输入手机号码" id="ph_name_phone" required/>
                <span class="nickx">
                  <i class="iconfont"></i>
                </span>
              </div>
              <div class="input-tip">
                <span id="form-account-error" class="error"></span>
              </div>
              <div class="field valid-field in-validation">
                <label id="password-label" for="TPL_password_1">
                  <i class="ico ico-valid" title="验证码"></i>
                </label>
<!--                            <span class="J_StandardPwd">-->
								<input name="captcha" type="text" class="fine-text input-validation" id="captcha" placeholder="请输入验证码"
                       pattern="[0-9]{6}" title="输入验证码" autocomplete="off" maxlength="6"  style="width: 144px;
    height: 18px;">
                              
<!--                            </span>-->
                                <span class="get-code">
                                    <button name="" type="" class="test-code" id="">获取验证码</button>
                                </span>
              </div>
              <div class="input-tip">
                <span id="form-account-error" class="error"></span>
              </div>
              <div class="submit Jph-fine">
                <button type="submit" class="J_Submit ph-fine">找　回</button>
              </div>
            </form>
            <div class="shell">
              <a href="<?php echo SITE_SITE_URL?>/index.php?act=login&op=login">想起来了，直接登录>></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--        密码重置-->
    <div class="m-loginWrap clearfix password-reset" style="display: none;">
      <div class="loginForm clearfix">
        <div class="login-title clearfix">
          <p class="clearfix password-reset-p">密码重置</p>
        </div>
        <div class="loginForm-box">
          <div class="login-box" id="ph-fine">
            <form method="post" id="form-resetpassword-reset" class="form clr">
              <?php Security::getToken(); ?>
              <input type="hidden" name="form_submit" value="ok"/>
              <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo SHOP_SITE_URL; ?>"/>
              <input type="hidden" name="nchash" id="nchash" value="<?php echo getNchash(); ?>"/>
              <div class="field pwd-field">
                <label id="password-label" for="TPL_password_1">
                  <i class="ico ico-pass" title=""></i>
                </label>
                  <input type="password" name="password" id="cz-password" class="fine-text" placeholder="请输入6-20个字符的输入密码" pattern="[\S]{6}[\S]*">
              </div>
              <div class="input-tip">
                <span id="form-account-error" class="error"></span>
              </div>
              <div class="field pwd-field">
                <label id="password-label" for="TPL_password_1">
                  <i class="ico ico-pass" title="确认密码"></i>
                </label>
                <input type="password" name="re_pwd" id="re_pwd" class="fine-text" placeholder="请确认密码" pattern="[\S]{6}[\S]*">
              </div>
              <div class="input-tip">
                <span id="form-account-error" class="error"></span>
              </div>
              <div class="submit Jph-fine">
                <button type="submit" class="J_Submit confirm-reset">确认重置</button>
              </div>
            </form>
            <div class="shell">
              <a href="<?php echo SITE_SITE_URL.'/index.php?act=login&op=login'?>">想起来了，直接登录>></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--        重置成功-->
    <div class="m-loginWrap clearfix password-reset-succeed" style="display:none;">
      <div class="loginForm clearfix">
        <div class="login-title clearfix">
          <p class="clearfix password-reset-p">密码重置</p>
        </div>
        <div class="login-box" id="pb-success">
          <form action="" method="post" id="form_reg" class="form clr">
            <div class="pb-reg-suc">
              <div class="pb-reg-success">
                <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/dg.png"/>
                <p>重置成功</p>
              </div>
            </div>
            <div class="submit">
              <button type="button" class="J_Submit pb-login">立即登录</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--        邮箱密码重置-->
    <div class="m-loginWrap clearfix pb-password-reset-succeed" style="display:none;">
      <div class="loginForm clearfix">
        <div class="login-title clearfix">
          <p class="clearfix password-reset-p">密码重置</p>
        </div>
        <div class="login-box" id="pb-JSP">
          <form action="" method="post" id="form_reg" class="form clr">
            <div class="pb-reg-suc">
              <div class="pb-reg-success">
                <p class="pb-reg-link">密码重置链接已经发送到您的邮箱：</p>
                <p class="pb-reg-address"></p>
                <p class="pb-reg-activate">赶快去登录邮箱重置账号吧！</p>
              </div>
            </div>
            <div class="submit">
              <button type="button" class="J_Submit pb-sure">确定</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/login/login-reg.js"></script>
