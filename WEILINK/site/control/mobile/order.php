<?php

/**
 * 订单数据
 */
class orderControl extends SystemControl
{
  public function __construct()
  {
    // 查询物流信息不需要登录
    if (empty($_GET['op']) || 0!==strcasecmp($_GET['op'], 'getLogisticsInfo') && 0!==strcasecmp($_GET['op'], 'uploadImg')) {
      parent::__construct();
    }
  }

  public function listOp()
  {
    $orders = Model('order')->getOrderList(array('u_id'=>$this->admin_info['id'],'order_state'=>array('neq','24')));
    $list = array();
    foreach ($orders as $order) {
      $goods_list = Model()->query("SELECT goods_name FROM wl_order_goods WHERE order_id = '{$order['order_id']}'");
      $goods_names = array();
      foreach($goods_list as $gl) {
        $goods_names[] = $gl['goods_name'];
      }
      $item = array(
        'orderId' => $order['order_id'],
        'orderStatus' => str_replace(array('20','24','25','30','35','40','45'), array('审核中','审核失败','审核中','待入仓','待付款','已发货','已完成'), $order['order_state']),
        'orderTime' => date('Y-m-d H:i', $order['add_time']),
        'goodsName' => $goods_names,
        'consigneeName' => $order['reciver_name'],
        'expressNumber' => $order['shipping_code'],
        'expressPrice' => sprintf('%.2f',$order['shipping_fee'] + $order['extra_service_fee']),
        'dutyPrice' => $order['tariff_fee']
      );
      $list[] = $item;
    }

    $this->output(1, null, $list);
  }

  public function detailOp()
  {
    if (empty($_GET['orderId']) || !($order = Model('order')->getOrderInfo(array('order.order_id' => $_GET['orderId']))
      )) {
      $this->output(0, '无效的订单号');
    }

    $th = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order['tc_code']));
    $goods_list = Model()->query("SELECT goods_name FROM wl_order_goods WHERE order_id = '{$_GET['orderId']}'");
    $goods_names = array();
    foreach($goods_list as $gl) {
      $goods_names[] = $gl['goods_name'];
    }

    $data = array(
      'logisticsNumber' => $order['shipping_code'], //物流单号
      'warehouseAddress' => array( //货站地址
        'name' => $th['receiver'], //收货人姓名
        'address' => $th['address'], //地址
        'city' => $th['city'],
        'state' => $th['province'],
        'zipCode' => $th['zipcode'],
        'tel' => $th['phone']
      ),
      'expressCompanyName' => $order['company'], //快递公司
      'expressNumber' => $order['pre_track_no'] ? $order['pre_track_no'] : $order['track_no'], //快递单号
      'goodsWeight' => $order['order_weight'], //货物重量
      'logisticsPrice' => $order['shipping_fee'], //物流费用
      'valueAddedServicePrice' => $order['extra_service_fee'], //增值服务费
      'goodsName' => $goods_names, //商品名称
      'consigneeName' => $order['reciver_name'], //收件人姓名
      'consigneeAddress' => $order['reciver_address'], //收货人地址
      'remark' => $order['remark'], //备注
      'orderStatus' => str_replace(array('20', '24', '25', '30', '35', '40', '45'), array('审核中', '审核失败', '审核中', '待入仓', '待付款', '已发货', '已完成'), $order['order_state']) , //订单状态
      'orderNumber' => $order['order_sn'], //订单号
      'orderCreateTime' => date('Y-m-d H:i', $order['add_time']), //订单创建时间
      'timeBeStoreInBarn' => '', //订单入仓时间
      'orderPayTime' => '', //订单付款时间
      'orderDoneTime' => '',
      'dutyPrice' => $order['tariff_fee']
    );
    $this->output(1, null ,$data);
  }

  public function addConsignorOp()
  {
    //if (!Model('consignor')->getConsignorInfo(array('cid' => $_POST['consignorId'], 'u_id'=>$this->admin_info['id']))) {
    //  $this->output(0, '无效的发件人ID');
    //}
    if (empty($_POST['consignorName'])) {
      $this->output(0, '无效的发件人名称');
    }
    if (empty($_POST['consignorTel'])) {
      $this->output(0, '无效的发件人电话');
    }
    if (empty($_POST['consignorName'])) {
      $this->output(0, '无效的发件人名称');
    }
    if (empty($_POST['consignorProvince'])) {
      $this->output(0, '无效的发件人省份');
    }
    if (empty($_POST['consignorCity'])) {
      $this->output(0, '无效的发件人城市');
    }
    if (empty($_POST['consignorArea'])) {
      $this->output(0, '无效的发件人地区');
    }
    $consignorId = Model('consignor')->addConsignor(array(
      'u_id' => $this->admin_info['id'],
      'name' => $_POST['consignorName'],
      'phone' => $_POST['consignorTel'],
      'province' => $_POST['consignorProvince'],
      'city' => $_POST['consignorCity'],
      'area' => $_POST['consignorArea'],
      'address' => $_POST['consigneeProvince'].$_POST['consignorCity'].$_POST['consigneeArea'].$_POST['consignorAddress']
    ));

    $this->output(1, '添加成功', array('consignorId'=>$consignorId));
  }

  public function updateConsignorOp()
  {
    //if (empty($_POST['consignorId'])) {
    //  $this->output(0, '无效的订单ID');
    //}
    /*
    if (!Model('consignor')->getConsignorInfo(array('cid' => $_POST['consignorId'], 'u_id'=>$this->admin_info['id']))) {
      $this->output(0, '无效的订单ID');
    }
    */
    if (empty($_POST['consignorName'])) {
      $this->output(0, '无效的发件人名称');
    }
    if (empty($_POST['consignorTel'])) {
      $this->output(0, '无效的发件人电话');
    }
    if (empty($_POST['consignorProvince'])) {
      $this->output(0, '无效的发件人省份');
    }
    if (empty($_POST['consignorCity'])) {
      $this->output(0, '无效的发件人城市');
    }
    if (empty($_POST['consignorArea'])) {
      $this->output(0, '无效的发件人地区');
    }
    if (empty($_POST['consignorAddress'])) {
      $this->output(0, '无效的发件人地址');
    }
    /*
    Model('order_address')->updateConsignor(array(
      'name' => $_POST['consignorName'],
      'phone' => $_POST['consignorTel'],
      'province' => $_POST['consignorProvince'],
      'city' => $_POST['consignorCity'],
      'area' => $_POST['consignorArea'],
      'address' => $_POST['consigneeProvince'].$_POST['consignorCity'].$_POST['consigneeArea'].$_POST['consignorAddress']
    ), array('cid' => $_POST['consignorId']));*/
    Model('consignor')->updateConsignor(array(
      'name' => $_POST['consignorName'],
      'phone' => $_POST['consignorTel'],
      'province' => $_POST['consignorProvince'],
      'city' => $_POST['consignorCity'],
      'area' => $_POST['consignorArea'],
      'address' => $_POST['consigneeProvince'].$_POST['consignorCity'].$_POST['consigneeArea'].$_POST['consignorAddress']
    ), array('u_id'=>$this->admin_info['id']));

    Model('user_other')->updateUserOther(array(
      'first_name' => $_POST['consigneeName'],
      'last_name' => '',
      'province' => $_POST['consignorProvince'],
      'city' => $_POST['consignorCity'],
      'area' => $_POST['consignorArea'],
      'address' => $_POST['consigneeProvince'].$_POST['consignorCity'].$_POST['consigneeArea'].$_POST['consignorAddress'],
      'phone' => $_POST['consigneeTel']
    ), array('u_id'=>$this->admin_info['id']));

    $this->output(1, '更新成功');
  }

  public function addConsigneeOp()
  {
    if (empty($_POST['consigneeName'])) { //收货人姓名
      $this->output(0, '无效收件人姓名');
    }
    if (empty($_POST['consigneeTel'])) {//收货人电话
      $this->output(0, '无效收件人电话');
    }
    if (empty($_POST['consigneeCardId'])||!preg_match('/^[1-9]\d{16}[\dx]$/i', $_POST['consigneeCardId'])||!$this->IDCardValidate($_POST['consigneeCardId'])) {
      $this->output(0, '无效收件人身份证号码');
    }
    if (empty($_POST['IDCardFront'])) { //身份证正面
      $this->output(0, '无效身份证正面');
    }
    if (empty($_POST['IDCardBack'])) { //身份证反面
      $this->output(0, '无效身份证反面');
    }
    if (empty($_POST['consigneeProvince'])) { //收货人省份
      $this->output(0, '无效的收件人省份');
    }
    if (empty($_POST['consigneeCity'])) { //收货人城市
      $this->output(0, '无效的收件人城市');
    }
    if (empty($_POST['consigneeArea'])) { //收货人地区
      $this->output(0, '无效的收件人地址');
    }
    if (empty($_POST['consigneeAddress'])) { //收货人详细地址
      $this->output(0, '无效的收件人地址');
    }
    if (empty($_POST['zipCode'])) { //收货人详细地址
      $this->output(0, '无效的邮编');
    }
    $ID_front = $this->base642jpeg($_POST['IDCardFront'], $this->getBase64FileName($_POST['IDCardFront'],'../data/upload/idcard/'));
    $ID_back = $this->base642jpeg($_POST['IDCardBack'], $this->getBase64FileName($_POST['IDCardBack'],'../data/upload/idcard/'));
    $data = array(
      'name' => $_POST['consigneeName'],
      'province' => $_POST['consigneeProvince'],
      'city' => $_POST['consigneeCity'],
      'area' => $_POST['consigneeArea'],
      'address' => $_POST['consigneeAddress'],
      'zipcode' => $_POST['zipCode'],
      'phone' => $_POST['consigneeTel'],
      'u_id' => $this->admin_info['id'],
      'add_time' => time(),
      'ID' => $_POST['consigneeCardId'],
      'ID_front' => $ID_front,
      'ID_back' => $ID_back
    );
    $cid = Model('consignee')->addConsignee($data);
    if (!empty($_POST['isDefault'])) {
      $res = Model('consignee')->updateConsignee(array('is_default'=>'Y'),array(
        'cid'=>$cid));
      if ($res) {
        Model('consignee')->updateConsignee(array('is_default'=>'N'), array('u_id'=>$this->admin_info['id'],
          'cid'=>array('neq', $cid)));
      }
    }

    $this->output(1, '添加成功', array('consigneeId'=>$cid));
  }

  public function updateConsigneeOp()
  {
    if (empty($_POST['consigneeId'])) { //收货人ID
      $this->output(0, '无效收件人Id');
    }
    if (empty($_POST['consigneeName'])) { //收货人姓名
      $this->output(0, '无效收件人姓名');
    }
    if (empty($_POST['consigneeTel'])) {//收货人电话
      $this->output(0, '无效收件人电话');
    }
    if (empty($_POST['consigneeCardId'])||!preg_match('/^[1-9]\d{16}[\dx]$/i', $_POST['consigneeCardId'])||!$this->IDCardValidate($_POST['consigneeCardId'])) {
      $this->output(0, '无效收件人身份证号码');
    }
    if (empty($_POST['IDCardFront'])) { //身份证正面
      $this->output(0, '无效身份证正面');
    }
    if (empty($_POST['IDCardBack'])) { //身份证反面
      $this->output(0, '无效身份证反面');
    }
    if (empty($_POST['consigneeProvince'])) { //收货人省份
      $this->output(0, '无效的收件人省份');
    }
    if (empty($_POST['consigneeCity'])) { //收货人城市
      $this->output(0, '无效的收件人城市');
    }
    if (empty($_POST['consigneeArea'])) { //收货人地区
      $this->output(0, '无效的收件人地址');
    }
    if (empty($_POST['consigneeAddress'])) { //收货人详细地址
      $this->output(0, '无效的收件人地址');
    }
    if (empty($_POST['zipCode'])) { //收货人详细地址
      $this->output(0, '无效的邮编');
    }
    $ID_front = $this->base642jpeg($_POST['IDCardFront'], $this->getBase64FileName($_POST['IDCardFront'],'../data/upload/idcard/'));
    $ID_back = $this->base642jpeg($_POST['IDCardBack'], $this->getBase64FileName($_POST['IDCardBack'],'../data/upload/idcard/'));
    $data = array(
      'name' => $_POST['consigneeName'],
      'province' => $_POST['consigneeProvince'],
      'city' => $_POST['consigneeCity'],
      'area' => $_POST['consigneeArea'],
      'address' => $_POST['consigneeAddress'],
      'zipcode' => $_POST['zipCode'],
      'phone' => $_POST['consigneeTel'],
      'ID' => $_POST['consigneeCardId'],
      'ID_front' => $ID_front,
      'ID_back' => $ID_back
    );
    Model('consignee')->updateConsignee($data, array('cid'=>$_POST['consigneeId'], 'u_id'=>$this->admin_info['id']));

    if (!empty($_POST['isDefault'])) {
      $res = Model('consignee')->updateConsignee(array('is_default'=>'Y'),array(
        'u_id'=>$this->admin_info['id'], 'cid'=>$_POST['consigneeId']));
      if ($res) {
        Model('consignee')->updateConsignee(array('is_default'=>'N'), array('u_id'=>$this->admin_info['id'],
          'cid'=>array('neq', $_POST['consigneeId'])));
      }
    } else {
      Model('consignee')->updateConsignee(array('is_default'=>'N'), array('u_id'=>$this->admin_info['id'],
        'cid'=>$_POST['consigneeId']));
    }

    $this->output(1, '修改成功');
  }

  public function save2draftOp() {
    if (empty($_POST['warehouseId']) || !$this->_tcCodeValidate($_POST['warehouseId'])) {
      $this->output(0, '无效的货站ID');
    }
    $goodses = array();
    if (empty($_POST['goodsId']) || !is_array($_POST['goodsId'])) {
      $this->output(0, '无效的商品ID');
    } else {
      foreach ($_POST['goodsId'] as $gid) {
        $goods = $this->_goodsIdValidate($gid['goodsId']);
        if ($goods) {
          $goodses[] = $gid;
        }
      }
    }
    if (count($goodses) < 1) {
      $this->output(0, '无效的商品数据');
    }
    if (empty($_POST['consignorId']) || !$this->_consignorIdValidate($_POST['consignorId'])) {
      $this->output(0, '无效的发件人ID');
    }
    if (empty($_POST['consigneeId']) || !$this->_consigneeIdValidate($_POST['consigneeId'])) {
      $this->output(0, '无效的收件人ID');
    }
    Model('order_draft')->addOrderDraft(array(
      'warehouseId' => $_POST['warehouseId'],
      'goodsId' => json_encode($_POST['goodsId']),
      'consignorId' => $_POST['consignorId'],
      'consigneeId' => $_POST['consigneeId'],
      'valueAddedService' => $_POST['valueAddedService']? json_encode($_POST['valueAddedService']): '{}',
      'u_id' => $this->admin_info['id'],
      'saveStep' => !empty($_POST['saveStep'])? $_POST['saveStep']: 0,
      'add_time' => time()
    ), TRUE);

    $this->output(1, '保存成功');
  }

  public function getFromDraftOp() {
    $draft = Model('order_draft')->getOrderDraftInfo(array('u_id'=>$this->admin_info['id']));
    if ($draft) {
      $draft['goodsId'] = json_decode($draft['goodsId'],TRUE);
      $draft['addTime'] = date('Y-m-d H:i', $draft['add_time']);
      $draft['valueAddedService'] = json_decode($draft['valueAddedService']);
      unset($draft['u_id'], $draft['add_time']);
    }
    //$unValidate = Model('order')->getOrderList(array('u_id'=>$this->admin_info['id'],'order_state'=>'24'));
    $unValidate = Model('order')->query("SELECT * FROM wl_order WHERE u_id={$this->admin_info['id']} AND
    order_state='24'");
    $order_list = array();
    if ($unValidate) {
      $consignor = Model('consignor')->getConsignorInfo(array('u_id'=>$this->admin_info['id']));
      foreach ($unValidate as $v) {
        $goods = Model('order_goods')->getOrderGoodsList(array('order_id'=>$v['order_id']));
        $goods_list = array();
        foreach ($goods as $g) {
          $goods_list[] = array(
            'goodsId' => $g['goods_id'],
            'goodsName' => $g['goods_name'],
            'goodsPrice' => $g['goods_price'],
            'goodsCategoryId' => $g['cat_id'],
            'goodsBrand' => $g['bland'],
            'goodsUnitId' => $g['goods_unit'],
            'goodsNum' => $g['goods_num'],
          );
        }
        $order_list[] = array(
          'orderId' => $v['order_id'],             // 订单号
          'addTime' => date('Y-m-d H:i:s', $v['add_time']),             // 添加时间
          'warehouseId' => $v['tc_code'],
          'goodsId'=>$goods_list,
          'valueAddedService'=> $this->_getExtraService($v),   // 增值服务
          'consignorId'=>$consignor['cid'],
          'consigneeId'=>$v['consignee_id'],
          'expressCompanyName'=>$v['company'],   //快递公司
          'expressNumber'=>$v['pre_track_no'],      //快递单号
          'remark'=>$v['remark']
        );
      }
    }
    $data = array(
      'draftOrder' => $draft? array($draft):array(),
      'orderFailed' => $order_list
    );
    $this->output(1, NULL, $data);
  }

  public function getConsigneeListOp() {
    $consignees = Model('consignee')->getConsigneeList(array('u_id'=>$this->admin_info['id']));
    $list = array();
    foreach ($consignees as $c) {
      $list[] = array(
        'consigneeId' => $c['cid'],
        'name' => $c['name'],
        'tel' => $c['phone'],
        'address' => $c['address'],
        'CardId' => $c['ID'],
        'haveCardUpload' => !!$c['ID'],
        'front' => substr($c['ID_front'],2),
        'back' => substr($c['ID_back'],2),
        'zipCode' => $c['zipcode'],
        'consigneeProvince' => $c['province'],
        'consigneeCity' => $c['city'],
        'consigneeArea' => $c['area'],
        'isDefault' => $c['is_default'] == 'Y'?'1':''
      );
    }

    $this->output(1, null, $list);
  }

  public function saveOrderOp() {
    if (empty($_POST['warehouseId']) || !$this->_tcCodeValidate($_POST['warehouseId'])) {
      $this->output(0, '无效的货站ID');
    }
    $goodses = array();
    if (empty($_POST['goods']) || !is_array($_POST['goods'])) {
      $this->output(0, '无效的商品ID');
    } else {
      foreach ($_POST['goods'] as $g) {
        $goods = $this->_goodsIdValidate($g['goodsId']);
        if ($goods) {
          $cat = Model('category')->getCategoryInfo($goods['cat_id']);
          $unit = Model('measure')->getMeasureInfo($goods['unit_id']);
          $goodses[] = array(
            $cat['cat_name'],
            $unit['measure_name_cn'],
            $goods['name'],
            $goods['brand'],
            $goods['price'],
            $g['goodsNumber'],
            $g['goodsId'] // +商品ID
          ); // cat_name, measure_name_cn, goods_name, bland, goods_price, goods_num
        }
      }
    }
    if (count($goodses) < 1) {
      $this->output(0, '无效的商品数据');
    }
    if (empty($_POST['consignorId']) || !($consignor = $this->_consignorIdValidate($_POST['consignorId']))) {
      $this->output(0, '无效的发件人ID');
    }
    if (empty($_POST['consigneeId']) || !($consignee = Model('consignee')->getConsigneeInfo(array
      ('cid'=>$_POST['consigneeId'])))) {
      $this->output(0, '无效的收件人ID');
    }
    if (!$consignee['ID']) {
      $this->output(0,'收件人身份证号码为空');
    }
    if (empty($_POST['expressCompanyName'])) {
      $this->output(0, '无效的快递公司');
    }
    if (empty($_POST['expressNumber'])) {
      $this->output(0, '无效的快递单号');
    }

    // get IDCard
    $_POST['id_number'] = $consignee['ID'];
    $_POST['id_front'] = $consignee['ID_front'];
    $_POST['id_back'] = $consignee['ID_back'];
    $_POST['type'] = 'tp';
    $_POST['tc_code'] = $_POST['warehouseId'];
    $_POST['is_cover'] = '否';
    $_POST['express_no'] = $_POST['expressNumber'];
    $_POST['express'] = $_POST['expressCompanyName'];
    $_POST['box_change'] = !empty($_POST['valueAddedService']['box_change'])?
      $_POST['valueAddedService']['box_change']:'';
    $_POST['force_type'] = !empty($POST['valueAddedService']['force_type'])?
      $POST['valueAddedService']['force_type']: '0';
    $_POST['is_invoice'] = !empty($POST['valueAddedService']['is_invoice'])?
      $POST['valueAddedService']['is_invoice']: '否';
    $_POST['is_open'] = !empty($POST['valueAddedService']['is_open'])?
      $POST['valueAddedService']['is_open']: '否';
    $_POST['is_combine'] = !empty($POST['valueAddedService']['is_combine'])?
      $POST['valueAddedService']['is_combine']: '否';
    $_POST['paste_barcode'] = !empty($POST['valueAddedService']['paste_barcode'])?
      $POST['valueAddedService']['paste_barcode']: '否';
    $_POST['pack_size'] = !empty($POST['valueAddedService']['pack_size'])?
      $POST['valueAddedService']['pack_size']: '';
    $_POST['reciver_name'] = $consignee['name'];
    $_POST['provincer'] = $consignee['province'];
    $_POST['cityr'] = $consignee['city'];
    $_POST['arear'] = $consignee['area'];
    $_POST['reciver_address'] = $consignee['address'];
    $_POST['reciver_phone'] = $consignee['phone'];
    $_POST['reciver_zipcode'] = $consignee['zipcode'];
    $_POST['provinces'] = $consignor['province'];
    $_POST['sender_address'] = $consignee['address'];
    $_POST['sender_zipcode'] = $consignee['zipcode'];
    $_POST['sender_phone'] = $consignee['phone'];
    $_POST['citys'] = $consignor['city'];
    $_POST['areas'] = $consignor['area'];
    $_POST['goods'] = $goodses;

    // order_id
    if (!empty($_POST['orderId'])) {
      $_POST['order_id'] = $_POST['orderId'];
    }

    require_once BASE_PATH.'/control/order_tp.php';
    $order_tp = new order_tpControl();
    $order_tp->save_orderOp($_POST);
  }

  public function getLogisticsInfoOp() {
    if (empty($_GET['logisticsNumber'])) {
      $this->output(0, '无效的物流单号');
    }

    //$order_info = Model('order')->getOrderInfo(array('u_id'=>$this->admin_info['id'], 'shipping_code'=>$_GET['logisticsNumber']));
    $order_info = Model('order')->getOrderInfo(array('shipping_code'=>$_GET['logisticsNumber']));
    if (!$order_info) {
      $this->output(0, '无效的物流单号');
    }

    $track_no = $order_info['track_no'] ? $order_info['track_no'] : $order_info['pre_track_no'];
    if ($track_no) {
      $list = array();
      if (in_array($_SERVER['HTTP_HOST'], array('local-welink.dxomni.com', 'dev-welink.dxomni.com'))) {
        $list[] = array('time'=>'2016-07-05 17:37:08', 'info'=> '已收寄');
        $list[] = array('time'=>'2016-07-05 17:37:08', 'info'=> 'EMS跟踪号【BZ000002576HK】，您可以到EMS官网查询');
        $list[] = array('time'=>'2016-07-05 17:37:08', 'info'=> '您的包裹【345345】已入库');
      } else {
        $result = Model('package_service')->queryOrderStatus($track_no);
        if ($result->ResponseResult == 'Success') {
          $data = (array)$result->Data->TraceFlow->TraceStatus;
          if (!isset($data[0])) {
            $data[0] = $data;
          }
          foreach ($data as $d) {
            $list[] = array(
              'time' => preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $d->CreatedTime),
              'info' => $d->StatusDesc
            );
          }
        }
      }
      $this->output(1, null, $list);
    } else {
      $this->output(0, '无效的物流单号');
    }
  }

  /**
   * 获取交易记录
   */
  public function getMoneyLogOp() {
    $condition = array('u_id'=>$this->admin_info['id']);

    $list = model('money_log')->getMoneyLogList($condition);
    $data = array();
    foreach ($list as $k=>$v) {
      $data[$k] = array(
        'transactionId' => $v['log_id'],
        'title' => $v['title'],
        'time' => date('Y-m-d H:i', $v['add_time']),
        'transactionType' => str_replace(array('in','out'), array('收入','支出'), $v['type']),
        'transactionAmounts' => $v['amount'],
        'balance' => $v['balance']
      );
    }

    $this->output(1, NULL, $data);
  }

  /**
   * 获取交易记录详情
   */
  public function getMoneyLogDetailOp() {
    $condition = array('u_id'=>$this->admin_info['id'], 'log_id'=>$_GET['transactionId']);

    $info = model('money_log')->getMoneyLogInfo($condition);
    if ($info) {
      if ($info['flow_id']) {
        $payLog = model('pay_log')->getPayLogInfo(array('flow_id' => $info['flow_id']));
      } else {
        $payLog = NULL;
      }
      $data = array(
        'time' => date('Y-m-d H:i', $info['add_time']),
        'transactionType' => str_replace(array('in', 'out'), array('收入', '支出'), $info['type']),
        'transactionAmounts' => $info['amount'],
        'balance' => $info['balance'],
        'orderNumber' => $payLog ? $payLog['order_sn'] : '',
        'PaymentMethod' => $payLog ? str_replace(array('1','12','13','99'), array('银联', '支付宝', '微信', '余额支付'), $payLog['payment']) : ''
      );
    } else {
      $this->output(0, '无效的记录ID');
    }

    $this->output(1, NULL, $data);
  }

  /**
   * 删除收件人
   */
  public function deleteConsigneeOp() {
    $cid = $_POST['consigneeId'];
    if (!$cid) {
      $this->output(0, '无效的收件人ID');
    }
    model('consignee')->delConsignee(array('u_id'=>$this->admin_info['id'],'cid'=>$cid));
    $this->output(1,'删除成功');
  }

  /**
   * 删除商品
   */
  public function deleteGoodsOp() {
    $gid = $_POST['goodsId'];
    if (!$gid) {
      $this->output(0, '无效的商品ID');
    }
    model('goods')->delGoods(array('u_id'=>$this->admin_info['id'],'id'=>$gid));
    $this->output(1,'删除成功');
  }

  /**
   * 删除草稿
   */
  public function deleteDraftOp() {
    model('order_draft')->delOrderDraft(array('u_id'=>$this->admin_info['id']));
    $this->output(1,'删除成功');
  }

  /**
   * 删除订单
   */
  public function deleteOrderOp() {
    $oid = $_POST['orderId'];
    if (!$oid) {
      $this->output(0, '无效的订单ID!');
    }
    $order = model('order')->getOrderInfo(array('u_id'=>$this->admin_info['id'],'order.order_id'=>$oid));
    if (!$order) {
      $this->output(0,'无效的订单ID!!');
    }
    $consignor = model('consignor')->getConsignorInfo(array('u_id'=>$this->admin_info['id']));
    model('order')->delOrder($oid, $consignor? $consignor['name']:NULL);
    model('order_address')->delOrderAddress(array('order_id'=>$oid));
    model('order_goods')->delOrderGoods(array('order_id'=>$oid));
    model('order_log')->delOrderLog(array('order_id'=>$oid));
    model('order_logistics_log')->delOrderLogisticsLog(array('order_id'=>$oid));

    $this->output(1,'删除成功');
  }

  public function uploadImgOp() {
    $dir = $_POST['dir']? $_POST['dir']: 'other';
    $img = $this->base642jpeg($_POST['img'], $this->getBase64FileName($_POST['img'],'../data/upload/'.$dir.'/'));
    $this->output(1, NULL, $img);
  }

  private function _getExtraService($order) {
    $extra_services_list = require BASE_PATH.'/include/extra_service_fee.php';
    $return = array();
    foreach ($extra_services_list as $ekey=>$eval) {
      list($key, $val) = explode(':', $ekey);
      if (isset($order[$key]) && !isset($return[$key])) {
        $return[$key] = $order[$key];
      }
    }
    return $return;
  }
}
