<?php defined('InOmniWL') or exit('Access Invalid!'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo $output['html_title']; ?></title>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/jquery.1_9_1_min.js"></script>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/jquery.validate.min.js"></script>
    <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/messages_zh.js"></script>

    <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/login.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/register.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
    <div class="top">
        <div class="w1000 por">
            <div class="logo poa">
                <a href="#">
                    <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/logo.png"/>
                </a>
            </div>
            <div class="topimg poa">
                <img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/top-nav.png"/>
            </div>
        </div>
    </div>
</header>
<?php defined('InOmniWL') or exit('Access Invalid!'); ?>


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
                            <input type="hidden" name="formhash" value="TMJsErdtyWZKJAUex7V3j5CaRtICKc7"> <input
                                type="hidden" name="form_submit" value="ok">
                            <input type="hidden" name="SiteUrl" id="SiteUrl" value="SHOP_SITE_URL">
                            <input type="hidden" name="nchash" id="nchash" value="">
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
                                    <input name="password" type="password" id="password" class="login-text"
                                           autocomplete="off" required="" placeholder="输入密码" pattern="[\S]{6}[\S]*"
                                           minlength="6" maxlength="20">
                                </div>
                                <div class="input-tip">
                                    <span id="password-error" class="error"></span>
                                </div>
                            </div>
                            <div>
                            <div class="field valid-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-valid" title="验证码"></i>
                                </label>
                                <!--                            <span class="J_StandardPwd">-->
                                <input name="captcha" type="text" class="login-text" id="captcha" placeholder="输入验证"
                                       pattern="[A-z0-9]{4}" title="输入验证" autocomplete="off" maxlength="4">
                                <!--                            </span>-->
                                <div class="code">
                                    <div class="arrow"></div>
                                    <div class="code-img"><a href="JavaScript:void(0);"
                                                             onclick="javascript:document.getElementById('codeimage').src = 'index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash=&amp;t=' + Math.random();"
                                                             class="change" title="刷新"><img
                                                src="index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash="
                                                name="codeimage" id="codeimage" border="0" width="81" height="36"></a>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="login-links">
                                <div href="" class="b-unlogn">
                                    <div class="b-unlogn-rp">
                                        <span class="rem-pd">
                                            <label>
                                                <input id="" name="remember-password" type="checkbox" value="" class="">
                                                记住密码
                                            </label>
                                        </span>
                                        <span class="for-pd">
                                            <a href="index.php?act=login&op=forgotpassword_html"
                                               class="forget-password">忘记密码</a>
                                        </span>
                                    </div>
                                    <!--                                    <label for="un-login" class="un-login-check">十天内免登录</label></div>-->

                                </div>
                                <div class="submit">
                                    <button type="submit" class="J_Submit">登　录</button>
                                </div>

                            </div>
                        </form>

                    </div>
                    <!--                    手机注册-->
                    <div class="login-box" id="ph-resiger" style="display:none;">
                        <form method="post" id="ph_form_reg" class="form clr"
                              action="<?php echo urlShop('login', 'register') ?>">
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
                                    <input name="ph_name" type="" class="login-text" autocomplete="off"
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
                                    <input name="ph_captcha" type="text" class=" input-validation" id="ph_captcha" placeholder="请输入验证码"/>

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
                              action="http://www.local.welink.com/site/index.php?act=login&amp;op=register">
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
                                    <input type="text" name="TPL_password_1" id="TPL_password_1" class="login-text"
                                           placeholder="输入验证" pattern="[A-z0-9]{4}" autocomplete="off" maxlength="4"
                                           required="">
                                    <!--                            </span>-->
                                    <div class="code">
                                        <div class="arrow"></div>
                                        <div class="code-img"><a href="JavaScript:void(0);"
                                                                 onclick="javascript:document.getElementById('codeimage').src = 'index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash=&amp;t=' + Math.random();"
                                                                 class="change" title="刷新"><img
                                                    src="index.php?act=code&amp;op=makecode&amp;admin=1&amp;nchash="
                                                    name="codeimage" id="codeimage" border="0" width="81" height="36"></a>
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
                            <button type="submit" class="J_Submit pb-login">立即登录</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="foot-box">
        <div class="w1000 por">
            <div class="foot-logo poa"><a href="/site/index.php?act=login&op=login_html"><img
                        src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/foot_logo.png"></a></div>
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
            <div class="foot-code poa"><img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/foot_code.png"></div>
        </div>
    </div>
</footer>
</body>
<script>
    $(function () {
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

    })
</script>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/login/login-reg.js"></script>
</html>
