<?php

/**
 * 用户管理
 * @copyright (c) 2016-05-26 13:53:29, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class userControl extends SystemControl {
    
    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/user');
    }
    
    public function indexOp() {
        if(chksubmit()){
            $model = Model('user_other');
            $post_data = $this->getPostArray();
            
            $u_id = $post_data['u_id'];
            unset($post_data['form_submit']);
            $is_exit = $model->getUserOtherInfo(array('u_id'=>$u_id));
            if($is_exit){
                unset($post_data['u_id']);
                $result = $model->updateUserOther($post_data,array('u_id'=>$u_id));
            }else{
                $result = $model->addUserOther($post_data);
            }
            if($result){
                redirect_url('编辑成功！');
            }
            redirect_url('编辑失败！', '' , 'error');
        }
        //省份列表
        $pro_list = Model('area')->getAreaList(array('area_parent_id'=>0));
        //会员信息
        $uid = $this->admin_info['id'];
        $info = Model('user')->getUserInfo(array('user.u_id'=>$uid));
        
        if($info['province']){
            $city_list = Model('area')->getAreaList(array('area_parent_id'=>$info['province']));
            Tpl::output('city_list', $city_list);
        }
        if($info['city']){
            $area_list = Model('area')->getAreaList(array('area_parent_id'=>$info['city']));
            Tpl::output('area_list', $area_list);
        }
        $info['u_id'] = $uid;
        $money = Model('money')->getMoneyInfo(array('u_id'=>$uid));
        $consignor = Model('consignor')->getConsignorInfo(array('u_id'=>$uid));
        Tpl::output('info', $info);
        Tpl::output('pro_list', $pro_list);
        Tpl::output('money', $money);
        Tpl::output('consignor', $consignor);
        Tpl::output('position', '用户管理');
        Tpl::showpage('index', 'index_layout');
    }
    /**
     * 会员自己修改密码
     */
    public function modify_pwdOp() {
        $uid = $this->admin_info['id'];
        if (chksubmit()) {
            if (trim($_POST['new_pw']) !== trim($_POST['new_pw2'])) {			
                redirect_url('两次输入的密码不一致', '' , 'error');
            }
            $model = Model('user');
            $user_info = $model->getUserInfo(array('user.u_id'=>$uid));
            if (!is_array($user_info) || count($user_info) <= 0) {
                redirect_url('记录不存在！', '' , 'error');
            }
            //旧密码是否正确
            if ($user_info['u_password'] != md5(SECRET_KEY.trim($_POST['old_pw']))) {
                redirect_url('旧密码不正确！', '' , 'error');
            }
            $new_pw = md5(SECRET_KEY.trim($_POST['new_pw']));
            $result = $model->updateUser(array('u_password' => $new_pw), array('u_id' => $uid));
            if ($result) {
                redirect_url('修改成功！', urlShop('member','index'));
            } else {
                redirect_url('修改失败！', urlShop('member','index'), 'error');
            }
        }
        Tpl::output('position', '修改密码');
        Tpl::showpage('modify_pwd', 'index_layout');
    }
    public function transacteOp() {
      $uid = $this->admin_info['id'];
      if ($_GET['user_name'] && $uid == '1') {
        $user = model('user')->getUserInfo(array('u_name'=>$_GET['user_name']));
        if (!$user) {
          $uid = 0;
        } else {
          $uid = $user['u_id'];
        }
      }
        $user_info = Model('user')->getUserInfo(array('user.u_id'=>$uid),'balance,frozen');
        $money_info = Model('money')->getMoneyInfo(array('u_id'=>$uid),'balance');
        Tpl::output('user_info', $user_info);
        Tpl::output('money_info', $money_info);
        Tpl::output('position', '交易明细');
        Tpl::output('is_super', $this->admin_info['id']==1);
        Tpl::showpage('transacte', 'index_layout');
    }
    /**
     *  获取交易明细
     */
    public function getTransacteDetailOp() {
        $uid = $this->admin_info['id'];
        $model = Model('money_log');
      if ($_GET['user_name'] && $uid == '1') {
        $user = model('user')->getUserInfo(array('u_name'=>$_GET['user_name']));
        if (!$user) {
          $uid = 0;
        } else {
          $uid = $user['u_id'];
        }
      }
        $condition = array('u_id'=>$uid);
        
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['end_date']) : null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time', array($start_unixtime, $end_unixtime));
        }
        if (in_array($_GET['inout'], array('in','out'))) {
            $condition['type'] = $_GET['inout'];
        }
        
        $count = $model->getMoneyLogCount($condition);
        $list = $model->getMoneyLogList($condition);
        if($list){
            die(json_encode(array('status'=>1,'data'=>$list,'page'=>$model->showpage(9,'clickpage'),'count'=>$count)));
        }
        die(json_encode(array('status'=>0,'msg'=>'暂无数据','count'=>$count)));
    }

  public function billOp() {
    $uid = $this->admin_info['id'];
    if ($_GET['user_name'] && $uid == '1') {
      $user = model('user')->getUserInfo(array('u_name'=>$_GET['user_name']));
      if (!$user) {
        $uid = 0;
      } else {
        $uid = $user['u_id'];
      }
    }
    $user_info = Model('user')->getUserInfo(array('user.u_id'=>$uid),'balance,frozen');
    $money_info = Model('money')->getMoneyInfo(array('u_id'=>$uid),'balance');
    $trans_houses = Model('trans_house')->getTransHouseList();
    Tpl::output('user_info', $user_info);
    Tpl::output('money_info', $money_info);
    Tpl::output('trans_houses', $trans_houses);
    Tpl::output('position', '账单查询');
    Tpl::output('is_super', $this->admin_info['id']==1);
    Tpl::showpage('bill', 'index_layout');
  }

  /**
   *  获取交易明细
   */
  public function getBillOp() {
    $uid = $this->admin_info['id'];
    if ($_GET['user_name'] && $uid == '1') {
      $user = model('user')->getUserInfo(array('u_name'=>$_GET['user_name']));
      if (!$user) {
        $uid = 0;
      } else {
        $uid = $user['u_id'];
      }
    }
    $model = Model('order');
    /*$condition = array('u_id'=>$uid);
    $condition['order_state'] = array('gt', '35');*/
    $sql = "SELECT %s FROM wl_order o INNER JOIN wl_order_address a ON a.order_id = o.order_id
    INNER JOIN wl_pay_log l ON o.order_sn = l.order_sn
     WHERE o.order_state > '35' AND l.status = 'done' AND o.u_id = '$uid'";

    if ($_GET['order_sn']) {
      $sql .= " AND o.order_sn = '{$_GET['order_sn']}'";
    }
    if ($_GET['shipping_code']) {
      $sql .= " AND shipping_code = '{$_GET['shipping_code']}'";
    }
    if ($_GET['track_no']) {
      $sql .= " AND (pre_track_no = '{$_GET['track_no']}' OR track_no = '{$_GET['track_no']}')";
    }
    if ($_GET['reciver_name']) {
      $sql .= " AND a.reciver_name = '{$_GET['reciver_name']}'";
    }
    if ($_GET['tc_code']) {
      $sql .= " AND tc_code = '{$_GET['tc_code']}'";
    }

    $sql .=  " GROUP BY o.order_id";

    // 1. 获取订单
    // 2. 获取订单交易信息

    //$count = $model->getOrderCount($condition);
    //$list = $model->getOrderList($condition);
    $count = Model()->query(sprintf($sql, 'COUNT(1) AS n'));
    $count = count($count);
    if($count){
      $list = Model()->query(sprintf($sql, 'o.*, a.*, l.flow_id, l.pay_time, l.notify_time').' ORDER BY IF(l
      .notify_time,l
      .notify_time,l.pay_time) DESC LIMIT '.(($_GET['curpage']-1)*20).',20');
      foreach ($list as $k=>$v) {
        //$log = Model('pay_log')->getPayLogInfo(array('order_sn'=>$v['order_sn'],'status'=>'done'),'flow_id,pay_time,notify_time','notify_time desc');
        //$list[$k]['flow_id'] = $log['flow_id'];
        $list[$k]['notify_time'] = $v['notify_time']?$v['notify_time']:$v['pay_time'];
        $trans_house = Model('trans_house')->getTransHouseInfo(array('tc_code'=>$v['tc_code']), 'tc_name');
        $list[$k]['tc_name'] = $trans_house['tc_name'];
      }
      die(json_encode(array('status'=>1,'data'=>$list,'page'=>$model->showpage(9,'clickpage'),'count'=>$count)));
    }
    die(json_encode(array('status'=>0,'msg'=>'暂无数据','count'=>$count)));
  }

  public function saveConsignorOp() {
    if (empty($_POST['name'])) {
      $this->output(0, '姓名不能为空');
    }
    if (empty($_POST['phone'])) {
      $this->output(0, '电话不能为空');
    }
    if (empty($_POST['province'])) {
      $this->output(0, '省份不能为空');
    }
    if (empty($_POST['city'])) {
      $this->output(0, '城市不能为空');
    }
    if (empty($_POST['area'])) {
      $this->output(0, '地区不能为空');
    }
    if (empty($_POST['address'])) {
      $this->output(0, '地址不能为空');
    }
    if (empty($_POST['zipcode'])) {
      $this->output(0, '邮编不能为空');
    }
    $data = array(
      'name' => $_POST['name'],
      'phone' => $_POST['phone'],
      'province' => $_POST['province'],
      'city' => $_POST['city'],
      'area' => $_POST['area'],
      'address' => $_POST['address'],
      'zipcode' => $_POST['zipcode']
    );
    if (Model('consignor')->getConsignorInfo(array('u_id'=>$this->admin_info['id']))) {
      Model('consignor')->updateConsignor($data, array('u_id'=>$this->admin_info['id']));
    } else {
      $data['u_id'] = $this->admin_info['id'];
      $data['add_time'] = time();
      Model('consignor')->addConsignor($data);
    }

    $this->output(1, '更新成功');
  }
    
    public function testOp(){
        //AWE06CEF40E79
        $res = Model('package_service')->queryOrderStatus('318900837429');
        print_r($res);
        die;

    }
}

