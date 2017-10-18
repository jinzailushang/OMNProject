<?php

/**
 * 转运单管理
 * @copyright (c) 2016-05-23, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class order_tpControl extends SystemControl {

    const EXPORT_SIZE = 1000;
    const EMPTY_STR = '--';

    public $order_types = array('1' => '直邮', '转运', '备货');

    public function __construct() {
        // 为避免与支付回调参数冲突,此处充值参数名
        if (FALSE !== strpos($_SERVER['QUERY_STRING'], 'act=order_tp&op=payStatusPage')) {
            $this->signatureKey = 'NULL_STRING';
        }
        parent::__construct();
        Tpl::setDir('tpl/order');
    }

    public function indexOp() {
        //转运国
        $trans_list = Model('trans_house')->getTransHouseList();
        // 获取各个状态的数量
        $res = Model()->query("SELECT order_state,count(1) as n FROM wl_order WHERE u_id = {$this->admin_info['id']}
    GROUP BY order_state");
        $state_counts = array();
        if ($res) {
            foreach ($res as $r) {
                $state_counts[$r['order_state']] = $r['n'];
            }
        }
        //$channel = Model()->table('trans_house_channel')->field('channel_name,channel_code')->where()->select();
        Tpl::output('trans_list', $trans_list);
        Tpl::output('state_counts', $state_counts);
        Tpl::output('position', '转运单管理');
        Tpl::showpage('tp', 'index_layout');
    }

    /**
     * 获取入库单列表填充数据
     * @return JSON
     */
    public function get_dataOp() {
        $model_order = Model('order');
        $condition = $this->get_search_condition();

        $count = $model_order->getOrderCount($condition);
        $order_list = $model_order->getOrderList($condition, '*', 20);

        $temp = array();
        if ($order_list) {
            foreach ($order_list as $k => &$v) {
                //$goods = Model()->table('order_goods')->where(array('order_id' => $v['order_id']))->field('group_concat(goods_name) as name')->find();
                $tc = Model('trans_house')->getTransHouseInfo(array('tc_code' => $v['tc_code']));
                //$v['gname'] = $goods['name'];
                //$v['gname_s'] = str_cut($goods['name'], 16) . '...';
                $v['shipping_code'] = $v['shipping_code'] ? $v['shipping_code'] : self::EMPTY_STR;
                $v['country'] = $tc['country'];
                $v['tc_name'] = $tc['tc_name'];
                $v['order_type'] = $this->order_types[$v['order_type']];
                //$v['tc_csg'] = $tc['consignee'];
            }
            
            die(json_encode(array('status' => 1, 'data' => $order_list, 'page' => $model_order->showpage(9, 'clickpage'), 'count' => $count)));
        }
        die(json_encode(array('status' => 0, 'msg' => '暂无数据', 'count' => $count)));
    }

    /**
     * 获取搜索条件
     * @return string
     */
    public function get_search_condition() {
        $condition = array();

        $condition['order_type'] = $_GET['type'];
        if ($_GET['status']) {
            $condition['order_state'] = $_GET['status'];
        }
        if (trim($_GET['reciver_name'])) {
            $condition['reciver_name'] = $_GET['reciver_name'];
        }
        if ($_GET['tc_code']) {
            $condition['tc_code'] = $_GET['tc_code'];
        }
        if ($_GET['order_sn']) {
            $condition['customer_code'] = $_GET['order_sn'];
        }
        if ($_GET['pre_track_no']) {
            $condition['pre_track_no'] = $_GET['pre_track_no'];
        }
        if ($_GET['shipping_code']) {
            $condition['shipping_code'] = $_GET['shipping_code'];
        }
        if ($_GET['order_type']) {
            $condition['order_type'] = $_GET['order_type'];
        }
        if ($_GET['ship_method']) {
            $condition['ship_method'] = $_GET['ship_method'];
        }

        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['end_date']);
        $start_unixtime = $if_start_time ? $_GET['start_date'] : null;
        $end_unixtime = $if_end_time ? $_GET['end_date'] : null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time', array($start_unixtime, $end_unixtime));
        }
        //if ($this->admin_info['sp'] == 0) {
        $condition['u_id'] = $this->admin_info['id'];
        //}
        return $condition;
    }

    public function getFormOp() {
        //省份
        $pro_list = Model('area')->getAreaList(array('area_parent_id' => 0));
        //分类
        $cate_list = Model('category')->getCategoryList();
        //单位
        $unit_list = Model('measure')->getMeasureList();
        //转运国
        $trans_list = Model('trans_house')->getTransHouseList();
        //最后添加的订单
        $order = array();
        $latest_tccode = '';
        if (!empty($_GET['order_id'])) {
            $order = Model('order')->getOrderInfo(array('u_id' => $this->admin_info['id'], 'order.order_id' => $_GET['order_id']));
            Tpl::output('order_info', $order);
            $latest_tccode = $order['tc_code'];
            $goods_list = Model()->query("SELECT g.*, c.*, m.* FROM wl_order_goods g, wl_category c, wl_measure m WHERE g
      .order_id = '{$_GET['order_id']}' AND g.cat_id = c.cat_id AND g.goods_unit = m.id");
            Tpl::output('goods_list', $goods_list);

            $tc = Model('trans_house')->getTransHouseInfo(array('tc_code' => $latest_tccode), 'tid');
            $channel = Model('trans_house_channel')->getTransHouseChannelInfo(array('channel_code' => $order['ship_method'], 'house_id' => $tc['tid']), 'channel_name');
            //print_r($channel);die;
            Tpl::output('channel', array('name' => $channel['channel_name'], 'code' => $order['ship_method']));
        } else {
            $order = Model('order')->getOrderInfo(array('u_id' => $this->admin_info['id']), 'tc_code,ship_method');
            if ($order) {
                $latest_tccode = $order['tc_code'];
            }
            // 获取默认收件人
            $consignee = Model('consignee')->getConsigneeInfo(array('u_id' => $this->admin_info['id'], 'is_default' => 'Y'));
            if ($consignee) {
                Tpl::output('order_info', array(
                    'reciver_name' => $consignee['name'],
                    'reciver_state' => $consignee['province'],
                    'reciver_city' => $consignee['city'],
                    'reciver_area' => $consignee['area'],
                    'reciver_address' => $consignee['address'],
                    'reciver_zipcode' => $consignee['zipcode'],
                    'reciver_phone' => $consignee['phone'],
                    'identity_code' => $consignee['ID'],
                    'id_card_front' => $consignee['ID_front'],
                    'id_card_back' => $consignee['ID_back'],
                ));
            }
        }
        //获取对应转运仓的运输方式(即渠道信息)
        $chns = Model()->table('trans_house_channel,trans_house')->join('inner join')->on('trans_house_channel.house_id=trans_house.tid')->field('channel_code,channel_name')->where(array('tc_code' => $latest_tccode))->select();

        //发件人信息
        $user_info = Model('consignor')->getConsignorInfo(array('u_id' => $this->admin_info['id']));
        Tpl::output('pro_list', $pro_list);
        Tpl::output('cate_list', $cate_list);
        Tpl::output('unit_list', $unit_list);
        Tpl::output('trans_list', $trans_list);
        Tpl::output('latest_tccode', $latest_tccode);
        Tpl::output('channels', $chns);
        Tpl::output('user_info', $user_info);
        Tpl::output('extra_service_list', require BASE_PATH . '/include/extra_service_fee.php');
        Tpl::showpage('form', 'nothing');
    }

    public function addGoodsOp() {
        if (empty($_POST['name'])) {
            $this->output(0, '无效的商品名称');
        }
        if (empty($_POST['cat_id']) || !$this->_catIdValidate($_POST['cat_id'])) {
            $this->output(0, '无效的分类ID');
        }
        if (empty($_POST['unit_id']) || !$this->_unitIdValidate($_POST['unit_id'])) {
            $this->output(0, '无效的单位ID');
        }
        if (empty($_POST['price']) || !preg_match('/^[\d\.]+$/', $_POST['price'])) {
            $this->output(0, '无效的价格');
        }
        if (Model('goods')->getGoodsInfo(array('u_id' => $this->admin_info['id'], 'name' => $_POST['name']), 'id')) {
            $this->output(0, '商品已存在');
        }

        $goods_id = Model('goods')->addGoods(array(
            'u_id' => $this->admin_info['id'],
            'name' => $_POST['name'],
            'cat_id' => $_POST['cat_id'],
            'unit_id' => $_POST['unit_id'],
            'brand' => $_POST['brand'],
            'price' => $_POST['price'],
            'add_time' => time()
        ));

        $this->output($goods_id ? 1 : 0, !$goods_id ? '添加商品失败' : null, $goods_id);
    }

    public function getGoodsListOp() {
        $model = Model('goods');
        $condition = array('u_id' => $this->admin_info['id']);
        if (!empty($_GET['q'])) {
            $condition['name'] = array('like', "%{$_GET['q']}%");
        }
        $count = $model->getGoodsCount($condition);
        $goods_list = $model->getGoodsList($condition, '*', 10);

        $temp = array();
        if ($goods_list) {
            foreach ($goods_list as $k => &$v) {
                $cat = Model('category')->getCategoryInfo(array('cat_id' => $v['cat_id']), 'cat_name');
                $v['cat_name'] = $cat['cat_name'];
                $unit = Model('measure')->getMeasureInfo(array('id' => $v['unit_id']), 'measure_name_cn');
                $v['unit_name'] = $unit['measure_name_cn'];
            }
            die(json_encode(array('status' => 1, 'data' => $goods_list, 'page' => $model->showpage(9, 'getGoodsList'), 'count' => $count)));
        }
        die(json_encode(array('status' => 0, 'msg' => '暂无数据', 'count' => $count)));
    }

    public function getConsigneeListOp() {
        $model = Model('consignee');
        $condition = array('u_id' => $this->admin_info['id']);
        if (!empty($_GET['q'])) {
            $condition['name'] = array('like', "%{$_GET['q']}%");
        }
        $count = $model->getConsigneeCount($condition);
        $consignee_list = $model->getConsigneeList($condition, '*', 10);

        if ($consignee_list) {
            die(json_encode(array('status' => 1, 'data' => $consignee_list, 'page' => $model->showpage(9, 'getContactList'), 'count' => $count)));
        }
        die(json_encode(array('status' => 0, 'msg' => '暂无数据', 'count' => $count)));
    }

    public function uploadIdCardOp() {
        $ID_front = $this->base642jpeg($_POST['file'], $this->getBase64FileName($_POST['file'], '../data/upload/idcard'));
        echo $ID_front;
    }

    /**
     * 详情显示页面
     * @return tpl
     */
    public function detailOp() {
        $receiving_id = (int) $_GET['receiving_id'];
        if (empty($receiving_id)) {
            return;
        }
        //获取入库单信息
        $pr_info = Model('receiving')->getOne(array('receiving_id' => $receiving_id));
        $pr_id = $pr_info ? $pr_info['pr_id'] : 0;
        //获取入库单明细
        $detail = Model('storage_planing_receipts_goods')->getStoragePlaningReceiptsGoodsList(array('pr_id' => $pr_id), self::EXPORT_SIZE);

        Tpl::output('info', $pr_info);
        Tpl::output('detail', $detail);
        Tpl::output('empty_str', self::EMPTY_STR);
        Tpl::output('extra_service_list', require BASE_PATH . '/include/extra_service_fee.php');
        Tpl::showpage('detail', 'null_layout');
    }

    /**
     * 保存订单
     */
    public function save_orderOp($POST = NULL) {
        if ($POST) {
            $post_data = $POST;
        } else {
            $post_data = $this->getPostArray();
        }


        $type = $post_data['type'];
        $order = array();
        $order['order_sn'] = '';
        //$shipment_code = $this->autoGetShipmentCode();
        $order['customer_code'] = isset($post_data['customer_code']) ? $post_data['customer_code'] : '';
        $order['tc_code'] = $post_data['tc_code'];
        //1-直邮单 2-转运单
        $order['order_type'] = 2;
        $order['u_id'] = $this->admin_info['id'];


        $order['origin'] = 'China';
        $order['is_tariff'] = $post_data['is_tariff'] ? 'Y' : 'N';
        $order['is_cover'] = $post_data['is_cover'] ? 'Y' : 'N';
        $order['add_time'] = time();
        //如果是转运单
        //if ($type == 'tp') {
        $order['pre_track_no'] = $post_data['express_no'];
        $order['company'] = $post_data['express'];
        $order['ship_method'] = $post_data['ship_method'];
        $order['box_change'] = $post_data['box_change'] ? $post_data['box_change'] : '';
        $order['force_type'] = $post_data['force_type'] ? $post_data['force_type'] : '';
        $order['invoice_out'] = $post_data['invoice_out'] ? 'Y' : 'N';
        $order['open_box'] = $post_data['open_box'] ? 'Y' : 'N';
        $order['insured'] = $post_data['insured'] ? $post_data['insured'] : '';
        $order['remark'] = $post_data['remark'];
        //}
        // 包装信息
        $order['combine_separate'] = $post_data['combine_separate'] ? $post_data['combine_separate'] : '';
        $order['paste_barcode'] = $post_data['paste_barcode'] ? 'Y' : 'N';
        $order['pack_size'] = $post_data['pack_size'] ? $post_data['pack_size'] : '';

        // 计算增值服务费
        $order['extra_service_fee'] = $this->countExtraServiceFee($order);

        //订单地址信息
        $data_address = array();
        $data_address['reciver_name'] = $post_data['reciver_name'];
        $data_address['reciver_state'] = $post_data['provincer'];
        $data_address['reciver_city'] = $post_data['cityr'];
        $data_address['reciver_area'] = $post_data['arear'];
        $data_address['reciver_address'] = $post_data['reciver_address'];
        $data_address['reciver_phone'] = $post_data['reciver_phone'];
        $data_address['reciver_zipcode'] = $post_data['reciver_zipcode'];
        $data_address['has_identity'] = '是';
        $data_address['consignee_id'] = $post_data['consigneeId']; // +收件人ID

        if (!$this->IDCardValidate($post_data['id_number'])) {
            $this->output(0, '无效的身份证号码');
        }
        $data_address['identity_code'] = $post_data['id_number'];
        $data_address['id_card_front'] = $post_data['id_front'];
        $data_address['id_card_back'] = $post_data['id_back'];
        //发件人信息从会员自身资料获取
        $consignor = Model('consignor')->getConsignorInfo(array('u_id' => $this->admin_info['id']));
        if (!$consignor || !$consignor['phone']) {
            if (empty($post_data['sender_name']) || empty($post_data['sender_phone']) || empty($post_data['sender_zipcode']) || empty($post_data['sender_address']) || empty($post_data['provinces']) || empty($post_data['citys']) || empty($post_data['areas'])) {
                $this->output(0, '无效的发件人信息');
            } else {
                $consignor_data = array(
                    'name' => $post_data['sender_name'],
                    'u_id' => $this->admin_info['id'],
                    'province' => $post_data['provinces'],
                    'city' => $post_data['citys'],
                    'area' => $post_data['areas'],
                    'phone' => $post_data['sender_phone'],
                    'zipcode' => $post_data['sender_zipcode'],
                    'address' => $post_data['sender_address']
                );
                if (!$consignor) {
                    $consignor_data['add_time'] = time();
                    Model('consignor')->addConsignor($consignor_data);
                } else {
                    Model('consignor')->updateConsignor($consignor_data, array('cid'=>$consignor['cid']));
                }
                $consignor = $consignor_data;
            }
        }
        $data_address['sender'] = $consignor['name'];
        $data_address['sender_phone'] = $consignor['phone'];
        $data_address['sender_zipcode'] = $consignor['zipcode'];
        $data_address['sender_address'] = $consignor['address'];
        $data_address['sender_province'] = $consignor['province'];
        $data_address['sender_city'] = $consignor['city'];
        $data_address['sender_area'] = $consignor['area'];

        $order['order_address'] = $data_address;
        //收件人信息
        $data_consignee = array();
        $data_consignee['name'] = $post_data['reciver_name'];
        $data_consignee['u_id'] = $this->admin_info['id'];
        $data_consignee['province'] = $post_data['provincer'];
        $data_consignee['city'] = $post_data['cityr'];
        $data_consignee['area'] = $post_data['arear'];
        $data_consignee['address'] = $post_data['reciver_address'];
        $data_consignee['zipcode'] = $post_data['reciver_zipcode'];
        $data_consignee['phone'] = $post_data['reciver_phone'];
        $data_consignee['ID'] = $post_data['id_number'];
        $data_consignee['ID_front'] = $post_data['id_front'];
        $data_consignee['ID_back'] = $post_data['id_back'];
//    $data_consignee['cid'] = $post_data['consigneeId']; // +收件人ID
        $order['consignee'] = $data_consignee;
        $order_amount = 0;
        $goods = array();
        foreach ($_POST['goods'] as $k => $v) {
            $cate = Model('category')->getCategoryInfo(array('cat_name' => $v[0]));
            $unit = Model('measure')->getMeasureInfo(array('measure_name_cn' => $v[1]));
            $goods[$k]['cat_id'] = $cate ? $cate['cat_id'] : 0;
            $goods[$k]['goods_unit'] = $unit ? $unit['id'] : 0;
            $goods[$k]['goods_name'] = $v[2];
            $goods[$k]['bland'] = $v[3];
            $goods[$k]['goods_price'] = $v[4];
            $goods[$k]['goods_num'] = $v[5];
            $order_amount += $v[4] * $v[5];
            $goods[$k]['goods_id'] = isset($v[6]) ? $v[6] : 0; // +商品ID
        }
        $order['order_amount'] = $order_amount;
        $order['order_goods'] = $goods;
        if (!empty($post_data['order_id'])) {
            $order['order_id'] = $post_data['order_id'];
        }
        if (!empty($post_data['consignee_id'])) {
            $order['consignee_id'] = $post_data['consignee_id'];
        }
        $data[0] = $order;

        $res = Model('order')->saveOrder($data, $this->admin_info['id'], $type);
        if ($res['snum'] == 1) {
            Model('shipment_code')->updateShipmentCode(array('flag' => '1', 'use_time' => time()), array('scode' => $shipment_code));
            die(json_encode(array('status' => 1, 'msg' => !empty($post_data['order_id']) ? '修改成功' : '添加成功', 'data' => array
                    ('orderId' => $res['orderIds'][0]))));
        }
        die(json_encode(array('status' => 0, 'msg' => mb_substr($res['error'], 4, 200, 'utf-8'))));
    }
    /**
     * 批量同步订单到仓库
     */
    public function batch_syncOp() {
        set_time_limit(0);
        $model_ship = Model('shipment_code');
        $model_house = Model('trans_house');
        $model_api = Model('api/transOrder');
        $model_order = Model('order');
        $condition['order_state'] = 20;
        $condition['order_type'] = 2;
        if($this->admin_info['sp'] != 1){
            $condition['u_id'] = $this->admin_info['id'];
        }
        //一次同步500条
        $list = Model('order')->getOrderList($condition, '*', 500);
        if ($list) {
            $s_num = $f_num = 0;
            foreach ($list as $k => $row) {
                $house = $model_house->getTransHouseInfo(array('tc_code' => $row['tc_code']), 'tc_type');
                //查找一个没有使用过的物流单号赋予shipping_code
                $ship = $model_ship->getShipmentCodeInfo(array('flag' => 0));
                if ($ship) {
                    $code = $ship['scode'];
                } else {
                    //如果物流单号已用完，则生成一个
                    $code = $model_ship->buildCode(1);
                }
                if ($house['tc_type'] != 'wms') {
                    continue;
                }
                $row['shipping_code'] = $code;
                $result = $model_api->createWmsOrder($row);
                if($result['status'] == 1){
                    $update_arr = array(
                        'order_state' => 30,   //待入仓
                        'shipping_code' => $code
                    );
                    $model_order->updateOrder($update_arr, array('order_id' => $row['order_id']));
                    $model_ship->updateShipmentCode(array('flag' => '1', 'use_time' => time()), array('scode' => $code));
                    //die(json_encode(array('status' =>$result['status'], 'msg' => $result['msg'])));
                    $s_num ++;
                }else{
                    $f_num ++;
                }
            }
            die(json_encode(array('status' =>1, 'msg' => '同步完成','s_num'=>$s_num,'f_num'=>$f_num)));
        }else{
            die(json_encode(array('status' =>0, 'msg' => '没有可同步的订单！')));
        }
    }

    /**
     * 订单导入页面
     */
    public function importOp() {
        $title = $_GET['type'] == 'dm' ? '直邮单导入' : '转运单导入';
        Tpl::output('position', $title);
        Tpl::showpage('import', 'index_layout');
    }

    /**
     * 导出数据
     */
    public function exportOp() {
        set_time_limit(0);
        $model = Model('order');
        $condition = $this->get_search_condition();
        if (!is_numeric($_GET['curpage'])) {
            $count = $model->getOrderCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE) { //显示下载链接
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                Tpl::output('list', $array);
                Tpl::output('murl', 'index.php?act=order&op=index');
                Tpl::showpage('export.excel');
            } else { //如果数量小，直接下载
                $data = $model->getOrderList($condition, '*', '', 'order.order_id desc', self::EXPORT_SIZE);
                $this->createExcel($data);
            }
        } else { //下载
            $limit1 = ($_GET['curpage'] - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model->getOrderList($condition, '*', '', 'order.order_id desc', "{$limit1},{$limit2}");
            $this->createExcel($data);
        }
    }

    public function paymentOp() {
        if ($_GET['pay_for'] == 'order_shipping_fee') {
            $info = Model('order')->getOrderInfo(array('order.order_id' => $_GET['order_id']));
            $money = Model('money')->getMoneyInfo(array('u_id' => $this->admin_info['id']));
            Tpl::output('orderInfo', $info);
            Tpl::output('money', $money);
        } elseif ($_GET['pay_for'] == 'tax') {
            $info = Model('order')->getOrderInfo(array('order.order_id' => $_GET['order_id']));
            $money = Model('money')->getMoneyInfo(array('u_id' => $this->admin_info['id']));
            Tpl::output('orderInfo', $info);
            Tpl::output('money', $money);
        }elseif ($_GET['pay_for'] == 'recharge') {
            
        }
        Tpl::showpage('payment', 'null_layout');
    }

    public function gotoPayOp() {
        $req = array(
            'notifyUrl' => $GLOBALS['config']['site_site_url'] . '/../data/ipaynow/api/front_notify.php',
            'frontNotifyUrl' => $GLOBALS['config']['site_site_url'] . '/../data/ipaynow/api/notify.php',
            'payChannelType' => $_GET['paymentType'],
        );
        if ($_GET['pay_for'] == 'order_shipping_fee') {
            $info = Model('order')->getOrderInfo(array('order.order_id' => $_GET['order_id']));
            $req = array_merge($req, array(
                'mhtOrderName' => $info['order_sn'] . '-' . uniqid(),
                'mhtOrderAmt' => ($info['shipping_fee'] + $info['extra_service_fee']) * 100,
                'mhtOrderDetail' => $info['order_sn'] . '运单物流费合计',
                // @todo 重复支付的问题
                'mhtOrderNo' => $info['order_sn'],
                'mhtReserved' => 'order_shipping_fee'
            ));
        } elseif($_GET['pay_for'] == 'tax'){
            $info = Model('order')->getOrderInfo(array('order.order_id' => $_GET['order_id']));
            $req = array_merge($req, array(
                'mhtOrderName' => $info['order_sn'] . '-' . uniqid(),
                'mhtOrderAmt' => ($info['shipping_fee'] + $info['extra_service_fee']) * 100,
                'mhtOrderDetail' => $info['order_sn'] . '缴税费用',
                // @todo 重复支付的问题
                'mhtOrderNo' => $info['order_sn'],
                'mhtReserved' => 'tax'
            ));
        }else {
            $order_sn = uniqid('RC');
            $req = array_merge($req, array(
                'mhtOrderName' => $order_sn . '充值',
                'mhtOrderAmt' => $_GET['recharge_amount'] * 100,
                'mhtOrderDetail' => $order_sn . '账户充值',
                'mhtOrderNo' => $order_sn,
                'mhtReserved' => 'recharge'
            ));
        }
        // 如果是余额支付
        if ($_GET['paymentType'] == '99') {
            $this->payFromMoney($info,$_GET['pay_for']);
        } else {
            header('Location:../data/ipaynow/api/trade.php?' . http_build_query($req));
        }
    }

    public function payLogOp() {
        $data = array(
            'order_sn' => $_GET['mhtOrderNo'],
            'u_id' => $this->admin_info['id'],
            'amount' => $_GET['mhtOrderAmt'] / 100,
            'payment' => $_GET['payChannelType'],
            'signature' => $_GET['mhtReserved'],
            'pay_time' => time(),
            'timeout' => $_GET['mhtOrderTimeOut'],
            'status' => 'paying',
            'notify_time' => 0,
            'pay_data' => json_encode($_GET),
            'pay_for' => $_GET['pay_for']
        );

        Model('pay_log')->addPayLog($data);
        header('Location:' . safe_b64decode($_GET['pay_url']));
    }

    // payment notify
    /*
      public function payNotifyOp() {
      Model('pay_log')->updatePayLog(array('status'=>'done','notify_time'=>time()),array('order_sn'=>$_GET['order_sn'], 'signature'=>$_GET['signature']));
      echo '<script>window.opener.location.href="index.php?act=order_tp&op=payStatusPage&status=success&order_sn='.$_GET['order_sn'].'&signature='.$_GET['signature'].'";window.close();</script>';
      die();
      }

      public function payFailNotifyOp() {
      Model('pay_log')->updatePayLog(array('status'=>'fail','notify_time'=>time()),array('order_sn'=>$_GET['order_sn'], 'signature'=>$_GET['signature']));
      echo '<script>window.opener.location.href="index.php?act=order_tp&op=payStatusPage&status=fail&order_sn='.$_GET['order_sn'].'&signature='.$_GET['signature'].'";window.close();</script>';
      die();
      } */

    public function payStatusPageOp() {
        $info = Model('order')->getOrderInfo(array('order.order_sn' => $_GET['order_sn']));
        if ($_GET['status'] == 'success' && $info) {
            // sync id card
            //$this->sync_idcardOp($info);
            //通知货站发货
            Model('api/transOrder')->noticeWmsShip($info['shipping_code']);
            Tpl::output('orderInfo', $info);
        }
        $log = Model('pay_log')->getPayLogInfo(array('order_sn' => $_GET['order_sn'], 'signature' => $_GET['signature']));
        Tpl::output('payLog', $log);
        Tpl::output('status', $_GET['status']);
        Tpl::showpage('payStatus', 'null_layout');
    }

    /**
     * 获取订单最新状态
     */
    public function fetchRemoteStatusOp() {
        set_time_limit(0);
        ignore_user_abort(1);
        //20:待发货;24:审核失败;25:审核中;30:发货中;35:待付款;40:已发货;45:已完成;50:导常;60:已取消;
        //$result = Model('package_service')->getWeightTp('709635293135');
        //print_r($result);exit;
//    $status = $this->getPostData('status');
        //$statusValidate = array(30,35,40);
        // 25 -> 审核中, 后去审核通过或者未通过
        // 30 -> 待入仓，获取重量，并转换成待付款35 -> 已发货40
        // 40 -> 获取完成

        global $config;
        $statusValidate = array("'25'", "'30'", "'40'");
        $success = 0;
        $exception = array();
        $orderList = Model('order')->getOrderList('order_state IN (' . implode(',', $statusValidate) . ') AND (order_type = 1 AND track_no != "" OR order_type = 2 AND pre_track_no != "")', '`order`.order_id,u_id,order_type,order_state,tc_code,track_no,pre_track_no,ship_method');
        if ($orderList) {
            foreach ($orderList as $order) {
                $track_no = $order['order_type'] == 1 ? $order['track_no'] : $order['pre_track_no'];
                switch ($order['order_state']) {
                    case '25':
                        $pass = file_get_contents($config['site_site_url'] . '/../data/cache/biaoke.php?act=fetchPassTrackNo&track_no=' . $track_no);
                        $unpass = '';
                        if (!$pass) {
                            $unpass = file_get_contents($config['site_site_url'] . '/../data/cache/biaoke.php?act=fetchUnpassTrackNo&track_no=' . $track_no);
                        }
                        if ($pass) {
                            Model('order')->updateOrder(array('order_state' => 30), array('order_id' => $order['order_id']));
                            $success++;
                        } elseif ($unpass) {
                            Model('order')->updateOrder(array('order_state' => 24), array('order_id' => $order['order_id']));
                            $success++;
                        }
                        break;
                    case '30':
                        $result = Model('package_service')->getWeightTp($track_no);
                        if ($result['status']) {
                            $trans_house = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order['tc_code']));
                            Model('order')->updateOrder(array('order_state' => 35, 'order_weight' => $result['data'], 'shipping_fee' => Model('order')->getShippingFees($result['data'], $order['tc_code'], $order['ship_method'],$order['u_id'])), array('order_id' => $order['order_id']));
                            $success++;
                        } else {
                            $exception[] = array('order_id' => $order['order_id'], 'msg' => $result['msg']);
                        }
                        break;
                    case '40':
                        $result = Model('package_service')->queryOrderStatus($track_no);

                        if ($result->ResponseResult == 'Success') {
                            $flows = $result->Data->TraceFlow;
                            $finish = false;
                            foreach ($flows as $flow) {
                                if ($flow['StatusDesc'] == '签收') {
                                    $finish = true;
                                    break;
                                }
                            }
                            if ($finish) {
                                Model('order')->updateOrder(array('order_state' => 45), array('order_id' => $order['order_id']));
                                $success++;
                            }
                            $data = (array) $flows->TraceStatus;
                            if (!isset($data[0])) {
                                $data[0] = $data;
                            }
                            foreach ($data as $k => $row) {
                                $data[$k] = array('CreatedTime' => preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $row->CreatedTime), 'StatusDesc' => $row->StatusDesc);
                            }
                            Model()->execute("REPLACE INTO wl_order_logistics_log SET order_id = '{$order['order_id']}', `log` = '"
                                    . ch_json_encode($data) . "'");
                        } elseif ($result->ResponseError->LongMessage) {
                            $exception[] = array('order_id' => $order['order_id'], 'msg' => $result->ResponseError->LongMessage);
                        }
                        break;
                }
            }
        }
        die(json_encode(array('status' => 1, 'msg' => '更新成功：<font color=green>' . $success . '</font>个<br>更新失败：<font color=red>' . count($exception) . '</font>个')));
    }

    /**
     * 同步身份证
     */
    public function sync_idcardOp($order_info = null) {
        if (!$order_info) {
            $order_id = $this->getPostData('order_id');
            $condition = array('order.order_id' => $order_id);
            if ($this->admin_info['sp'] == 0) {
                $condition['u_id'] = $this->admin_info['id'];
            }
            $order_info = Model('order')->getOrderInfo($condition);
        }
        $result = Model('package_service')->uploadIDCard($order_info);
        if (func_num_args() > 0) {
            return $result;
        }
        die(json_encode(array('status' => $result['status'], 'msg' => $result['msg'])));
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()) {
        if (empty($data)) {
            return false;
        }
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id' => 's_title', 'Font' => array('FontName' => '宋体', 'Size' => '12', 'Bold' => '1')));
        //header
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '客户订单号');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '寄件人');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '寄件人电话');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '收件人');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '收件人电话');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '申报价值');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '原产地');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '是否代缴关税');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '是否投保');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '物品名称');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '品类');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '品牌');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '数量');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '单价');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '单位');
        //data
        $model = Model();
        $model_cat = Model('category');
        $model_unit = Model('measure');

        $i = 1;
        foreach ((array) $data as $k => $v) {
            $list = $model->table('order_goods')->where(array('order_id' => $v['order_id']))->select();
            if ($list) {
                foreach ($list as $k1 => $row) {
                    $excel_data[$i][] = array('data' => replachString($v['customer_code']));
                    $excel_data[$i][] = array('data' => replachString($v['sender']));
                    $excel_data[$i][] = array('data' => replachString($v['sender_phone']));
                    $excel_data[$i][] = array('data' => replachString($v['reciver_name']));
                    $excel_data[$i][] = array('data' => replachString($v['reciver_phone']));
                    $excel_data[$i][] = array('data' => replachString($v['order_amount']));
                    $excel_data[$i][] = array('data' => replachString($v['origin']));
                    $excel_data[$i][] = array('data' => $v['is_tariff']);
                    $excel_data[$i][] = array('data' => $v['is_cover']);

                    $cate = $model_cat->getCategoryInfo(array('cat_id' => $row['cat_id']));
                    $unit = $model_unit->getMeasureInfo(array('id' => $row['goods_unit']));

                    $excel_data[$i][] = array('data' => $cate ? replachString($cate['cat_name']) : '');
                    $excel_data[$i][] = array('data' => replachString($row['cat_id']));
                    $excel_data[$i][] = array('data' => replachString($row['bland']));
                    $excel_data[$i][] = array('data' => $row['goods_num']);
                    $excel_data[$i][] = array('data' => $row['goods_price']);
                    $excel_data[$i][] = array('data' => $unit ? replachString($unit['measure_name_cn']) : '');
                    $i++;
                }
            }
        }
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('转运运单', CHARSET));
        $excel_obj->generateXML($excel_obj->charset('转运运单', CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }

    // 计算增值服务费
    public function countExtraServiceFee($order) {
        $items = require BASE_PATH . '/include/extra_service_fee.php';
        $th = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order['tc_code']));
        $amount = 0.00;
        $counted = array();
        foreach ($items as $k => $v) {
            list($field, $val) = explode(':', $k);
            if (!in_array($field, $counted) && !empty($order[$field]) && $order[$field] != 'N') {
                $counted[] = $field;
                $field_data = json_decode($th[$field], TRUE);
                if (is_array($field_data) && isset($field_data[$order[$field]])) {
                    $amount += floatval($field_data[$order[$field]]);
                }
            }
        }

        return $amount;
    }

    // 计算物流费用
//  private function getShippingFee($weight, $trans_house) {
//    $fee = $trans_house['first_weight_fee'];
//    if ($weight > $trans_house['first_weight']) {
//      $weight -= $trans_house['first_weight'];
//      $fee += $trans_house['continue_weight_fee'] * ceil($weight/$trans_house['continue_weight']);
//    }
//
//    return $fee;
//  }

    private function payFromMoney($orderInfo,$payType = 'order_shipping_fee') {
        // 检查金额是否足够
        $money = Model('money')->getMoneyInfo(array('u_id' => $this->admin_info['id']));
        $amount = 0;
        if($payType == 'order_shipping_fee'){ //物流费用合计
            $amount = $orderInfo['shipping_fee'] + $orderInfo['extra_service_fee'];  
        }elseif($payType == 'tax'){  //缴税
            $amount = $orderInfo['tariff_fee'];
        }
        
        if ($money && $money['balance'] >= $amount) {
            $time = time();
            //$amount = $orderInfo['shipping_fee'] + $orderInfo['extra_service_fee'];
            $data = array(
                'order_sn' => $orderInfo['order_sn'],
                'u_id' => $this->admin_info['id'],
                'amount' => $amount,
                'payment' => '99',
                'signature' => '',
                'pay_time' => $time,
                'notify_time' => $time,
                'timeout' => '',
                'status' => 'done',
                'pay_data' => '',
                'pay_for' => $payType
            );
            $data['signature'] = md5(http_build_query($data));
            $balance = $money['balance'] - $amount;

            $flow_id = Model('pay_log')->addPayLog($data);
            Model('money')->updateMoney(array('balance' => $balance), array('u_id' => $this->admin_info['id']));
            // @todo title 与 pay_for 对应
            if($payType == 'order_shipping_fee'){
                Model('money_log')->addMoneyLog(array('u_id' => $this->admin_info['id'], 'title' => '订单物流费用', 'type' => 'out', 'amount' => $amount, 'balance' => $balance, 'add_time' => $time, 'flow_id' => $flow_id));
                Model('order')->updateOrder(array('order_state' => '40', 'pay_time' => $time), array('order_id' => $orderInfo['order_id']));
            }elseif($payType == 'tax'){
                Model('money_log')->addMoneyLog(array('u_id' => $this->admin_info['id'], 'title' => '缴税', 'type' => 'out', 'amount' => $amount, 'balance' => $balance, 'add_time' => $time, 'flow_id' => $flow_id));
                Model('order')->updateOrder(array('pay_tariff_status' => '1', 'pay_tariff_time' => $time), array('order_id' => $orderInfo['order_id']));
            }

            echo '支付成功，正在跳转...';

            echo '<script>window.opener.location.href="index.php?act=order_tp&op=payStatusPage&order_sn=' . $orderInfo['order_sn'] . '&status=success&signature=' . $data['signature'] . '";window.setTimeout(function(){window.close();},10);</script>';
        } else {
            echo '支付失败，正在跳转...';
            echo '<script>window.opener.location.href="index.php?act=order_tp&op=payStatusPage&order_sn=' . $orderInfo['order_sn'] . '&status=fail";window.setTimeout(function(){window.close();},10);</script>';
        }
    }

    private function getUserOtherDataViaPostData() {
        $post_data = $this->getPostArray();
        $user_othter_data = array(
            'first_name' => $post_data['sender_name'],
            'last_name' => '',
            'province' => $this->getAreaIdByName($post_data['provinces'], 1),
            'city' => $this->getAreaIdByName($post_data['citys'], 2),
            'area' => !empty($post_data['areas']) ? $this->getAreaIdByName($post_data['areas'], 3) : '',
            'address' => $post_data['sender_address'],
            'phone' => $post_data['sender_phone'],
            'zipcode' => $post_data['sender_zipcode']
        );

        return $user_othter_data;
    }

}
