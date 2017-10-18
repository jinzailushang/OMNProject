
<?php defined('InOmniWL') or exit('Access Invalid!'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>威廉系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo $output['html_title']; ?></title>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/jquery.1_9_1_min.js"></script>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/jquery.validate.min.js"></script>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/messages_zh.js"></script>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/login/login-reg.js"></script>
    <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/login.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/register.css" rel="stylesheet" type="text/css">
</head>
<script>
    $(function() {
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
            $(".find-back").hide();
            $(".password-reset").show();
        });
        //            重置成功
        $(".confirm-reset").click(function () {
            $(".password-reset").hide();
            $(".password-reset-succeed").show();
        });
        //            激活邮箱
        $(".pb-fine").click(function () {
            $(".find-back").hide();
            $(".pb-password-reset-succeed").show();
            return false;
        });
        //            邮箱密码重置
        $(".pb-sure").click(function () {
            $(".find-back,.pb-password-reset-succeed").hide();
            $(".password-reset").show();
            return false;
        });
        //立即登录,跳转到登录页面
    });
</script>
</head>
<body>
<header>
    <div class="top">
        <div class="w1000 por">
            <div class="logo poa">
                <a href="#">
                    <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/logo.png" />
                </a>
            </div>
            <div class="topimg poa">
                <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/top-nav.png" />
            </div>
        </div>
    </div>
</header>

<div class="login-banner">
    <div class="m-loginBody" >
        <div class="m-loginWrap clearfix find-back">
            <div class="loginForm clearfix">
                <div class="login-title clearfix">
                    <h2 class="clearfix retrieve-email">邮箱找回</h2>
                    <h4 class="find-mobile on">手机找回</h4>
                </div>
                <div class="loginForm-box">
                    <!--                    邮箱找回-->
                    <div class="login-box" id="pb-fine">
                        <form method="post" id="form_login"  class="form clr">
                            <input type='hidden' name='formhash' value='szzHyz4d0ZN1Yhq4XdEso4HfD0WeoQY' />                    <input type="hidden" name="form_submit" value="ok" />
                            <input type="hidden" name="SiteUrl" id="SiteUrl" value="SHOP_SITE_URL" />
                            <input type="hidden" name="nchash" id="nchash" value="" />
                            <div class="field ph-hide username-field pb-fine-input">
                                <label for="TPL_username_1">
                                    <i class="ico ico-user" title=""></i>
                                </label>
                                <input name="ph_name" class="fine-text" autocomplete="off" value="" placeholder="请输入电子邮箱" id="ph_name" required />
                            <span class="nickx">
                                <i class="iconfont"></i>
                            </span>
                            </div>
                            <div class="submit">
                                <button type="submit" class="J_Submit pb-fine">找　回</button>
                            </div>
                        </form>
                        <div class="shell">
                            <a href="http://sandbox-welink.dxomni.com/site/index.php?act=login&op=login">想起来了，直接登录>></a>
                        </div>
                    </div>

                    <!--                    手机找回-->
                    <div class="login-box" id="ph-fine" style="display:none;">
                        <form method="post" id="form_login"  class="form clr">
                            <input type='hidden' name='formhash' value='szzHyz4d0ZN1Yhq4XdEso4HfD0WeoQY' />                    <input type="hidden" name="form_submit" value="ok" />
                            <input type="hidden" name="SiteUrl" id="SiteUrl" value="SHOP_SITE_URL" />
                            <input type="hidden" name="nchash" id="nchash" value="" />
                            <div class="field ph-hide username-field">
                                <label for="TPL_username_1">
                                    <i class="ico ico-user" title=""></i>
                                </label>
                                <input name="ph_name" class="fine-text" autocomplete="off" value="" placeholder="请输入手机号码" id="ph_name" required />
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
                            <span class="J_StandardPwd">
								<input name="captcha" type="text" class="fine-text input-validation" id="captcha" placeholder="请输入验证码" pattern="[A-z0-9]{4}" title="输入验证码" autocomplete="off" maxlength="4" style="width:204px;">
                            </span>
                                <span class="get-code">
                                    <button name="" type="" class="test-code" id="">获取验证码</button>
                                </span>
                            </div>

                            <div class="submit Jph-fine">
                                <button type="submit" class="J_Submit ph-fine">找　回</button>
                            </div>
                        </form>
                        <div class="shell">
                            <a href="javascript::">想起来了，直接登录>></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        密码重置-->
        <div class="m-loginWrap clearfix password-reset" style="display:none;">
            <div class="loginForm clearfix">
                <div class="login-title clearfix">
                    <p class="clearfix password-reset-p">密码重置</p>
                    <!--                    <h4 class="find-mobile on">手机找回</h4>-->
                </div>
                <div class="loginForm-box">
                    <div class="login-box" id="ph-fine" >
                        <form method="post" id="form_login"  class="form clr">
                            <input type='hidden' name='formhash' value='szzHyz4d0ZN1Yhq4XdEso4HfD0WeoQY' />                    <input type="hidden" name="form_submit" value="ok" />
                            <input type="hidden" name="SiteUrl" id="SiteUrl" value="SHOP_SITE_URL" />
                            <input type="hidden" name="nchash" id="nchash" value="" />
                            <div class="field pwd-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-pass" title=""></i>
                                </label>
                            <span class="J_StandardPwd">
                                <input type="password" name="password" id="cz-password" class="fine-text" placeholder="请输入6-20个字符的输入密码" required="" pattern="[\S]{6}[\S]*">
                            </span>
                            </div>
                            <div class="input-tip">
                                <span id="form-account-error" class="error"></span>
                            </div>
                            <div class="field pwd-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-pass" title="确认密码"></i>
                                </label>
                            <span class="J_StandardPwd">
                                <input type="password" name="re_pwd" id="re_pwd" class="fine-text" placeholder="请确认密码" required="" pattern="[\S]{6}[\S]*">
                            </span>
                            </div>
                            <div class="submit Jph-fine">
                                <button type="submit" class="J_Submit confirm-reset">确认重置</button>
                            </div>
                        </form>
                        <div class="shell">
                            <a href="#">想起来了，直接登录>></a>
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
                <div class="login-box" id="pb-success" >
                    <form action="" method="post" id="form_reg" class="form clr">
                        <div class="pb-reg-suc">
                            <div class="pb-reg-success">
                                <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/dg.png" />
                                <p>重置成功</p>
                            </div>
                        </div>
                        <div class="submit">
                            <button type="submit" class="J_Submit pb-login">立即登录</button>
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
                <div class="login-box" id="pb-JSP" >
                    <form action="" method="post" id="form_reg" class="form clr">
                        <div class="pb-reg-suc">
                            <div class="pb-reg-success">
                                <p class="pb-reg-link">密码重置链接已经发送到您的邮箱：</p>
                                <p class="pb-reg-address">339370563@qq.com</p>
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
    </div>		<footer>
        <div class="foot-box">
            <div class="w1000 por">
                <div class="foot-logo poa"><a href="index.html"><img src="http://sandbox-welink.dxomni.com/site/templates/default/images/login/foot_logo.png"></a></div>
                <div class="foot-text poa">
                    <p><span>友情链接</span></p>
                    <div class="foot-nav clear">
                        <a target="_blank" href="http://xianlife.com">鲜LIFE</a>
                        <span class="sep">|</span>
                        <a target="_blank" href="http://elly.xianlife.com">爱莉颜芝</a>
                        <span class="sep">|</span>
                        <a target="_blank" href="http://www.my-worldstore.com">世界商店</a>
                    </div>
                    <p>Copyright © 2016 welinkexpress.com Inc</p>
                </div>
                <div class="foot-code poa"><img src="http://sandbox-welink.dxomni.com/site/templates/default/images/login/foot_code.png"></div>
            </div>
        </div>
    </footer>
</body>
</html>




