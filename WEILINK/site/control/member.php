<?php

/**
 * 会员管理
 * @copyright (c) 2016-05-27 16:12:37, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class memberControl extends SystemControl {
    
    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/user');
    }
    public function indexOp() {
        Tpl::output('position', '会员管理');
        Tpl::showpage('list', 'index_layout');
    }
    /**
     * 获取入库单列表填充数据
     * @return JSON
     */
    public function get_dataOp() {
        $model = Model('user');
        $condition = $this->get_search_condition();

        $count = $model->getUserCount($condition);
        $list = $model->getUserList($condition, '*', 20);
//        $sql = "SELECT %s FROM wl_user u, wl_user_other uo WHERE u.u_id = uo.u_id";
//        if (!empty($_GET['name'])) {
//            $sql .= " AND (first_name LIKE '%%{$_GET['name']}%%' OR last_name LIKE '%%{$_GET['name']}%%' OR CONCAT
//            (first_name,last_name) = '{$_GET['name']}')";
//        }
//        $count = $model->query(sprintf($sql, 'count(1) as n'));
//        $count = $count[0]['n'];
        $temp = array();
        if($count){
            //$list = $model->query(sprintf($sql, 'u.*,uo.*'). ' ORDER BY u.u_id DESC LIMIT '.(($_GET['curpage']-1)*20).',20');
            foreach($list as $k=>$v){
                $address = EMPTY_STR;
                if($v['area']){
                    $res = $this->getFullAreaName($v['area']);
                    krsort($res); //反向排序
                    $address = implode('', $res) . $v['address'];
                }
                $list[$k]['first_name'] = $v['first_name'] ? $v['first_name'] : EMPTY_STR;
                $list[$k]['last_name'] = $v['last_name'] ? $v['last_name'] : EMPTY_STR;
                $list[$k]['phone'] = $v['phone'] ? $v['phone'] : EMPTY_STR;
                $list[$k]['login_time'] = format_time($v['login_time']);
                $list[$k]['address'] = $address;
            }
            die(json_encode(array('status'=>1,'data'=>$list,'page'=>$model->showpage(9,'clickpage'),'count'=>$count)));
        }
        die(json_encode(array('status'=>0,'msg'=>'暂无数据','count'=>$count)));
    }

    /**
     * 获取搜索条件
     * @return string
     */
    public function get_search_condition() {
        $condition = array();
        $condition['status'] = (int)$_GET['status'];
        if (trim($_GET['name'])) {
            $condition['u_name'] = $_GET['name'];
        }
        if (trim($_GET['xm'])) {
            $condition['1'] = array('exp',"=1 and ( first_name = '".$_GET['xm']."'  or last_name = '".$_GET['xm']."' or CONCAT(first_name,last_name) = '".$_GET['xm']."')");
        }
        if (trim($_GET['province']) && $_GET['province'] != L('nc_please_choose')) {
            $condition['province'] = $_GET['province'];
        }
        if (trim($_GET['city']) && $_GET['city'] != L('nc_please_choose')) {
            $condition['city'] = $_GET['city'];
        }

        return $condition;
    }
    /**
     * 管理员重置会员密码
     */
    public function reset_pwdOp(){
        $u_id = $this->getGetData('u_id',0);
        $array['u_password'] = md5(SECRET_KEY.COMMON_PWD);
        if(Model('user')->updateUser($array,array('u_id'=>$u_id))){
            die(json_encode(array('status'=>1,'msg'=>'操作成功！')));
        }else{
            die(json_encode(array('status'=>0,'msg'=>'操作失败！')));
        }
    }
    /**
     * 会员的启用或停用
     */
    public function change_statusOp() {
        $u_id = $this->getGetData('u_id',0);
        $status = $this->getGetData('status',1);
        $array['status'] = $status;
        if(Model('user')->updateUser($array,array('u_id'=>$u_id))){
            die(json_encode(array('status'=>1,'msg'=>'操作成功！')));
        }else{
            die(json_encode(array('status'=>0,'msg'=>'操作失败！')));
        }
    }
    /**
     * 
     */
    public function settingOp() {
        if(chksubmit()){
            $post_data = $this->getPostArray();
            $res = Model('user_other')->updateUserOther(array('pay_method'=>$post_data['pay_method'],'house_id'=>$post_data['house_id']),array('u_id'=>$post_data['u_id']));
            if($res){
                $this->output(1,'操作成功！');
            }
            $this->output(0,'操作失败！');
        }
        $u_id = $this->getGetData('u_id',0);
        $info = Model('user_other')->getUserOtherInfo(array('u_id'=>$u_id),'pay_method,house_id');
        $house_list = Model('trans_house')->getTransHouseList(array(),'tid,tc_name');
        Tpl::output('u_id', $u_id);
        Tpl::output('info', $info);
        Tpl::output('house_list', $house_list);
        Tpl::showpage('setting', 'null_layout');
    }
}

