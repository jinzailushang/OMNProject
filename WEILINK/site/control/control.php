<?php

/**
 * 接口control父类
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class apiControl
{

  protected $version = '0.1';
  protected $page = 20;
  protected $signatureKey = '';

  public function __construct()
  {

    Language::read('common');

    $page = intval($_GET['page']);
    if ($page > 0) {
      $this->page = $page;
    }
  }

  public function output($flag = false, $msg = '', $data = array())
  {
    header('Content-type:application/x-javascript');
    echo json_encode(array(
        'status' => $flag,
        'msg' => $msg,
        'data' => $data)
    );
    die();
  }

  /**
   * 查找上级管理员名称
   * @return type
   */
  protected function getSubAdmin()
  {
    $userArr[] = $this->admin_info['name'];
    $list = Model()->table('admin,admin_group')->join('left join')->on('admin.admin_gid=admin_group.gid')->where(array('admin_group.parent_id' => $this->admin_info['gid']))->select();
    if ($list) {
      foreach ($list as $v) {
        $userArr[] = $v['admin_name'];
      }
    }
    return $userArr;
  }

  /**
   * 获取下级地区
   * @return type
   */
  public function get_areaOp()
  {
    $area_id = (int)$_GET['area_id'];
    if (!$area_id) {
      return;
    }
    $list = Model('area')->getAreaList(array('area_parent_id' => $area_id));
    if ($list) {
      die(json_encode(array('status' => 1, 'data' => $list)));
    }
    die(json_encode(array('status' => 0)));
  }

  /**
   * 根据area_id 获取上级地区名称（包含本级）
   * @param int $gid
   * @return array
   */
  public function getFullAreaName($areaid)
  {
    $info = Model()->table('area')->where(array('area_id' => $areaid))->field('area_name,area_parent_id')->find();
    $arr[] = $info['area_name'];
    if ($info['area_parent_id']) {
      $return = $this->getFullAreaName($info['area_parent_id']);
      if (count($return)) {
        foreach ($return as $a) {
          $arr[] = $a;
        }
      }
    }
    return $arr;
  }

  /**
   * 自动分配一个物流单号
   * @return string
   */
  public function autoGetShipmentCode()
  {
    $model_ship = Model('shipment_code');
    //查找一个没有使用过的物流单号赋予shipping_code
    /*$ship = $model_ship->getShipmentCodeInfo(array('flag'=>0));
    if($ship){
        $code = $ship['scode'];
    }else{*/
    //如果物流单号已用完，则生成一个
    $code = $model_ship->buildCode(1);
    //}
    return $code;
  }

  /**
   * @version 获取指定名称的表单数据(POST)
   * @param string $name 表单名称
   */
  protected function getPostData($name = '', $default_value = '')
  {
    $value = filter_input(INPUT_POST, $name);
    if (FALSE === $value || NULL == $value) {
      $value = $default_value;
    }
    return $name == '' ? '' : $value;
  }

  /**
   * @version 获取表单POST数组
   */
  protected function getPostArray()
  {
    $data = filter_input_array(INPUT_POST);
    if (!is_array($data)) {
      $data = array();
    }
    return $data;
  }

  /**
   * @version 获取指定名称的表单数据(GET)
   * @param string $name 表单名称
   */
  protected function getGetData($name = '', $default_value = '')
  {
    $value = filter_input(INPUT_GET, $name);
    if (FALSE == $value || NULL == $value) {
      $value = isset($_GET[$name]) ? $_GET[$name] : $default_value;
    }
    return $name == '' ? '' : $value;
  }

}

class SystemControl extends apiControl
{

  /**
   * 管理员资料
   */
  protected $admin_info;

  /**
   * 权限内容
   */
  protected $permission;

  public function __construct()
  {
    parent::__construct();
    import('libraries.process');
    Language::read('common,layout');
    $this->admin_info = $this->systemLogin();
    /*if (!in_array($_GET['op'], array('buildpdf', 'sync_wms'))) {
        $this->admin_info = $this->systemLogin();
    }
    if ($this->admin_info['id'] != 1) {
        $this->checkPermission();
    }*/
    if (($_GET['branch'] != '' || $_GET['op'] == 'ajax') && strtoupper(CHARSET) == 'GBK') {
      $_GET = Language::getGBK($_GET);
    }
    $this->set_perm_to();
  }

  /**
   * 取得当前管理员信息
   * @param
   * @return 数组类型的返回结果
   */
  protected final function getAdminInfo()
  {
    return $this->admin_info;
  }

  /**
   * 系统后台登录验证
   * @param
   * @return array 数组类型的返回结果
   */
  protected final function systemLogin()
  {
    $signature = cookie('sys_key');
    if (!$signature) {
      $signature = $this->signature();
    }
    $user = unserialize(decrypt($signature, MD5_KEY));
    if (!key_exists('gid', (array)$user) || !isset($user['sp']) || (empty($user['name']) || empty($user['id']))) {
      @header('Location: index.php?act=login&op=login');
      exit;
    } else {
      //$this->systemSetKey($user);
    }
    return $user;
  }

  protected function signature()
  {
    @session_start();
    // 为避免与支付回调参数冲突,添加一个预设有限参数名
    $signatureKey = $this->signatureKey ? $this->signatureKey : 'signature';
    return !empty($_GET[$signatureKey]) ? $_GET[$signatureKey] : $_SESSION['sys_key'];
  }

  /**
   * 将验证内容写入对应cookie中
   * @param string $name 用户名
   * @param int $id 用户ID
   * @return bool 布尔类型的返回结果
   */
  protected final function systemSetKey($user, $exptime = '3600', $return = FALSE)
  {
    $value = encrypt(serialize($user), MD5_KEY);
    if ($return) {
      return $value;
    } elseif ($exptime == '3600') {
      @session_start();
      $_SESSION['sys_key'] = $value;
    } else {
      setNcCookie('sys_key', $value, $exptime, '', null);
    }
  }

  /**
   * 权限检测
   * copyright 2015-06-02, jack
   */
  protected function checkPcl($text = '', $show = 1)
  {
    if ($this->admin_info['sp'] == 1)
      return true;
    if (empty($this->permission)) {
      $gadmin = Model('admin_group')->getby_gid($this->admin_info['gid']);
      $permission = decrypt($gadmin['limits'], MD5_KEY . md5($gadmin['gname']));
      $this->permission = $permission = explode('|', $permission);
    } else {
      $permission = $this->permission;
    }
    $act = $_GET['act'] ? $_GET['act'] : $_POST['act'];
    $op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
    $type = $_GET['type'] ? $_GET['type'] : '';
    $perm = $type ? $act . '.' . $op . '.' . $type : $act . '.' . $op;
    if (!$text && !in_array($perm, $permission)) {
      redirect_url('您不具备进行该操作的权限');
    } elseif ($text == 'ajax') {
      if (!in_array($perm, $permission)) {
        die(json_encode(array('status' => 0, 'msg' => '您不具备进行该操作的权限')));
      }
    } elseif ($text == 'qBox') {
      if (!in_array($perm, $permission)) {
        die(json_encode(array('status' => 0, 'msg' => '您不具备进行该操作的权限')));
      } else {
        die(json_encode(array('status' => 1)));
      }
    } elseif ($text == 'dialog' && !in_array($perm, $permission)) {
      redirect_url('您不具备进行该操作的权限');
    }
  }

  /**
   * 验证当前管理员权限是否可以进行操作
   * @param string $link_nav
   * @return
   */
  protected final function checkPermission($link_nav = null)
  {
    if ($this->admin_info['sp'] == 1)
      return true;

    $act = $_GET['act'] ? $_GET['act'] : $_POST['act'];
    $op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

    if (empty($this->permission)) {
      $gadmin = Model('admin_group')->getby_gid($this->admin_info['gid']);
      $permission = decrypt($gadmin['limits'], MD5_KEY . md5($gadmin['gname']));
      $this->permission = $permission = explode('|', $permission);
    } else {
      $permission = $this->permission;
    }
  }

  /**
   * 过滤掉无权查看的菜单
   *
   * @param array $menu
   * @return array
   */
  private final function parseMenu($menu = array())
  {
    if ($this->admin_info['sp'] == 1)
      return $menu;
    foreach ($menu['left'] as $k => $v) {
      foreach ($v['list'] as $xk => $xv) {
        $tmp = explode(',', $xv['args']);
        $except = array('index', 'dashboard', 'login', 'common');
        if (in_array($tmp[1], $except))
          continue;
        if (!in_array($tmp[1], $this->permission) && !in_array($tmp[1] . '.' . $tmp[0], $this->permission)) {
          unset($menu['left'][$k]['list'][$xk]);
        }
      }
      if (empty($menu['left'][$k]['list'])) {
        unset($menu['top'][$k]);
        unset($menu['left'][$k]);
      }
    }
    return $menu;
  }

  /**
   * 取得顶部小导航
   *
   * @param array $links
   * @param 当前页 $actived
   */
  protected final function sublink($links = array(), $actived = '', $file = 'index.php')
  {
    $linkstr = '';
    foreach ($links as $k => $v) {
      parse_str($v['url'], $array);
      if (!$this->checkPermission($array))
        continue;
      $href = ($array['op'] == $actived ? null : "href=\"{$file}?{$v['url']}\"");
      $class = ($array['op'] == $actived ? "class=\"current\"" : null);
      $lang = L($v['lang']);
      $linkstr .= sprintf('<li><a %s %s><span>%s</span></a></li>', $href, $class, $lang);
    }
    return "<ul class=\"tab-base\">{$linkstr}</ul>";
  }

  /**
   * 记录系统日志
   * @param $lang 日志语言包
   * @param $state 1成功0失败null不出现成功失败提示
   * @param $admin_name
   * @param $admin_id
   */
  protected final function log($lang = '', $state = 1, $admin_name = '', $admin_id = 0)
  {
    if (!C('sys_log') || !is_string($lang))
      return;
    if ($admin_name == '') {
      $admin = unserialize(decrypt(cookie('sys_key'), MD5_KEY));
      $admin_name = $admin['name'];
      $admin_id = $admin['id'];
    }
    $data = array();
    if (is_null($state)) {
      $state = null;
    } else {
      $state = $state ? '' : L('nc_fail');
    }
    $data['content'] = $lang . $state;
    $data['admin_name'] = $admin_name;
    $data['createtime'] = TIMESTAMP;
    $data['admin_id'] = $admin_id;
    $data['ip'] = getIp();
    $data['url'] = $_REQUEST['act'] . '&' . $_REQUEST['op'];
    return Model('admin_log')->insert($data);
  }

  protected function set_perm_to()
  {
    Tpl::output('admin_info', $this->admin_info);
    Tpl::output('permission', $this->permission);
  }


  protected function base642jpeg($base64_string, $output_file)
  {
    if (!strpos($base64_string, ';base64,')) {
      return $output_file;
    }
    $ifp = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);

    return $output_file;
  }

  protected function getBase64FileName($base64_string, $path = null)
  {
    if (!strpos($base64_string, ';base64,')) {
      return (0 === strpos($base64_string, '/data/') ? '..' : '') . $base64_string;
    }
    $tmp = explode(',', $base64_string);
    $ext = preg_replace('/^data:image\/(png|jpg|jpeg|gif|webp);base64$/', '$1', $tmp[0]);
    if ($path && substr($path, -1) == '/') {
      $path = substr($path, 0, -1);
    }
    $dir = ($path ? $path : '../data/upload') . '/' . date('YmdH');
    mk_dir($dir);
    return $dir . '/' . uniqid() . '.' . $ext;
  }

  protected function _tcCodeValidate($tc_code)
  {
    return Model('trans_house')->getTransHouseInfo(array('tc_code' => $tc_code));
  }

  protected function _goodsIdValidate($goods_id)
  {
    return Model('goods')->getGoodsInfo(array('id' => $goods_id));
  }

  protected function _consignorIdValidate($consignor_id)
  {
    return Model('consignor')->getConsignorInfo(array('cid' => $consignor_id));
  }

  protected function _consigneeIdValidate($consignee_id)
  {
    return Model('consignee')->getConsigneeInfo(array('cid' => $consignee_id));
  }

  protected function _expressIdValidate($express_id)
  {
    return Model('express')->getExpressInfo(array('id' => $express_id));
  }

  protected function _catIdValidate($cat_id)
  {
    return Model('category')->getCategoryInfo(array('cat_id' => $cat_id));
  }

  protected function _unitIdValidate($unit_id)
  {
    return Model('measure')->getMeasureInfo(array('id' => $unit_id));
  }

  protected function getAreaIdByName($area_name, $level)
  {
    $area_id = Model('area')->getAreaInfo(array('area_name' => $area_name, 'area_deep' => $level), 'area_id');
    return $area_id ? $area_id['area_id'] : 0;
  }

  protected function IDCardValidate($ID)
  {
    include BASE_PATH . '/../vendor/cszchen/citizenid/Parser.php';

    $parser = new cszchen\citizenid\Parser();
    $parser->setId($ID);

    //身份证号码格式是否正确
    return $parser->isValidate();

    //获取生日，格式YYYYmmdd
    //        $parser->getBirthday();

    //获取性别, 1-男， 0-女，对应的常量为Parser::GENDER_MALE, Parser::FEMALE
    //        $parser->getGender();

    //获取行政区域,返回数组包含省，市，县，完整区域
    //        $parser->getRegion();
  }

  protected function smsInit()
  {
    static $Sms = null;
    if (is_null($Sms)) {
      require_once BASE_PATH . '/include/sdk/Sms.php';
      require_once BASE_PATH . '/include/sms.php';
      $Sms = new Sms(SMS_SERVER_URL, SMS_APP_KEY, SMS_SECRET);
    }
    return $Sms;
  }

  protected function smailInit()
  {
    static $Smail = null;
    if (is_null($Smail)) {
      require_once BASE_PATH . '/include/sdk/Smail.php';
      require_once BASE_PATH . '/include/smail.php';
      $Smail = new Smail(SMS_SERVER_URL, SMS_APP_KEY, SMS_SECRET);
    }
    return $Smail;
  }

  //@todo 创建一张邮件验证码的表: code_id, code, u_id, bs, expire
  protected function setEmailValidateCode($u_id, $bs = '', $expire = 86400) {
    $time = time();
    $condition = array('u_id' => $u_id, 'expire' => array('gt', $time));
    if ($bs) {
      $condition['bs'] = $bs;
    }
    $code = model('email_code')->getEmailCodeInfo($condition);
    if ($code) {
      return $code['code'];
    } else {
      $code = md5($u_id.'#'.uniqid());
      model('email_code')->addEmailCode(array(
        'code' => $code,
        'u_id' => $u_id,
        'bs' => $bs,
        'expire' => $expire?$time+$expire:0
      ));

      return $code;
    }
  }

  protected function checkEmailValidateCode($code, $bs = '') {
    $code = trim($code);
    if (!$code || strlen($code) != 32) {
      return FALSE;
    }
    $sql = "SELECT code_id, u_id FROM wl_email_code WHERE code = '{$code}' AND (expire <=> '0' OR expire >= "
      .time().")";
    if ($bs) {
      $sql .= " AND bs = '{$bs}'";
    }
    $code = model()->query($sql." LIMIT 1");
    if (!$code || empty($code[0])) {
      return FALSE;
    } else {
      model('email_code')->updateEmailCode(array('expire'=>time()), array('code_id'=>$code[0]['code_id']));
      return $code[0]['u_id'];
    }
  }
}
