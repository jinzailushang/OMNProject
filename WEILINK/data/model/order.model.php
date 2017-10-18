<?php

/**
 * 订单管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class orderModel extends Model {

    public $table = "";

    public function __construct() {
        parent::__construct('order');
        $this->table = "order";
    }

    /**
     * 获取订单详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getOrderInfo($condition, $field = '*', $order = 'order.order_id desc') {

        return $this->table('order,order_address')
                        ->join('left join')
                        ->on('order.order_id = order_address.order_id')
                        ->field($field)->where($condition)->order($order)->limit(1)->find();
    }

    /**
     * 获取订单列表
     * @copyright 
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderList($condition = array(), $field = '*', $page = 0, $order = 'order.order_id desc', $limit = '') {

        return $this->table('order,order_address')
                        ->join('left join')
                        ->on('order.order_id = order_address.order_id')
                        ->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getOrderCount($condition) {

        return $this->table('order,order_address')
                        ->join('left join')
                        ->on('order.order_id = order_address.order_id')->where($condition)->count();
    }

    /**
     * 检测是否存在
     * @param type $condition
     * @return boolean
     */
    public function is_exist($condition = array()) {
        $num = $this->getTransHouseCount($condition);
        if ($num) {
            return true;
        }
        return false;
    }

    /**
     * 新增
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addOrder($data) {

        return $this->table($this->table)->insert($data);
    }

    /**
     * 插入订单地址信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderAddress($data) {

        return $this->table('order_address')->insert($data);
    }

    /**
     * 更新订单地址
     * @param type $update
     * @param type $condition
     * @return type
     */
    public function updateOrderAddress($update, $condition) {

        return $this->table('order_address')->where($condition)->update($update);
    }

    /**
     * 插入订单产品表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderGoods($data) {

        if (count($data) == 1) {
            return $this->table('order_goods')->insert($data[0]);
        } else {
            return $this->table('order_goods')->insertAll($data);
        }
    }

    /**
     * 删除订单产品列表
     * @param integer $order_id
     * @return integer
     */
    public function deleteOrderGoods($order_id) {
        $this->table('order_goods')->where(array('order_id' => $order_id))->delete();
    }

    /**
     * 获取订单中物品列表
     * @copyright 
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderGoodsList($condition = array(), $field = '*') {

        return $this->table('order_goods')->where($condition)->field($field)->select();
    }

    /**
     * 更新
     * @copyright 
     * @param	array $param 更改信息
     * @param	int $id 
     * @return	array 数组格式的返回结果
     */
    public function updateOrder($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);
    }

    /**
     * 删除订单
     * @param type $order_id
     * @param type $user_info
     * @return boolean
     */
    public function delOrder($order_id, $user_info) {
        $condition = array('order_id' => $order_id);
        $model = Model();
        $model->beginTransaction();
        try {
            if (!$this->table($this->table)->where($condition)->delete()) {
                throw new Exception('删除订单失败');
            }
            if (!$model->table('order_address')->where($condition)->delete()) {
                throw new Exception('删除订单地址失败');
            }
            if (!$model->table('order_goods')->where($condition)->delete()) {
                throw new Exception('删除订单明细失败');
            }

            $data_log = array();
            $data_log['order_id'] = $order_id;
            $data_log['log_role'] = 'member';
            $data_log['log_user'] = $user_info['name'];
            $data_log['log_msg'] = '删除订单';
            $data_log['log_time'] = time();
            $res_log = Model('order_log')->addOrderLog($data_log);
            $model->commit();
            return true;
        } catch (Exception $ex) {
            $model->rollback();
            return false;
        }
    }

    public function checkError($data, $type = 'dm') {
        $error = '';
        if (empty($data['order_address']['reciver_name'])) {
            $error .= '收货人必填；';
        }
        if (empty($data['order_address']['reciver_phone'])) {
            $error .= '收货人电话必填；';
        }
        if (empty($data['order_address']['reciver_state'])) {
            $error .= '收货人省份必填；';
        }
        if (empty($data['order_address']['reciver_city'])) {
            $error .= '收货人市必填；';
        }

        if (empty($data['order_address']['reciver_address'])) {
            $error .= '收货人地址必填；';
        }
        //图片标识转运单
        if ($type == 'tp') {
            if (empty($data['pre_track_no'])) {
                $error .= '预报跟踪号必填；';
            }
            if ($data['pre_track_no']) {
                $condition = array('pre_track_no' => $data['pre_track_no']);
                if ($data['order_id']) {
                    $condition['order.order_id'] = array('neq', $data['order_id']);
                }
                $is_exit = $this->getOrderCount($condition);
                if ($is_exit) {
                    $error .= '预报跟踪号已经存在；';
                }
            }
        }

        return $error;
    }

    /**
     * 批量导入订单入库
     * copyright 2016-05-23
     * @return array(成功数，失败数，失败原因)
     */
    public function saveOrder($data_list, $uid = 0, $type) {
        $snum = $fnum = 0;
        $line = 2;
        $index = 1;
        $err = array();
        $orderIds = array();
        foreach ($data_list as $k => $data) {
            //检测customer_code是否重复
            $condition = array('u_id' => $uid, 'customer_code' => $data['customer_code']);
            $isnew = true;
            if (!empty($data['order_id'])) {
                $isnew = false;
                $condition['order.order_id'] = array('neq', $data['order_id']);
            }
            $is_exit = $this->getOrderCount($condition);
            if ($is_exit) {
                $err[] = '第' . $index . '行：订单数据已存在';
                $index++;
                $fnum++;
                continue;
            }

            $flag = false;
            foreach ($data['order_goods'] as $kg => $row) {
                $error = '';
                if ($kg == 0) {
                    $error = $this->checkError($data, $type);
                }
                if (empty($row['goods_name'])) {
                    $error .= '商品名称必填；';
                }
                if (empty($row['goods_num'])) {
                    $error .= '商品数量必填；';
                }
                if (empty($row['goods_price'])) {
                    $error .= '商品单价必填；';
                }
                if ($error) {
                    $flag = true;
                    $err[] = "第" . $line . "行：" . $error . PHP_EOL;
                }
                $line++;
            }
            if ($flag) {
                $fnum++;
                $index++;
                continue;
                ;
            }
            $order_array = $data;
            unset($order_array['order_address'], $order_array['order_goods'], $order_array['consignee']);
            $model_order = Model('order');
            $model_order->beginTransaction();
            //插入order
            if ($isnew) {
                $order_id = $model_order->addOrder($order_array);
            } else {
                $order_id = $data['order_id'];
                $model_order->updateOrder($order_array, array('u_id' => $uid, 'order_id' => $order_id));
            }
            if (!$order_id) {
                $err[] = '第' . index . '行插入订单失败';
                $fnum++;
                $model_order->rollback();
                $index++;
                continue;
            }
            //插入order_address
            $order_address = $data['order_address'];
            $order_address['order_id'] = $order_id;
            if (empty($order_address['consignee_id'])) {
                $order_address['consignee_id'] = '';
            }
            if ($isnew) {
                $return_address = $model_order->addOrderAddress($order_address);
            } else {
                $return_address = !!$model_order->updateOrderAddress($order_address, array('order_id' => $order_id));
            }
            if (!$return_address) {
                $fnum++;
                $err[] = '第' . $index . '行添加订单地址失败';
                $model_order->rollback();
                $index++;
                continue;
            }

            //插入order_goods
            $order_goods = $data['order_goods'];
            foreach ($order_goods as $k1 => $v1) {
                $order_goods[$k1]['order_id'] = $order_id;
            }
            if (!$isnew) {
                $model_order->deleteOrderGoods($order_id);
            }
            $return_goods = $model_order->addOrderGoods($order_goods);
            if (!$return_goods) {
                $err[] = '第' . $index . '行添加订单商品失败';
                $fnum++;
                $model_order->rollback();
                $index++;
                continue;
            }
            //插入consignee
            //@todo 是否需要根据 consignee id 来更新
            $data_consignee = $data['consignee'];
            $c_consignee = Model('consignee')->getConsigneeCount(array('name' => $data_consignee['name'], 'phone' => $data_consignee['phone']));
            if ($c_consignee) {
                $update_consignee = $data_consignee;
                unset($update_consignee['name'], $update_consignee['phone']);
                $return_consignee = Model('consignee')->updateConsignee($update_consignee, array('name' => $data_consignee['name'], 'phone' => $data_consignee['phone']));
            } else {
                $return_consignee = Model('consignee')->addConsignee($data_consignee);
            }
            if (!$return_consignee) {
                $fnum++;
                $err[] = '第' . $index . '行添加收件人失败';
                $model_order->rollback();
                $index++;
                continue;
            }
            //$user_info = Model('user')->getUserInfo(array('user.u_id'=>$uid),'balance,frozen');
            $order_sn = '1' . sprintf('%09d', (int) $order_id);
            $edit_order['order_sn'] = $order_sn;
            if (!$order_array['customer_code']) {
                $edit_order['customer_code'] = $order_sn;
            }
            $model_order->updateOrder($edit_order, array('order_id' => $order_id));
            /* if($user_info['balance'] > $order_array['order_amount']){
              $update_user = array(
              'balance' => $user_info['balance'] - $order_array['order_amount'],
              'frozen' => $user_info['frozen'] + $order_array['order_amount']
              );
              $res_user = Model('user')->updateUser($update_user,array('u_id'=>$uid));
              if(!$res_user){
              $fnum++;
              $model_order->rollback();
              continue;
              }

              $balance_log = array();
              $balance_log['u_id'] = $uid;
              $balance_log['type'] = 2;
              $balance_log['cash'] = $order_array['order_amount'];
              $balance_log['pay_code'] = mt_rand(100000000, 999999999);
              $balance_log['remark'] = '下单，支付预存款';
              $balance_log['add_time'] = time();
              $res_balance = Model('balance_detail')->addBalanceDetail($balance_log);
              if(!$res_balance){
              $fnum++;
              $model_order->rollback();
              continue;
              }
              }
             */

            $data_log = array();
            $data_log['order_id'] = $order_id;
            $data_log['log_role'] = 'seller';
            $data_log['log_user'] = $uid;
            $data_log['log_msg'] = '创建订单';
            $data_log['log_orderstate'] = 20;
            $res_log = Model('order_log')->addOrderLog($data_log);
            if (!$res_log) {
                $err[] = '第' . $index . '行添加订单日志失败';
                $fnum++;
                $model_order->rollback();
                $index++;
                continue;
            }
            $model_order->commit();
            $snum++;
            $index++;
            $orderIds[] = $order_id;
        }
        return array('snum' => $snum, 'fnum' => $fnum, 'error' => count($err) > 0 ? implode("<br />", $err) : '',
            'orderIds' => $orderIds);
    }

    //excel直邮模板头部
    private function getDmTitle() {
        return array(
            'customer_code' => '订单号',
            'consignee' => '寄件人',
            'consignee_province' => '寄件人省份',
            'consignee_city' => '寄件人城市',
            'consignee_area' => '寄件人区',
            'consignee_phone' => '寄件人电话',
            'consignee_zipcode' => '寄件人邮编',
            'consignee_address' => '寄件人地址',
            'reciver_state' => '收货省',
            'reciver_city' => '收货市',
            'reciver_area' => '收货区/县',
            'reciver_name' => '收货人',
            'reciver_zipcode' => '收货人邮编',
            'reciver_address' => '收货详细地址',
            'reciver_phone' => '收货人电话',
            'has_identity' => '是否代传身份证',
            'identity_code' => '身份证号码',
            'order_weight' => '实际重量(kg)',
            'order_amount' => '申报价格',
            'origin' => '原产地(发件国)',
            'is_tariff' => '是否代缴关税',
            'is_cover' => '是否投保',
            'cat_name' => '品类',
            'goods_name' => '商品名称',
            'bland' => '品牌',
            'goods_num' => '数量',
            'goods_unit' => '单位',
            'goods_price' => '单价',
        );
    }

    //excel转运模板头部
    private function getTpTitle() {
        return array(
            'customer_code' => '客户订单号',
            'tc_code' => '中转仓',
            'ship_method' => '运输方式',
            'pre_track_no' => '预报跟踪号',
            'company' => '承运公司',
            'reciver_state' => '收件省/直辖市',
            'reciver_city' => '收件城市',
            'reciver_area' => '区域',
            'reciver_name' => '收货人',
            'reciver_zipcode' => '收货人邮编',
            'reciver_address' => '收件人地址',
            'reciver_phone' => '收件人电话号码',
            'identity_code' => '身份证编号',
            'order_amount' => '总申报价值',
            'origin' => '原产地（发件国）',
            'box_change:out' => '外箱更换',
            'box_change:auto' => '智能换箱',
            'force_type:base' => '普通加固',
            'force_type:spec' => '特殊加固',
            'invoice_out' => '发票取出',
            'open_box' => '开箱清点',
            'cat_name' => '品类',
            'goods_name' => '商品名称',
            'bland' => '品牌',
            'goods_num' => '商品数量',
            'goods_unit' => '计量单位',
            'goods_price' => '物品单价',
            'remark' => '备注',
        );
    }

    /**
     * 组合返回订单数组（默认）
     * copyright 2015-06-02, coolzbw
     * $type为dm时，是直邮，否则为转运
     * @return boolean
     */
    public function _getOrderHandle($filePath, $type = 'dm', $uid) {
        $importArray = $type == 'dm' ? $this->getDmTitle() : $this->getTpTitle();
        import('libraries.cls_PHPExcel');
        $excel_obj = new phpExcelMod();
        $column = $excel_obj->getHColumn($filePath);
        if ($type == 'dm' && $column != 28) {
            return array('status' => 0, 'msg' => '直邮模板错误！');
        }
        if ($type == 'tp' && $column != 28) {
            return array('status' => 0, 'msg' => '转运模板错误！');
        }

        $xlsArr = $excel_obj->excelToArray($filePath, $importArray, '2');

        $order_list = array();
        $order = array();

        $model_cat = Model('category');
        $model_unit = Model('measure');
        if (is_array($xlsArr)) {
            $i = 0;
            $time = time();
            foreach ($xlsArr as $k => $row) {
                if (!isset($order_list[$row['customer_code']])) {
                    //订单信息
                    $order = array();
                    $order['order_sn'] = '';
                    $order['customer_code'] = trim(addslashes($row['customer_code']));
                    //1-直邮单 2-转运单
                    $order['order_type'] = $type == 'dm' ? 1 : 2;
                    $order['u_id'] = $uid;
                    if ($type == 'dm') {
                        $order['order_weight'] = $row['order_weight'];
                    }
                    $order['tc_code'] = $row['tc_code'];
                    $order['ship_method'] = $row['ship_method'];
                    $order['order_amount'] = $row['order_amount'];
                    $order['origin'] = trim(addslashes($row['origin']));
                    $order['is_tariff'] = $row['is_tariff'] == '是' ? 'Y' : 'N';
                    $order['is_cover'] = $row['is_cover'] == '是' ? 'Y' : 'N';
                    $order['add_time'] = time();
                    //如果是转运单
                    if ($type == 'tp') {
                        $order['pre_track_no'] = trim(addslashes($row['pre_track_no']));
                        $order['company'] = trim(addslashes($row['company']));
                        $order['box_change'] = $row['box_change:out'] == '是' ? 'out' : ($row['box_change:auto'] == '是' ? 'auto' : '');
                        $order['force_type'] = $row['force_type:base'] == '是' ? 'base' : ($row['force_type:spec'] == '是' ? 'spec' : '');
                        $order['invoice_out'] = $row['invoice_out'] == '是' ? 'Y' : 'N';
                        $order['open_box'] = $row['open_box'] == '是' ? 'Y' : 'N';
                        $order['remark'] = trim(addslashes($row['remark']));
                    }

                    //订单地址信息
                    $data_address = array();
                    $data_address['reciver_name'] = trim(addslashes($row['reciver_name']));
                    $data_address['reciver_state'] = trim(addslashes($row['reciver_state']));
                    $data_address['reciver_city'] = trim(addslashes($row['reciver_city']));
                    $data_address['reciver_area'] = trim(addslashes($row['reciver_area']));
                    $data_address['reciver_address'] = trim(addslashes($row['reciver_address']));
                    $data_address['reciver_phone'] = trim($row['reciver_phone']);
                    $data_address['reciver_zipcode'] = empty($row['reciver_zipcode']) ? '' : addslashes($row['reciver_zipcode']);
                    $data_address['has_identity'] = addslashes($row['has_identity']);
                    $data_address['identity_code'] = trim(addslashes($row['identity_code']));

                    $data_address['sender'] = trim(addslashes($row['consignee']));
                    $data_address['sender_phone'] = trim(addslashes($row['consignee_phone']));
                    $data_address['sender_zipcode'] = trim(addslashes($row['consignee_zipcode']));
                    $data_address['sender_address'] = trim(addslashes($row['consignee_address']));
                    if ($type == 'dm') {
                        $data_address['sender_province'] = trim(addslashes($row['consignee_province']));
                        $data_address['sender_city'] = trim(addslashes($row['consignee_city']));
                        $data_address['sender_area'] = trim(addslashes($row['consignee_area']));
                    }
                    $order['order_address'] = $data_address;

                    //收件人信息
                    $data_consignee = array();
                    $data_consignee['name'] = trim(addslashes($row['reciver_name']));
                    $data_consignee['u_id'] = $uid;
                    $data_consignee['province'] = trim(addslashes($row['reciver_state']));
                    $data_consignee['city'] = trim(addslashes($row['reciver_city']));
                    $data_consignee['address'] = trim(addslashes($row['reciver_address']));
                    $data_consignee['zipcode'] = addslashes($row['reciver_zipcode']);
                    $data_consignee['phone'] = trim($row['reciver_phone']);
                    $order['consignee'] = $data_consignee;

                    $cate = $model_cat->getCategoryInfo(array('cat_name' => $row['cat_name']));
                    $unit = $model_unit->getMeasureInfo(array('measure_name_cn' => $row['goods_unit']));
                    $order['order_goods'][] = array(
                        'cat_id' => $cate ? $cate['cat_id'] : $row['cat_name'],
                        'goods_name' => $row['goods_name'],
                        'bland' => $row['bland'],
                        'goods_num' => $row['goods_num'],
                        'goods_unit' => $unit ? $unit['id'] : 0,
                        'goods_price' => $row['goods_price'],
                    );
                } else {
                    $cate = $model_cat->getCategoryInfo(array('cat_name' => $row['cat_name']));
                    $unit = $model_unit->getMeasureInfo(array('measure_name_cn' => $row['goods_unit']));
                    //产品信息
                    $order['order_goods'][] = array(
                        'cat_id' => $cate ? $cate['cat_id'] : $row['cat_name'],
                        'goods_name' => $row['goods_name'],
                        'bland' => $row['bland'],
                        'goods_num' => $row['goods_num'],
                        'goods_unit' => $unit ? $unit['id'] : 0,
                        'goods_price' => $row['goods_price'],
                    );
                }
                $order_list[$row['customer_code']] = $order;
                $i++;
            }
        }
        return array('status' => 1, 'data' => $order_list);
    }

    /**
     *  计费：首重费 + 续重费 * (物重-首重)/续重
     * @param type $weight  物重
     * @param type $tc_code 转蕴仓编码
     * @param type $ship_method 运输渠道编码
     * @return type
     */
    public function getShippingFees($weight, $tc_code,$ship_method,$user_id) {
        //$admin_info = unserialize(decrypt($_SESSION['sys_key'], MD5_KEY));
        $user_info = Model('user_other')->getUserOtherInfo(array('u_id'=>$user_id),'house_id');
        
        $trans_h = Model('trans_house')->getTransHouseInfo(array('tc_code' => $tc_code),'tid');
        $trans_hc = Model('trans_house_channel')->getTransHouseChannelInfo(array('house_id' => $trans_h['tid'],'channel_code'=>$ship_method));

        //如果会员绑定了仓库，则用优惠价，否则按正常计算
        $first_weight_fee = $user_info['house_id'] == $trans_h['tid'] ? $trans_hc['first_weight_fee_h'] : $trans_hc['first_weight_fee'];
        $continue_weight_fee = $user_info['house_id'] == $trans_h['tid'] ? $trans_hc['continue_weight_fee_h'] : $trans_hc['continue_weight_fee'];
        
        $fee = $first_weight_fee;
        if ($weight > $trans_hc['first_weight']) {
            $weight -= $trans_hc['first_weight'];
            $fee += $continue_weight_fee * ceil($weight / $trans_hc['continue_weight']);
        }
        return $fee;
    }
}
