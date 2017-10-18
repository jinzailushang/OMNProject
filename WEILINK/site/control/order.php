<?php

/**
 * 订单管理
 * @copyright (c) 2016-05-23, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class orderControl extends SystemControl {

    const EXPORT_SIZE = 1000;
    const EMPTY_STR = '--';
    public $order_types = array('1'=>'直邮','转运','备货');

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/order');
    }

    public function indexOp() {
        //省份
        $pro_list = Model('area')->getAreaList(array('area_parent_id' => 0));
        //分类
        $cate_list = Model('category')->getCategoryList();
        //单位
        $unit_list = Model('measure')->getMeasureList();
        Tpl::output('pro_list', $pro_list);
        Tpl::output('cate_list', $cate_list);
        Tpl::output('unit_list', $unit_list);
        Tpl::output('position', '订单管理');
        Tpl::showpage('index', 'index_layout');
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
                $goods = Model()->table('order_goods')->where(array('order_id' => $v['order_id']))->field('group_concat(goods_name) as name')->find();
                $v['gname'] = $goods['name'];
                $v['gname_s'] = str_cut($goods['name'], 16) . '...';
                $v['shipping_code'] = $v['shipping_code'] ? $v['shipping_code'] : self::EMPTY_STR;
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
        $condition['order_state'] = $_GET['status'];
        if (trim($_GET['customer_code'])) {
            $condition['customer_code'] = $_GET['customer_code'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['end_date']);
        $start_unixtime = $if_start_time ? $_GET['start_date'] : null;
        $end_unixtime = $if_end_time ? $_GET['end_date'] : null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time', array($start_unixtime, $end_unixtime));
        }
        if ($this->admin_info['sp'] == 0) {
            $condition['u_id'] = $this->admin_info['id'];
        }
        return $condition;
    }

    /**
     * 详情显示页面
     * @return tpl
     */
    public function detailOp() {
        $order_id = $this->getGetData('order_id', 0);
        if (empty($order_id)) {
            return;
        }
        //获取订单信息
        $condition = array('order.order_id' => $order_id);
        if ($this->admin_info['sp'] == 0) {
            $condition['u_id'] = $this->admin_info['id'];
        }
        $order_info = Model('order')->getOrderInfo($condition);

        $tc = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order_info['tc_code']));
        $order_info['tc_name'] = $tc['tc_name'];
        $order_info['order_type'] = $this->order_types[$order_info['order_type']];
        $channel = Model('trans_house_channel')->getTransHouseChannelInfo(array('house_id'=>$tc['tid'],'channel_code'=>$order_info['ship_method']),'channel_name');
        $order_info['ship_method'] = $channel ? $channel['channel_name'] : $order_info['ship_method'];
        //获取订单明细
        $detail = Model('order')->getOrderGoodsList(array('order_id' => $order_id));
        if ($detail) {
            $model_cat = Model('category');
            $model_unit = Model('measure');
            $trans_house = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order_info['tc_code']));
            foreach ($detail as &$v) {
                $cate = $model_cat->getCategoryInfo(array('cat_id' => $v['cat_id']));
                $unit = $model_unit->getMeasureInfo(array('id' => $v['goods_unit']));
                $v['cat_name'] = $cate ? $cate['cat_name'] : self::EMPTY_STR;
                $v['goods_unit'] = $unit ? $unit['measure_name_cn'] : self::EMPTY_STR;
                $v['currency'] = $trans_house ? $trans_house['currency'] : '-';
            }
        }

        Tpl::output('info', $order_info);
        Tpl::output('detail', $detail);
        Tpl::output('empty_str', self::EMPTY_STR);
        Tpl::output('extra_service_list', require BASE_PATH . '/include/extra_service_fee.php');
        Tpl::showpage('detail', 'null_layout');
    }

    /**
     * 保存订单
     */
    public function save_orderOp() {
        $post_data = $this->getPostArray();
        print_r($post_data);
        die;
        $type = 'dm';
        $order = array();
        $order['order_sn'] = '';
        $order['customer_code'] = $post_data['customer_code'];
        //1-直邮单 2-转运单
        $order['order_type'] = 1;
        $order['u_id'] = $this->admin_info['id'];
        $order['order_weight'] = $post_data['order_weight'];
        $order['order_amount'] = $post_data['order_amount'];
        $order['origin'] = $post_data['origin'];
        $order['is_tariff'] = $post_data['is_tariff'];
        $order['is_cover'] = $post_data['is_cover'];
        $order['add_time'] = time();
        //如果是转运单
        if ($type == 'tp') {
            $order['pre_track_no'] = $post_data['pre_track_no'];
            $order['company'] = $post_data['company'];
            $order['is_box_ch'] = $post_data['is_box_ch'];
            $order['is_auto_ch'] = $post_data['is_auto_ch'];
            $order['general_force'] = $post_data['general_force'];
            $order['special_force'] = $post_data['special_force'];
            $order['invoice_out'] = $post_data['invoice_out'];
            $order['open_box'] = $post_data['open_box'];
            $order['remark'] = $post_data['remark'];
        }

        //订单地址信息
        $data_address = array();
        $data_address['reciver_name'] = $post_data['reciver_name'];
        $data_address['reciver_state'] = $post_data['provincer'];
        $data_address['reciver_city'] = $post_data['cityr'];
        $data_address['reciver_area'] = $post_data['arear'];
        $data_address['reciver_address'] = $post_data['reciver_address'];
        $data_address['reciver_phone'] = $post_data['reciver_phone'];
        $data_address['reciver_zipcode'] = $post_data['reciver_zipcode'];
        $data_address['has_identity'] = $post_data['has_identity'];
        $data_address['identity_code'] = $post_data['identity_code'];

        $data_address['sender'] = $post_data['sender'];
        $data_address['sender_phone'] = $post_data['sender_phone'];
        $data_address['sender_zipcode'] = $post_data['sender_zipcode'];
        $data_address['sender_address'] = $post_data['sender_address'];
        if ($type == 'dm') {
            $data_address['sender_province'] = $post_data['provinces'];
            $data_address['sender_city'] = $post_data['citys'];
            $data_address['sender_area'] = $post_data['areas'];
        }
        $order['order_address'] = $data_address;

        //收件人信息
        $data_consignee = array();
        $data_consignee['name'] = $post_data['reciver_name'];
        $data_consignee['u_id'] = $this->admin_info['id'];
        $data_consignee['province'] = $post_data['province'];
        $data_consignee['city'] = $post_data['city'];
        $data_consignee['address'] = $post_data['reciver_address'];
        $data_consignee['zipcode'] = $post_data['reciver_zipcode'];
        $data_consignee['phone'] = $post_data['reciver_phone'];
        $order['consignee'] = $data_consignee;

        $cate = Model('category')->getCategoryInfo(array('cat_name' => $post_data['goods'][0]));
        $unit = Model('measure')->getMeasureInfo(array('measure_name_cn' => $post_data['goods'][4]));
        $order['order_goods'][] = array(
            'cat_id' => $cate ? $cate['cat_id'] : 0,
            'goods_name' => $post_data['goods'][1],
            'bland' => $post_data['goods'][2],
            'goods_num' => $post_data['goods'][3],
            'goods_unit' => $unit ? $unit['id'] : 0,
            'goods_price' => $post_data['goods'][5],
        );
        $data[0] = $order;
        $res = Model('order')->saveOrder($data, $this->admin_info['id']);
        if ($res['snum'] == 1) {
            die(json_encode(array('status' => 1, 'msg' => '添加成功！')));
        }
        die(json_encode(array('status' => 0, 'msg' => '添加失败！')));
    }

    public function print_dmOp() {
        set_time_limit(0);
        //header("Content-type: text/html; charset=utf-8");
        $order_id = $this->getGetData('order_id');
        $condition = array('order.order_id' => $order_id);
        if ($this->admin_info['sp'] == 0) {
            $condition['u_id'] = $this->admin_info['id'];
        }
        $order_info = Model('order')->getOrderInfo($condition);
        if ($order_info['order_state'] > 20) {
            echo "<img src=" . substr(SITE_SITE_URL, 0, -4) . $order_info['barcode_img'] . " />";
            exit;
        }
        $trans_house = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order_info['tc_code']));
        $order_info['currency'] = $trans_house['currency'];
        $order_info['channel'] = $trans_house['channel'];
        $result = Model('package_service')->createdAndPrintOrderDm($order_info);
        if ($result['status']) {
            echo $result['img'];
            exit;
        } else {
            echo $result['msg'];
        }
    }

    public function print_tpOp() {
        $order_id = $this->getPostData('order_id');
        $condition = array('order.order_id' => $order_id);
        if ($this->admin_info['sp'] == 0) {
            $condition['u_id'] = $this->admin_info['id'];
        }
        $order_info = Model('order')->getOrderInfo($condition);
        
        if ($order_info['order_state'] == 20 || $order_info['order_state'] == 24) {
            $house = Model('trans_house')->getTransHouseInfo(array('tc_code'=>$order_info['tc_code']),'tc_type');
            $model_ship = Model('shipment_code');
            //查找一个没有使用过的物流单号赋予shipping_code
            $ship = $model_ship->getShipmentCodeInfo(array('flag' => 0));
            if ($ship) {
                $code = $ship['scode'];
            } else {
                //如果物流单号已用完，则生成一个
                $code = $model_ship->buildCode(1);
            } 
            //同步到纵腾
            if($house['tc_type'] == 'zt'){
                $trans_house = Model('trans_house')->getTransHouseInfo(array('tc_code' => $order_info['tc_code']));
                $order_info['currency'] = $trans_house['currency'];
                $order_info['channel'] = $trans_house['channel'];
                $result = Model('package_service')->createdAndPrintOrderTp($order_info, $this->admin_info['id']);
                
                if ($result->ResponseResult == 'Success') {
                    $update_arr = array(
                        'order_state' => 25,  //审核中
                        'shipping_code' => $code
                    );
                    Model('order')->updateOrder($update_arr, array('order_id' => $order_id));
                    $model_ship->updateShipmentCode(array('flag' => '1', 'use_time' => time()), array('scode' => $code));
                    // 同步订单
                    // $idResult = $this->sync_idcardOp($order_info);
                    die(json_encode(array('status' => 1, 'msg' => '运单同步成功！')));
                }
                die(json_encode(array('status' => 0, 'msg' => $result->ResponseError->ShortMessage)));
            }elseif($house['tc_type'] == 'wms'){  //同步到wms
                $order_info['shipping_code'] = $code;
                $result = Model('api/transOrder')->createWmsOrder($order_info);
                if($result['status'] == 1){
                    $update_arr = array(
                        'order_state' => 30,   //待入仓
                        'shipping_code' => $code
                    );
                    Model('order')->updateOrder($update_arr, array('order_id' => $order_id));
                    $model_ship->updateShipmentCode(array('flag' => '1', 'use_time' => time()), array('scode' => $code));
                    die(json_encode(array('status' =>$result['status'], 'msg' => $result['msg'])));
                }else{
                    $str = '';
                    if($result['data']){
                        $str = implode('<br>', $result['data']);
                    }
                    die(json_encode(array('status' =>$result['status'], 'msg' => $result['msg'].$str)));
                }
                die(json_encode(array('status' =>$result['status'], 'msg' => $result['msg'], 'data' => $result['data'])));
            }
        }
        die(json_encode(array('status' => 0, 'msg' => '此为已同步运单！')));
    }

    /**
     * 查看物流轨迹
     */
    public function trackOp() {
        die();
        $order_id = $this->getGetData('order_id');
        if (!$order_id) {
            die('无效的订单ID');
        }
        $order_info = Model('order')->getOrderInfo(array('order.order_id' => $order_id, 'u_id' => $this->admin_info['id']), 'tracking_number,track_no, pre_track_no');
        if (!$order_info) {
            die('订单不存在');
        }
        $log = Model('order_logistics_log')->getOrderLogisticsLogInfo(array('order_id' => $order_id));
        if ($log) {
            $data = json_decode($log['log']);
            Tpl::output('data_list', $data);
            Tpl::showpage('track', 'null_layout');
            die();
        }

        if ($order_info) {
            $result = Model('transport')->queryTraceStatusFlow($order_id);
            if ($result['status'] == '1') {
                $data = $result['data'];
                $db_data = array();
                foreach ($data as $k => $row) {
                    $db_data[$k] = array('CreatedTime' => preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $row['CreatedTime']), 'StatusDesc' => $row['StatusDesc']);
                }
                Model()->execute("REPLACE INTO wl_order_logistics_log SET order_id = '{$order_id}', `log` = '"
                        . ch_json_encode($db_data) . "'");
                
                Tpl::output('data_list', json_decode($data));
            } else {
                Tpl::output('data_list', '');
            }
            Tpl::showpage('track', 'null_layout');
        }
    }

    /**
     * 删除订单
     */
    public function dropOp() {
        $order_id = $this->getGetData('order_id', 0);
        if ($order_id) {
            $res = Model('order')->delOrder($order_id, $this->admin_info);
            if ($res) {
                die(json_encode(array('status' => 1, 'msg' => '操作成功！')));
            }
        }
        die(json_encode(array('status' => 0, 'msg' => '操作失败！')));
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
     * 订单导入页面
     */
    public function importOp() {
        $title = $_GET['type'] == 'dm' ? '直邮单导入' : '转运单导入';
        Tpl::output('position', $title);
        Tpl::showpage('import', 'index_layout');
    }

    /**
     * 订单导入模板
     */
    public function upload_egOp() {
        $type = $this->getGetData('type', 'dm');
        $file_name = "order_eg_" . $type . ".xlsx";
        $file_dir = BASE_ROOT_PATH . DS . XLSX_TPL . DS;

        if (!file_exists($file_dir . $file_name)) {
            showDialog("找不到指定文件", '', 'error', '');
        } else {
            $file = fopen($file_dir . $file_name, "r");
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($file_dir . $file_name));
            Header("Content-Disposition: attachment; filename=" . $file_name);
            echo fread($file, filesize($file_dir . $file_name));
            fclose($file);
            exit();
        }
    }

    /**
     * 订单导入操作
     */
    public function order_importOp() {
        set_time_limit(0);
        if (chksubmit()) {
            $file_name = $_POST['file_xls'];
            if (!empty($_FILES['file_xls']['name']) && 0 == $_FILES['file_xls']['error']) {
                $fileName = date('YmdHis') . rand(1, 3009) . ".xls";
                $filePath = BASE_ROOT_PATH . DS . XLSX_TEMP . DS . $fileName;
                if (!file_exists(BASE_ROOT_PATH . DS . XLSX_TEMP)) {
                    if (!mkdir(BASE_ROOT_PATH . DS . XLSX_TEMP, 0777, true)) {
                        $res = json_encode(array('status' => 0, 'msg' => '创建目录失败！'));
                        die("<script>parent.callback(" . $res . ")</script>");
                    }
                }
                if (!move_uploaded_file($_FILES['file_xls']['tmp_name'], $filePath)) {
                    $is_moved = 0;
                } else {
                    $is_moved = 1;
                }
                if (!$is_moved && !copy($_FILES['file_xls']['tmp_name'], $filePath)) {
                    $res = json_encode(array('status' => 0, 'msg' => '导入失败！'));
                    die("<script>parent.callback(" . $res . ")</script>");
                }
                $model_order = Model('order');
                //读取excel数据
                $type = $this->getPostData('type', '');
                $order_res = $model_order->_getOrderHandle($filePath, $type, $this->admin_info['id']);


                if (empty($order_res['status'])) {
                    $res = json_encode(array('status' => 0, 'msg' => $order_res['msg']));
                    die("<script>parent.callback(" . $res . ")</script>");
                }

                // 计算增值服务费
                require_once __DIR__ . '/order_tp.php';
                $orderTp = new order_tpControl;
                foreach ($order_res['data'] as $oIndex => $ord) {
                    $shipment_code = $this->autoGetShipmentCode();
                    $order_res['data'][$oIndex]['customer_code'] = $shipment_code;
                    $tc = model('trans_house')->getTransHouseInfo(array('tc_name' => $ord['tc_code']));
                    $order_res['data'][$oIndex]['tc_code'] = $tc['tc_code'];
                    $ord['tc_code'] = $tc['tc_code'];
                    $order_res['data'][$oIndex]['extra_service_fee'] = $orderTp->countExtraServiceFee($ord);
                }

                $result = $model_order->saveOrder($order_res['data'], $this->admin_info['id'], $type);
                $res = json_encode(array('status' => 1, 'snum' => $result['snum'], 'fnum' => $result['fnum'], 'error' => $result['error']));
                die("<script>parent.callback(" . $res . ")</script>");
            } else {
                $res = json_encode(array('status' => 0, 'msg' => '请上传批量xls文件！'));
                die("<script>parent.callback(" . $res . ")</script>");
            }
        }
    }

    /**
     * 上传身份证弹窗
     */
    public function cardOp() {
        $order_id = $this->getGetData('order_id', 0);
        if ($order_id) {
            $info = Model('order')->getOrderInfo(array('order.order_id' => $order_id));
            if (0 === strpos($info['id_card_back'], '../')) {
                $info['id_card_back'] = substr($info['id_card_back'], 2);
                $info['id_card_front'] = substr($info['id_card_front'], 2);
            }
            $info['id_card_front'] = $info['id_card_front'] && file_exists(BASE_ROOT_PATH . $info['id_card_front']) ? substr(SITE_SITE_URL, 0, -5) . $info['id_card_front'] : SITE_SITE_URL . '/templates/default/images/no-picture100.png';
            $info['id_card_back'] = $info['id_card_back'] && file_exists(BASE_ROOT_PATH . $info['id_card_back']) ? substr(SITE_SITE_URL, 0, -5) . $info['id_card_back'] : SITE_SITE_URL . '/templates/default/images/no-picture100.png';
            Tpl::output('order_id', $order_id);
            Tpl::output('info', $info);
            Tpl::showpage('load_card', 'null_layout');
        }
    }

    /**
     * 上传身份证
     */
    public function load_cardOp() {
        $id_front_file = $id_back_file = '';
        $bflag = $fflag = false;
        if ($_FILES['id_card_front']['tmp_name']) {
            $uploadres = upload_file('id_card_front', 'idcard');
            if ($uploadres['status']) {
                $id_front_file = $uploadres['path'];
                $fflag = true;
            } else {
                $res = json_encode(array('status' => 0, 'msg' => '缺少身份证正面' . $uploadres['msg']));
                die("<script>parent.callback(" . $res . ")</script>");
            }
        }
        if ($_FILES['id_card_back']['tmp_name']) {
            $uploadres = upload_file('id_card_back', 'idcard');
            if ($uploadres['status']) {
                $id_back_file = $uploadres['path'];
                $bflag = true;
            } else {
                $res = json_encode(array('status' => 0, 'msg' => '缺少身份证反面' . $uploadres['msg']));
                die("<script>parent.callback(" . $res . ")</script>");
            }
        }
        $order_id = $this->getPostData('order_id');
        $reciver_name = $this->getPostData('reciver_name');
        $update_arr = array();
        if ($id_front_file) {
            $update_arr['id_card_front'] = $id_front_file;
        }
        if ($id_back_file) {
            $update_arr['id_card_back'] = $id_back_file;
        }
        $info = Model('order')->getOrderInfo(array('order.order_id' => $order_id), 'id_card_front,id_card_back');
        //删除旧图片
        if (Model('order')->updateOrderAddress($update_arr, array('order_id' => $order_id))) {
            if ($id_front_file && $info['id_card_front'] && file_exists(BASE_ROOT_PATH . $info['id_card_front'])) {
                @unlink(BASE_ROOT_PATH . $info['id_card_front']);
            }
            if ($id_back_file && $info['id_card_back'] && file_exists(BASE_ROOT_PATH . $info['id_card_back'])) {
                @unlink(BASE_ROOT_PATH . $info['id_card_back']);
            }
        }

        if ($id_front_file && !$id_back_file) {
            $res = json_encode(array('status' => 1, 'path' => substr(SITE_SITE_URL, 0, -5) . $id_front_file));
        } elseif ($id_back_file) {
            $res = json_encode(array('status' => 2, 'path' => substr(SITE_SITE_URL, 0, -5) . $id_back_file));
        } else {
            $res = json_encode(array('status' => 0, 'msg' => '上传失败'));
        }

        die("<script>parent.callback(" . $res . ")</script>");
    }

    /**
     * 更新真实姓名和身份证号
     */
    public function update_idenOp() {
        $order_id = $this->getPostData('order_id');
        $reciver_name = $this->getPostData('reciver_name');
        $identity_code = $this->getPostData('identity_code');
        if (!$this->IDCardValidate($identity_code)) {
            $res = json_encode(array('status' => 0, 'msg' => '修改失败！无效的身份证号码。'));
            die("<script>parent.update_after(" . $res . ")</script>");
        }
        $update_arr = array(
            'reciver_name' => $reciver_name,
            'identity_code' => $identity_code
        );
        if (Model('order')->updateOrderAddress($update_arr, array('order_id' => $order_id))) {
            $res = json_encode(array('status' => 1, 'msg' => '修改成功！'));
            die("<script>parent.update_after(" . $res . ")</script>");
        }
        $res = json_encode(array('status' => 0, 'msg' => '修改失败！'));
        die("<script>parent.update_after(" . $res . ")</script>");
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
        //print_r($excel_data);die;
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('直邮运单', CHARSET));
        $excel_obj->generateXML($excel_obj->charset('直邮运单', CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }

}
