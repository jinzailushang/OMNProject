<script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/layer.js"></script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/laydate/laydate.js"></script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/jquery.validate.min.js"></script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/messages_zh.js"></script>

<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/login.css" rel="stylesheet" type="text/css">
<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/register.css" rel="stylesheet" type="text/css">
<div class="login-banner">
  <div class="m-loginBody">

    <div class="m-loginWrap clearfix">
      <div class="loginForm clearfix">
        <div class="login-title clearfix">
          <h2 class="clearfix login">登录</h2>
          <h4 class="resiger on">注册</h4>
        </div>

        <div class="loginForm-box">
          <div class="login-box" id="login">
            <form method="post" id="form_login" class="form clr">
<!--              --><?php //Security::getToken(); ?>
              <input type="hidden" name="form_submit" value="ok" />
              <input type="hidden" name="SiteUrl" id="SiteUrl" value="index.php?act=login&op=login" />
              <input type="hidden" name="nchash" id="nchash" value="<?php echo getNchash(); ?>" />
              <div>
                <div class="field ph-hide username-field">
                  <label for="TPL_username_1">
                    <i class="ico ico-user" title="会员名"></i>
                  </label>
                  <input name="user_name" class="login-text" autocomplete="off" value=""
                         placeholder="邮箱/会员名/8位ID" required="" id="user_name">
                                <span class="nickx">
                                    <i class="iconfont"></i>
                                </span>
                </div>
                <div class="input-tip">
                  <span id="user_name-error" class="error"></span>
                </div>
              </div>
              <div>
                <div class="field pwd-field">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-pass" title="会员名"></i>
                  </label>
                  <!--                            <span class="J_StandardPwd">-->
                  <input name="password" type="password" id="password" class="login-text"
                         autocomplete="off" required="" placeholder="输入密码" pattern="[\S]{6}[\S]*"
                         minlength="6" maxlength="20">
                  <!--                            </span>-->
                </div>
                <div class="input-tip">
                  <span id="password-error" class="error"></span>
                </div>
              </div>
              <div class="field valid-field" id="login-verfiy-code-field">
              </div>
              <div class="login-links">
                <div href="" class="b-unlogn">
                  <div class="b-unlogn-rp">
                                        <span class="rem-pd">
                                            <label>
                                              <input id="un-login" name="ten" type="checkbox" value="1"
                                                     class="un-login" />
                                              记住密码
                                            </label>
                                        </span>
                                        <span class="for-pd">
                                            <a href="index.php?act=login&op=forgotpassword"
                                               class="forget-password">忘记密码</a>
                                        </span>
                  </div>
                  <!--                                    <label for="un-login" class="un-login-check">十天内免登录</label></div>-->
                </div>
                <div id="login-msg" style="margin-bottom:5px;color:#f00"></div>
                <div class="submit">
                  <button type="button" id="login-enter" class="J_Submit" onclick="$(this).closest('form').submit()">登　录</button>
                </div>

              </div>
            </form>

          </div>
          <!--                    手机注册-->
          <div class="login-box" id="ph-resiger" style="display:none;">
            <form method="post" id="ph_form_reg" class="form clr"
                  action="<?php echo urlShop('login', 'register') ?>">
              <input type="hidden" name="form_submit" value="ok" />
              <!--                            <input type="hidden" name="form_submit" value="ok" />-->
              <div class="field ph-hide username-field">
                <!--                                <label for="TPL_username_1">-->
                <!--                                    <i class="ico ico-user" title=""></i>-->
                <!--                                </label>-->
                <select class="ph-select">
                  <option>中国</option>
                  <option>JP</option>
                  <option>USA</option>
                </select>
              </div>
              <div class="input-tip">
                <span id="form-account-error" class="error"></span>
              </div>
              <div>
                <div class="field ph-hide username-field">
                  <label for="TPL_username_1">
                    <i class="ico ico-user" title=""></i>
                  </label>
                  <input name="ph_phone" type="" class="login-text" autocomplete="off"
                         placeholder="请输入手机号码" id="ph_name" >
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <!--                            手机验证-->

              <div>
                <div class="field valid-field in-validation">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-valid" title="验证码"></i>
                  </label>
                  <input name="ph_captcha" type="text" class="login-text input-validation" id="ph_captcha" placeholder="请输入验证码"/>

                    <span class="get-code">
                        <button name="" type="" class="test-code" id="">获取验证码</button>
                      </span>
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <!--                            end手机验证码-->
              <div>
                <div class="field pwd-field">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-pass" title="输入密码"></i>
                  </label>
                  <!--                                    <span class="J_StandardPwd">-->
                  <input type="password" name="ph_password" id="ph_password" class="login-text"
                         placeholder="请输入6-20个字符的密码"  pattern="[\S]{6}[\S]*" />
                  <!--                                    </span>-->
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div>
                <div class="field pwd-field">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-pass" title="输入密码"></i>
                  </label>
                  <!--                                <span class="J_StandardPwd">-->
                  <input type="password" name="ph_re_pwd" id="ph_re_pwd" class="login-text"
                         placeholder="请确认密码"  pattern="[\S]{6}[\S]*"/>
                  <!--                                </span>-->
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div >
                <div href="" class="b-unlogn">
                  <input type="checkbox" class="checkbox valid" id="agree" name="agree" aria-required="true" aria-invalid="false">
                  <label for="un-login" class="un-login-check">同意<a target="_blank" href="index.php?act=login&op=registration_protocol_html" id="ph_protocol">《威廉注册协议》</a></label>
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>

              <div class="submit">
                <button type="submit" class="J_Submit ph-submit">立即注册</button>
              </div>
              <div class="use-enroll">
                <a href="javascript:" class="use-pb-reg">使用邮箱注册</a>
              </div>
            </form>
          </div>
          <!--                    手机注册成功-->
          <div class="login-box" id="register-success" style="display: none;">
            <form action="" method="post" id="form_reg" class="form clr">
              <div class="ph-reg-suc">
                <div class="ph-reg-success">
                  <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/dg.png"/>
                  <p>注册成功</p>
                </div>
              </div>
              <div class="submit">
                <button type="submit" class="J_Submit ph-sure">确定</button>
              </div>
            </form>
          </div>
          <!--                邮箱注册-->
          <div class="login-box" id="pb-resiger" style="display: none;">
            <form method="post" id="pb_form_reg" class="form clr"
                  action="<?php echo urlShop('login', 'register') ?>">
              <input type="hidden" name="form_submit" value="ok">
              <div>
                <div class="field ph-hide username-field">
                  <label for="TPL_username_1">
                    <i class="ico ico-user" title="电子邮箱"></i>
                  </label>
                  <input name="u_name" type="email" class="login-text" autocomplete="off"
                         placeholder="请输入电子邮箱" id="u_name" required="">
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div>
                <div class="field pwd-field">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-pass" title="输入密码"></i>
                  </label>
                  <!--                            <span class="J_StandardPwd">-->
                  <input type="password" name="pb_password" id="pb_password" class="login-text"
                         placeholder="请输入6-20个字符的密码" minlength="6" maxlength="20" required="" pattern="[\S]{6}[\S]*">
                  <!--                            </span>-->
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div>
                <div class="field pwd-field">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-pass" title="确认密码"></i>
                  </label>
                  <!--                            <span class="J_StandardPwd">-->
                  <input type="password" name="pb_re_pwd" id="pb_re_pwd" class="login-text"
                         placeholder="请确认密码" required="" pattern="[\S]{6}[\S]*">
                  <!--                            </span>-->
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div>
                <div class="field valid-field">
                  <label id="password-label" for="TPL_password_1">
                    <i class="ico ico-valid" title="验证码"></i>
                  </label>
                  <!--                            <span class="J_StandardPwd">-->
                  <input type="text" name="captcha" id="TPL_password_1" class="login-text"
                         placeholder="输入验证" pattern="[A-z0-9]{4}" autocomplete="off" maxlength="4"
                         required="">
                  <!--                            </span>-->
                  <div class="code">
                    <div class="arrow"></div>
                    <div class="code-img"><a href="JavaScript:void(0);"
                                             onclick="javascript:document.getElementById('codeimage-reg').src = 'index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash=&amp;t=' + Math.random();"
                                             class="change" title="刷新"><img
                          src="index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash="
                          name="codeimage" id="codeimage-reg" border="0" width="81" height="36"></a>
                    </div>
                  </div>
                </div>
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div>
                <!--                                <div class="login-links">-->
                <div href="" class="b-unlogn"  >
                  <input type="checkbox" class="checkbox valid" id="pb_agree" name="pb_agree" aria-required="true" aria-invalid="false">
                  <!--                                        <input id="un-register" name="" type="checkbox" value="" class="un-register" checked="" required="">-->
                  <label for="un-login" class="un-login-check">同意<a href="" id="pb_protocol">《威廉注册协议》</a></label>
                </div>
                <!--                                </div>-->
                <div class="input-tip">
                  <span id="form-account-error" class="error"></span>
                </div>
              </div>
              <div class="submit">
                <button type="submit" class="J_Submit pb-submit">立即注册</button>
              </div>
              <div class="use-ph">
                <a href="javascript:" class="use-ph-reg">使用手机注册</a>
              </div>
            </form>
          </div>
        </div>
        <!--            邮箱验证-->

        <div class="login-box" id="JSP" style="display: none;">
          <form action="" method="post" id="form_reg" class="form clr">
            <div class="pb-reg-suc">
              <div class="pb-reg-success">
                <p class="pb-reg-link">账号链接已经发送到您的邮箱：</p>
                <p class="pb-reg-address">339370563@qq.com</p>
                <p class="pb-reg-activate">赶快去登录邮箱激活账号吧！</p>
              </div>
            </div>
            <div class="submit">
              <button type="submit" class="J_Submit pb-sure">确定</button>
            </div>
          </form>
        </div>
        <!--            邮箱激活-->
        <div class="login-box" id="pb-success" style="display: none;">
          <form action="" method="post" id="form_reg" class="form clr">
            <div class="pb-reg-suc">
              <div class="pb-reg-success">
                <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/dg.png"/>
                <p>激活成功</p>
              </div>
            </div>
            <div class="submit">
              <button type="submit"  class="J_Submit pb-login">立即登录</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(function () {
    //按回车键登录
    $(document).keydown(function(event) {
      if (event.keyCode == 13) {
        $("#login-enter").click();
      }
    });

      $(".login").click(function () {
      $("#login").show();
      $("#JSP").hide();
      $("#pb-resiger").hide();
      $("#pb-success").hide();
      $("#ph-resiger").hide();
      $("#register-success").hide();
      $("#resiger").hide();
      $(this).removeClass("on");
      $(this).siblings().addClass("on")
    });
    $(".resiger").click(function () {
      $("#login").hide();
      $("#ph-resiger").show();
      $(this).removeClass("on");
      $(this).siblings().addClass("on")
    });
    //        手机注册成功页面
    //        $(".ph-submit").click(function () {
    //            $("#ph-resiger").hide();
    ////            $("#login").hide();
    //            $("#register-success").show();
    //            return false;
    //        });
    ////        验证邮箱页面
    //        $(".pb-submit").click(function () {
    //            $("#pb-resiger").hide();
    //            $("#resiger").hide();
    //            $(".login-title").show();
    ////            $("#login").hide();
    //            $("#JSP").show();
    //            return false;
    //        });
    //邮箱激活成功
    $(".pb-sure").click(function () {
      $("#JSP").hide();
      $("#pb-success").show();
      return false;
    });
    //        切换邮箱注册
    $(".use-pb-reg").click(function () {
      $("#ph-resiger").hide();
      $("#pb-resiger").show();
    });
    //        切换手机注册
    $(".use-ph-reg").click(function () {
      $("#pb-resiger").hide();
      $("#ph-resiger").show();
    });

    $('.test-code').bind('click', function(){
      var $this = $(this);
      $this.prop('disabled', true);
      $.ajax({
        url: '<?php echo SITE_SITE_URL; ?>/index.php?act=login&op=sendCode',
        data: {phone: $('[name=ph_phone]').val()},
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

  });
</script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/login/login-reg.js"></script>