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
                        <form method="post" id="form_login"  class="form clr">
                            <?php Security::getToken(); ?>
                            <input type="hidden" name="form_submit" value="ok" />
                            <input type="hidden" name="SiteUrl" id="SiteUrl" value="<?php echo SHOP_SITE_URL; ?>" />
                            <input type="hidden" name="nchash" id="nchash" value="<?php echo getNchash(); ?>" />
                            <div class="field ph-hide username-field">
                                <label for="TPL_username_1">
                                    <i class="ico ico-user" title="会员名"></i>
                                </label>
                                <input name="user_name" class="login-text" autocomplete="off" value="<?php echo $output['u_name']?>" placeholder="邮箱/会员名/8位ID" id="user_name" required />
                            <span class="nickx">
                                <i class="iconfont"></i>
                            </span>
                            </div>
                            <div class="input-tip">
                                <span id="form-account-error" class="error"></span>
                            </div>
                            <div class="field pwd-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-pass" title="会员名"></i>
                                </label>
                            <span class="J_StandardPwd">
								<input name="password" type="password" id="password" class="login-text" autocomplete="off" placeholder="输入密码" required pattern="[\S]{6}[\S]*" />
                            </span>
                            </div>
                            <div class="input-tip">
                                <span id="form-account-error" class=""></span>
                            </div>
                            <div class="field valid-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-valid" title="验证码"></i>
                                </label>
                            <span class="J_StandardPwd">
								<input name="captcha" type="text" class="login-text" id="captcha" placeholder="输入验证" pattern="[A-z0-9]{4}" title="输入验证" autocomplete="off" maxlength="4" required/>
                            </span>
                                <div class="code">
                                    <div class="arrow"></div>
                                    <div class="code-img"><a href="JavaScript:void(0);" onclick="javascript:document.getElementById('codeimage').src = 'index.php?act=code&op=makecode&admin=1&nchash=<?php echo getNchash(); ?>&t=' + Math.random();" class="change" title="刷新"><img src="index.php?act=code&op=makecode&admin=1&nchash=<?php echo getNchash(); ?>" name="codeimage" id="codeimage" border="0" width="81" height="36"/></a></div>
                                </div>
                            </div>
                            <div class="login-links">
                                <div href="" class="b-unlogn"><input id="un-login" name="ten" type="checkbox" value="1" class="un-login" /><label for="un-login" class="un-login-check">十天内免登录</label></div>
                                <!--<a href="" class="register">忘记密码？</a>-->
                            </div>
                            <div class="submit">
                                <button type="submit" class="J_Submit">登　录</button>
                            </div>
                        </form>
                    </div>
                    <div class="login-box" id="resiger" style="display:none;">
                        <form method="post" id="form_reg"  class="form clr" action="<?php echo urlShop('login','register')?>">
                            <input type="hidden" name="form_submit" value="ok" />
                            <div class="field ph-hide username-field">
                                <label for="TPL_username_1">
                                    <i class="ico ico-user" title="电子邮箱"></i>
                                </label>
                                <input name="u_name" type="email" class="login-text" autocomplete="off" placeholder="电子邮箱" id="u_name" required />
                            </div>
                            <div class="input-tip">
                                <span id="form-account-error" class="error"></span>
                            </div>
                            <div class="field pwd-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-pass" title="输入密码"></i>
                                </label>
                            <span class="J_StandardPwd">
                                <input type="password" name="password" id="password" class="login-text" placeholder="输入密码" required pattern="[\S]{6}[\S]*" />
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
                                <input type="password" name="re_pwd" id="re_pwd" class="login-text" placeholder="确认密码" required pattern="[\S]{6}[\S]*" />
                            </span>
                            </div>
                            <div class="input-tip">
                                <span id="form-account-error" class="error"></span>
                            </div>
                            <div class="field valid-field">
                                <label id="password-label" for="TPL_password_1">
                                    <i class="ico ico-valid" title="验证码"></i>
                                </label>
                            <span class="J_StandardPwd">
                                <input type="text" name="captcha" id="TPL_password_1" class="login-text" placeholder="输入验证" pattern="[A-z0-9]{4}" autocomplete="off" maxlength="4" required>
                            </span>
                                
                                <div class="code">
                                    <div class="arrow"></div>
                                    <div class="code-img"><a href="JavaScript:void(0);" onclick="javascript:document.getElementById('codeimage').src = 'index.php?act=code&op=makecode&admin=1&nchash=<?php echo getNchash(); ?>&t=' + Math.random();" class="change" title="刷新"><img src="index.php?act=code&op=makecode&admin=1&nchash=<?php echo getNchash(); ?>" name="re-codeimage" id="re-codeimage" border="0" width="81" height="36"/></a></div>
                                </div>
                            </div>
                            <div class="login-links">
                                <div href="" class="b-unlogn"><input id="un-login" name="" type="checkbox" value="" class="un-login" checked=""/><label for="un-login" class="un-login-check">我同意并已阅读<a href="" id="protocol">《用户服务协议》</a></label></div>
                            </div>
                            <div class="submit">
                                <button type="submit" class="J_Submit">立即注册</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>