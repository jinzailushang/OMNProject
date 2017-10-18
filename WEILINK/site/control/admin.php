<?php

/**
 * 权限管理
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class adminControl extends SystemControl {

    const skey = 'V1.0';

    private $links = array(
        array('url' => 'act=admin&op=admin', 'lang' => 'limit_admin'),
        array('url' => 'act=admin&op=admin_add', 'lang' => 'admin_add_limit_admin'),
        array('url' => 'act=admin&op=gadmin', 'lang' => 'limit_gadmin'),
        array('url' => 'act=admin&op=gadmin_add', 'lang' => 'admin_add_limit_gadmin'),
    );

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/admin');
        Language::read('index');
        Language::read('admin');
        Tpl::output('position', '权限设置');
    }

    /**
     * 管理员列表
     */
    public function adminOp() {
        $this->checkPcl();
        Tpl::showpage('admin.index', 'index_layout');
    }

    /**
     * 返回管理员列表
     */
    public function get_dataOp() {
        $condition = array();
        if ($_GET['admin_name']) {
            $condition['admin_name'] = array('like', '%' . $_GET['admin_name'] . '%');
        }
        if ($this->admin_info['gid'] != 1) {
            $condition['admin.admin_name'] = array('in', $this->getSubAdmin());
        }

        $model = Model();
        $count = $model->table('admin')->where($condition)->count();

        $admin_list = $model->table('admin,admin_group')->join('left join')->on('admin_group.gid=admin.admin_gid')->where($condition)->page(20)->order('admin_gid asc')->select();
        if ($admin_list) {
            foreach ($admin_list as $k => $v) {
                $admin_list[$k]['login_time'] = $v['admin_login_time'] ? date('Y-m-d H:i:s', $v['admin_login_time']) : '此管理员未登录过';
            }
            die(json_encode(array('status' => 1, 'data' => $admin_list, 'page' => $model->showpage(9, 'clickpage'), 'count' => $count)));
        }
        die(json_encode(array('status' => 0, 'msg' => '暂无数据', 'count' => $count)));
    }

    /**
     * 管理员删除
     */
    public function admin_delOp() {
        $this->checkPcl();
        if (!empty($_GET['admin_id'])) {
            if ($_GET['admin_id'] == 1) {
                redirect_url(L('nc_common_save_fail'));
            }
            Model()->table('admin')->where(array('admin_id' => intval($_GET['admin_id'])))->delete();
            $this->log(L('nc_delete,limit_admin') . '[ID:' . intval($_GET['admin_id']) . ']', 1);
            redirect_url(L('nc_common_del_succ'));
        } else {
            redirect_url(L('nc_common_del_fail'));
        }
    }

    /**
     * 管理员添加
     */
    public function admin_addOp() {
        $this->checkPcl();
        if (chksubmit()) {
            $limit_str = '';
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_password'] = md5($_POST['admin_password']);
            $store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
            $param['bind_store'] = $store_name ? implode(',', $store_name) : '';
            $gid = isset($_POST['gid']) ? $_POST['gid'] : '';
            $gid1 = isset($_POST['gid1']) ? $_POST['gid1'] : '';
            $gid2 = isset($_POST['gid2']) ? $_POST['gid2'] : '';
            if ($gid2) {
                $param['admin_gid'] = $gid2;
            } elseif ($gid1) {
                $param['admin_gid'] = $gid1;
            } else {
                $param['admin_gid'] = $gid;
            }
            $rs = $model_admin->addAdmin($param);
            if ($rs) {
                $ginfo = Model()->table('admin_group')->field('parent_id')->where(array('gid' => $param['admin_gid']))->find();
                if ($ginfo['parent_id'] > 1) {
                    //如果直接上级不是超级管理员，则要把绑定的商品同时绑定到直接上级
                    $list = Model()->table('admin')->field('admin_id,bind_store')->where(array('admin_gid' => $ginfo['parent_id']))->select();
                    if ($list) {
                        foreach ($list as $k => $v) {
                            $st_arr = array();
                            if ($store_name) {
                                foreach ($store_name as $t) {
                                    if (empty($v['bind_store']) || !in_array($t, explode(",", $v['bind_store']))) {
                                        $st_arr[] = $t;
                                    }
                                }
                            }
                            if (empty($v['bind_store']) && $st_arr) {
                                $uparr = array(
                                    'bind_store' => implode(",", $st_arr),
                                    'admin_id' => $v['admin_id']
                                );
                                $model_admin->updateAdmin($uparr);
                            } elseif ($v['bind_store'] && $st_arr) {
                                $bt = explode(",", $v['bind_store']);
                                $uparr = array(
                                    'bind_store' => implode(",", array_merge($bt, $st_arr)),
                                    'admin_id' => $v['admin_id']
                                );
                                $model_admin->updateAdmin($uparr);
                            }
                        }
                    }
                }

                $this->log(L('nc_add,limit_admin') . '[' . $_POST['admin_name'] . ']', 1);
                redirect_url(L('nc_common_save_succ'), 'index.php?act=admin&op=admin');
            } else {
                redirect_url(L('nc_common_save_fail'));
            }
        }

        //得到权限组
        $gid = $this->admin_info['gid'];
        $condition = array();
        if ($gid != 1) {
            $condition['parent_id'] = $gid;
        } else {
            $condition['parent_id'] = 0;
        }
        $gadmin = Model('admin_group')->field('gname,gid')->where($condition)->select();
        $where = array('store_status' => 1);
        Tpl::output('gadmin', $gadmin);
        Tpl::output('admin_info', $this->admin_info);
        Tpl::output('top_link', $this->sublink($this->links, 'admin_add'));
        Tpl::output('limit', $this->permission());
        Tpl::showpage('admin.add', 'index_layout');
    }

    /**
     * 获取指定权限组列表信息
     */
    public function get_gadminOp() {
        $gid = $_GET['gid'];
        if ($gid) {
            $list = Model()->table('admin_group')->field('gid,gname')->where(array('parent_id' => $gid))->select();
            die(json_encode(array('status' => 1, 'data' => $list)));
        }
        die(json_encode(array('status' => 0)));
    }

    /**
     * 设置权限组权限
     */
    public function gadmin_setOp() {
        $this->checkPcl();
        $model = Model('admin_group');
        $gid = intval($_GET['gid']);
        $ginfo = $model->getby_gid($gid);
        if (empty($ginfo)) {
            showMessage(L('admin_set_admin_not_exists'));
        }
        if (chksubmit()) {
            $post_gid = intval($_POST['gid']) ? intval($_POST['gid']) : 1;
            $limit_str = '';
            $perm = $_POST['permission'];
            if (is_array($perm)) {
                $limit_str = implode('|', $_POST['permission']);
            }
            $limit_str = encrypt($limit_str, MD5_KEY . md5($_POST['gname']));

            $data['limits'] = $limit_str;
            $data['gname'] = $_POST['gname'];
            $data['parent_id'] = $post_gid;
            $update = $model->where(array('gid' => $gid))->update($data);
            if ($update) {
                //如果直接上级不是超级管理员，则相应更新权限
                if ($post_gid > 1) {
                    $info = Model()->table('admin_group')->where(array('gid' => $post_gid))->find();
                    $hlist = decrypt($info['limits'], MD5_KEY . md5($info['gname']));
                    $hlist = explode('|', $hlist);
                    if (is_array($perm)) {
                        foreach ($perm as $p) {
                            if (!in_array($p, $hlist)) {
                                $hlist[] = $p;
                            }
                        }
                    }
                    $limits = encrypt(implode('|', $hlist), MD5_KEY . md5($info['gname']));
                    Model()->table('admin_group')->where(array('gid' => $post_gid))->update(array('limits' => $limits));
                }

                $this->log(L('nc_edit,limit_gadmin') . '[' . $_POST['gname'] . ']', 1);
                redirect_url(L('nc_common_save_succ'), 'index.php?act=admin&op=gadmin');
            } else {
                redirect_url(L('nc_common_op_fail'));
            }
        }

        $hlist = $gadmin_list = array();
        //解析已有权限
        $hlimit = decrypt($ginfo['limits'], MD5_KEY . md5($ginfo['gname']));

        if ($this->admin_info['gid'] == 1 && $ginfo['level'] > 2) {
            $gadmin_list = Model()->table('admin_group')->field('gid,gname')->where(array('parent_id' => 1))->select();
        } else {
            $hinfo = $model->getby_gid($this->admin_info['gid']);
            $hlist = decrypt($hinfo['limits'], MD5_KEY . md5($hinfo['gname']));
            $hlist = explode('|', $hlist);
        }

        $ginfo['limits'] = explode('|', $hlimit);

        Tpl::output('ginfo', $ginfo);
        Tpl::output('hlist', $hlist);
        Tpl::output('gadmin_list', $gadmin_list);
        Tpl::output('gid', $this->admin_info['gid']);
        Tpl::output('limit', $this->permission());
        Tpl::output('top_link', $this->sublink($this->links, 'gadmin'));
        Tpl::showpage('gadmin.set', 'index_layout');
    }

    /**
     * ajax操作
     */
    public function ajaxOp() {
        switch ($_GET['branch']) {
            //管理人员名称验证
            case 'check_admin_name':
                $model_admin = Model('admin');
                $condition['admin_name'] = $_GET['admin_name'];
                $list = $model_admin->infoAdmin($condition);
                if (!empty($list)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
            //权限组名称验证
            case 'check_gadmin_name':
                $condition = array();
                if (is_numeric($_GET['gid'])) {
                    $condition['gid'] = array('neq', intval($_GET['gid']));
                }
                $condition['gname'] = $_GET['gname'];
                $info = Model('admin_group')->where($condition)->find();
                if (!empty($info)) {
                    exit('false');
                } else {
                    exit('true');
                }
                break;
        }
    }

    /**
     * 设置管理员权限
     */
    public function admin_editOp() {
        $this->checkPcl();
        if (chksubmit()) {
            $admin_model = Model('admin');
            //没有更改密码
            if ($_POST['new_pw'] != '') {
                $data['admin_password'] = md5($_POST['new_pw']);
            }
            $data['admin_id'] = intval($_POST['admin_id']);
            $data['admin_is_super'] = $data['admin_gid'] == 1 ? 1 : 0;
            $store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
            if ($data['admin_gid'] > 1 && !$store_name) {
                redirect_url('非超管必须绑定商家！');
            }
            $data['bind_store'] = $store_name ? implode(',', $store_name) : '';
            $qx_flag = (int) $_POST['qx_flag'];
            //$qx_flag = 2 表示更改权限组
            if ($qx_flag == 2) {
                $gid = isset($_POST['gid']) ? $_POST['gid'] : '';
                $gid1 = isset($_POST['gid1']) ? $_POST['gid1'] : '';
                $gid2 = isset($_POST['gid2']) ? $_POST['gid2'] : '';
                if ($gid2) {
                    $data['admin_gid'] = $gid2;
                } elseif ($gid1) {
                    $data['admin_gid'] = $gid1;
                } else {
                    $data['admin_gid'] = $gid;
                }
                //找出所属权限组id
                $gid = $data['admin_gid'];
            } else {
                //找出所属权限组id
                $info = Model()->table('admin')->field('admin_gid')->where(array('admin_id' => $data['admin_id']))->find();
                $gid = $info['admin_gid'];
            }
            $ginfo = Model()->table('admin_group')->field('parent_id')->where(array('gid' => $gid))->find();

            if ($ginfo['parent_id'] > 1) {
                //如果直接上级不是超级管理员，则要把绑定的商品同时绑定到直接上级
                $list = Model()->table('admin')->field('admin_id,bind_store')->where(array('admin_gid' => $ginfo['parent_id']))->select();
                if ($list) {
                    foreach ($list as $k => $v) {
                        $st_arr = array();
                        if ($store_name) {
                            foreach ($store_name as $t) {
                                if (empty($v['bind_store']) || !in_array($t, explode(",", $v['bind_store']))) {
                                    $st_arr[] = $t;
                                }
                            }
                        }
                        if (empty($v['bind_store']) && $st_arr) {
                            $uparr = array(
                                'bind_store' => implode(",", $st_arr),
                                'admin_id' => $v['admin_id']
                            );
                            $model_admin->updateAdmin($uparr);
                        } elseif ($v['bind_store'] && $st_arr) {
                            $bt = explode(",", $v['bind_store']);
                            $uparr = array(
                                'bind_store' => implode(",", array_merge($bt, $st_arr)),
                                'admin_id' => $v['admin_id']
                            );
                            $admin_model->updateAdmin($uparr);
                        }
                    }
                }
            }
            //查询管理员信息
            $result = $admin_model->updateAdmin($data);
            if ($result) {
                $this->log(L('nc_edit,limit_admin') . '[ID:' . intval($_POST['admin_id']) . ']', 1);
                redirect_url(Language::get('admin_edit_success'), 'index.php?act=admin&op=admin');
            } else {
                redirect_url(Language::get('admin_edit_fail'), 'index.php?act=admin&op=admin');
            }
        } else {
            //查询用户信息
            $admin_model = Model('admin');
            $admininfo = $admin_model->getOneAdmin(intval($_GET['admin_id']));
            $admininfo['gname'] = getFieldName(array('table' => 'admin_group', 'field' => 'gid', 'value' => $admininfo['admin_gid'], 'fields' => 'gname'));
            if (!is_array($admininfo) || count($admininfo) <= 0) {
                redirect_url(Language::get('admin_edit_admin_error'), 'index.php?act=admin&op=admin');
            }
            $gid = $this->admin_info['gid'];
            $condition = array();
            if ($gid != 1) {
                $condition['parent_id'] = $gid;
            } else {
                $condition['parent_id'] = 0;
            }
            $gadmin = Model('admin_group')->field('gname,gid')->where($condition)->select();

            $where = array('store_status' => 1);

            Tpl::output('admininfo', $admininfo);
            Tpl::output('top_link', $this->sublink($this->links, 'admin'));

            //得到权限组
            Tpl::output('gadmin', $gadmin);
            Tpl::showpage('admin.edit', 'index_layout');
        }
    }

    /**
     * 取得所有权限项
     * @return array
     */
    private function permission() {
        Language::read('common');
        $lang = Language::getLangContent();
        $limit = require(BASE_PATH . '/include/limit.php');
        if (is_array($limit)) {
            foreach ($limit as $k => $v) {
                if (is_array($v['child'])) {
                    $tmp = array();
                    foreach ($v['child'] as $key => $value) {
                        $act = (!empty($value['act'])) ? $value['act'] : $v['act'];
                        if (strpos($act, '|') == false) {//act参数不带|
                            $limit[$k]['child'][$key]['op'] = rtrim($act . '.' . str_replace('|', '|' . $act . '.', $value['op']), '.');
                        } else {//act参数带|
                            $tmp_str = '';
                            if (empty($value['op'])) {
                                $limit[$k]['child'][$key]['op'] = $act;
                            } elseif (strpos($value['op'], '|') == false) {//op参数不带|
                                foreach (explode('|', $act) as $v1) {
                                    $tmp_str .= "$v1.{$value['op']}|";
                                }
                                $limit[$k]['child'][$key]['op'] = rtrim($tmp_str, '|');
                            } elseif (strpos($value['op'], '|') != false && strpos($act, '|') != false) {//op,act都带|，交差权限
                                foreach (explode('|', $act) as $v1) {
                                    foreach (explode('|', $value['op']) as $v2) {
                                        $tmp_str .= "$v1.$v2|";
                                    }
                                }
                                $limit[$k]['child'][$key]['op'] = rtrim($tmp_str, '|');
                            }
                        }
                    }
                }
            }
            return $limit;
        } else {
            return array();
        }
    }

    /**
     * 权限组
     */
    public function gadminOp() {
        $this->checkPcl();
        Tpl::showpage('gadmin.index', 'index_layout');
    }

    public function get_admin_dataOp() {
        $gid = $_GET['gid'];
        $model = Model('admin_group');
        $where = array();
        if (!$gid) {
            if ($this->admin_info['gid'] == 1) {
                $where['parent_id'] = 0;
            } else {
                $where['parent_id'] = $this->admin_info['gid'];
            }
        } else {
            $where['parent_id'] = $gid;
        }
        $count = $model->where($where)->count();
        $list = $model->where($where)->page(20)->select();
        if ($list) {
            foreach ($list as $k => $v) {
                $list[$k]['child'] = $model->where(array('parent_id' => $v['gid']))->count();
            }
            die(json_encode(array('status' => 1, 'data' => $list, 'page' => $model->showpage(9, 'clickpage'), 'count' => $count)));
        }
        die(json_encode(array('status' => 0, 'msg' => '暂无数据', 'count' => $count)));
    }

    /**
     * 权限组显示和隐藏
     */
    public function get_gadmin_subidOp() {
        $gid = (int) $_GET['gid'];
        $gid_arr = array();
        if ($gid) {
            $model = Model('admin_group');
            $list = $model->field('gid')->where(array('parent_id' => $gid))->select();
            if ($list) {
                foreach ($list as $k => $v) {
                    $gid_arr[] = $v['gid'];
                    $list1 = $model->field('gid')->where(array('parent_id' => $v['gid']))->select();
                    if ($list1) {
                        foreach ($list1 as $k1 => $v1) {
                            $gid_arr[] = $v1['gid'];
                        }
                    }
                }
            }
            die(json_encode(array('status' => 1, 'data' => $gid_arr)));
        }
        die(json_encode(array('status' => 0, 'data' => $gid_arr)));
    }

    /**
     * 添加权限组
     */
    public function gadmin_addOp() {

        $this->checkPcl();
        if (chksubmit()) {
            $limit_str = '';
            $model = Model('admin_group');
            $perm = $_POST['permission'];
            if (is_array($perm)) {
                $limit_str = implode('|', $_POST['permission']);
            }

            $data['limits'] = encrypt($limit_str, MD5_KEY . md5($_POST['gname']));
            $data['gname'] = $_POST['gname'];
            $gid = (int) $_POST['gid'];
            if ($this->admin_info['gid'] == 1) {
                if ($gid) {
                    //$gid>0,添加的是3级管理员
                    $data['parent_id'] = $gid;
                    $data['level'] = 3;
                } else {
                    //$gid>0,添加的是2级管理员，即主管级
                    $data['parent_id'] = 1;
                    $data['level'] = 2;
                }
            } else {
                $data['parent_id'] = $this->admin_info['gid'];
                $data['level'] = 3;
            }
            $model->beginTransaction();
            try {
                $res = $model->insert($data);
                if (!$res) {
                    throw new Exception('创建权限组失败！');
                }
                if ($this->admin_info['gid'] == 1 && $gid) {
                    //$gid>0,添加的是3级管理员，需要把权限同时分配到所属上级主管
                    $info = Model()->table('admin_group')->where(array('gid' => $gid))->find();
                    $hlist = decrypt($info['limits'], MD5_KEY . md5($info['gname']));
                    $hlist = explode('|', $hlist);
                    if (is_array($perm)) {
                        foreach ($perm as $p) {
                            if (!in_array($p, $hlist)) {
                                $hlist[] = $p;
                            }
                        }
                    }
                    $limits = encrypt(implode('|', $hlist), MD5_KEY . md5($info['gname']));
                    $res1 = Model()->table('admin_group')->where(array('gid' => $gid))->update(array('limits' => $limits));
                    if (!$res1) {
                        throw new Exception('权限分配失败！');
                    }
                }
                $this->log(L('nc_add,limit_gadmin') . '[' . $_POST['gname'] . ']', 1);
                $model->commit();
                redirect_url(L('nc_common_save_succ'), 'index.php?act=admin&op=gadmin');
            } catch (Exception $ex) {
                $model->rollback();
                redirect_url($ex->getMessage());
            }
        }

        $model = Model('admin_group');
        $limit = $this->permission();
        $hlist = $gadmin_list = array();
        if ($this->admin_info['gid'] == 1) {
            $gadmin_list = Model()->table('admin_group')->field('gid,gname')->where(array('parent_id' => 1))->select();
        } else {
            $hinfo = $model->getby_gid($this->admin_info['gid']);
            $hlist = decrypt($hinfo['limits'], MD5_KEY . md5($hinfo['gname']));
            $hlist = explode('|', $hlist);
        }

        Tpl::output('hlist', $hlist);
        Tpl::output('gid', $this->admin_info['gid']);
        Tpl::output('top_link', $this->sublink($this->links, 'gadmin_add'));
        Tpl::output('limit', $limit);
        Tpl::output('gadmin_list', $gadmin_list);
        Tpl::showpage('gadmin.add', 'index_layout');
    }

    /**
     * 组删除
     */
    public function gadmin_delOp() {
        $this->checkPcl();
        if (is_numeric($_GET['gid'])) {
            $n = Model()->table('admin')->where(array('admin_gid' => $_GET['gid']))->find();
            if ($n) {
                redirect_url('有管理员属于该权限组，不能删除！');
            }
            Model('admin_group')->where(array('gid' => intval($_GET['gid'])))->delete();
            $this->log(L('nc_delete,limit_gadmin') . '[ID' . intval($_GET['gid']) . ']', 1);
            redirect_url(L('nc_common_del_succ'));
        } else {
            redirect_url(L('nc_common_op_fail'));
        }
    }

    public function modify_pwdOp() {
        if (chksubmit()) {
            if (trim($_POST['new_pw']) !== trim($_POST['new_pw2'])) {
                //showMessage('两次输入的密码不一致，请重新输入');				
                redirect_url(Language::get('index_modifypw_repeat_error'));
            }
            $admininfo = $this->getAdminInfo();
            //查询管理员信息
            $admin_model = Model('admin');
            $admininfo = $admin_model->getOneAdmin($admininfo['id']);
            if (!is_array($admininfo) || count($admininfo) <= 0) {
                redirect_url(Language::get('index_modifypw_admin_error'));
            }
            //旧密码是否正确
            if ($admininfo['admin_password'] != md5(SECRET_KEY.trim($_POST['old_pw']))) {
                redirect_url(Language::get('index_modifypw_oldpw_error'));
            }
            $new_pw = md5(SECRET_KEY.trim($_POST['new_pw']));
            $result = $admin_model->updateAdmin(array('admin_password' => $new_pw, 'admin_id' => $admininfo['admin_id']));
            if ($result) {
                redirect_url(Language::get('index_modifypw_success'), urlAdmin('index'));
            } else {
                redirect_url(Language::get('index_modifypw_fail'));
            }
        }
        Tpl::output('position', '修改密码');
        Tpl::showpage('admin.modify_pwd', 'index_layout');
    }

}
