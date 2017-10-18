<?php

/**
 * 首页
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class indexControl extends SystemControl {

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl');
        Tpl::output('admin_info', $this->admin_info);
        Tpl::output('permission', $this->permission);
    }
    
    /**
     * 欢迎页面
     * copyright 2015-06-02, jack
     */
    public function indexOp() {
        Tpl::output('admin_info', $this->getAdminInfo());
        Tpl::output('position', '欢迎页面');
        Tpl::showpage('welcome', 'index_layout');
    }

    /**
     * 退出
     * copyright 2015-06-02, jack
     */
    public function logoutOp() {
        setNcCookie('sys_key', '', -1, '', null);
        setNcCookie('u_name', '', -1, '', null);
        @session_start();
        unset($_SESSION['sys_key']);
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    /**
     * 修改密码
     * copyright 2015-06-02, jack
     */
    public function modifypwOp() {
        if (chksubmit()) {
            if (trim($_POST['new_pw']) !== trim($_POST['new_pw2'])) {
                showMessage('二次密码不一致');
            }
            $admininfo = $this->getAdminInfo();
            //查询管理员信息
            $admin_model = Model('admin');
            $admininfo = $admin_model->getOneAdmin($admininfo['id']);
            if (!is_array($admininfo) || count($admininfo) <= 0) {
                showMessage(Language::get('index_modifypw_admin_error'));
            }
            //旧密码是否正确
            if ($admininfo['admin_password'] != md5(trim($_POST['old_pw']))) {
                showMessage('理密码错误');
            }
            $new_pw = md5(trim($_POST['new_pw']));
            $result = $admin_model->updateAdmin(array('admin_password' => $new_pw, 'admin_id' => $admininfo['admin_id']));
            if ($result) {
                showMessage('修改成功');
            } else {
                showMessage('修改失败');
            }
        } else {
            Language::read('admin');
            Tpl::showpage('admin.modifypw', 'index_layout');
        }
    }

}
