<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('InOmniWL') or exit('Access Invalid!');

class transOrderModel extends Model {

    public $config = array();

    public function __construct() {
        $this->config = require_once 'wms.config.php';
    }
    private function _call($data) {
        import('libraries.restclient');
        $obj = new restclient;
        $obj->url = $this->config['api_url'];
        $obj->params = $data;
        $obj->post();
        $res = $obj->response;
        return $res;
    }

    //把转运单推入远程货站
    public function createWmsOrder($orderInfo) {
        $extra_server = 0;
        if($orderInfo['box_change'] != '' || $orderInfo['force_type'] != '' || $orderInfo['open_box'] != 'N' || $orderInfo['invoice_out'] != 'N'){
            $extra_server = 1;
        }
        $goods = array();
        $model = Model('order');
        $model_cat = Model('category');
        $model_measure = Model('measure');
        $goods_list = $model->getOrderGoodsList(array('order_id' => $orderInfo['order_id']));
        //货品信息
        if ($goods_list) {
          foreach ($goods_list as $k => $v) {
            $cat_info = $model_cat->getCategoryInfo($v['cat_id']);
            $goods[$k]['cat_name'] = $cat_info['cat_name'];
            $goods[$k]['hs_code'] = $cat_info['tariff_number'];
            $goods[$k]['goods_name'] = $v['goods_name'];
            $goods[$k]['brand'] = $v['bland'];
            $goods[$k]['goods_num'] = $v['goods_num'];
            $goods[$k]['goods_unit'] = $model_measure->getMeasureNameById($v['goods_unit']);
            $goods[$k]['goods_price'] = $v['goods_price'];
          }
        }
      
        $data = array(
            'funcode' => 'transOrders_create', //必填
            'customer_name' => $this->config['customer_name'], //必填
            'sign' => md5($this->config['customer_name'] . $this->config['secret_key'] . time()), //必填
            'qTime' => time(), //必填
            'customer_code' => $orderInfo['customer_code'], //客户单号，客户单号
            'tc_code' => $orderInfo['tc_code'],  //货站编码
            'platform' => $orderInfo['ship_method'],
            'sm_code' => 'WEL',  //物流方式编码
            'pre_track_no' => $orderInfo['pre_track_no'], //预报跟踪号，必填
            'shipping_code' => $orderInfo['shipping_code'],  //物流单号
            'company' => $orderInfo['company'], //盛运公司，必填
            'remark' => $orderInfo['remark'], //备注
            'extra_service' => $extra_server, //是否有增值服务, 默认0
            'box_change' => $orderInfo['box_change'], //外箱更换类型 (out-外箱,auto-智能)
            'force_type' => $orderInfo['force_type'], //加固类型 (base-基础加固，spec-特殊加固)
            'open_box' => $orderInfo['open_box'], //是否开箱清点（Y/N）
            'invoice_out' => $orderInfo['invoice_out'], //是否发票取出（Y/N）
            'reciver_name' => $orderInfo['reciver_name'], //收件人姓名,必填
            'reciver_state' => $orderInfo['reciver_state'], //收件人省，必填
            'reciver_city' => $orderInfo['reciver_city'],//收件人城市，必填
            'reciver_area' => $orderInfo['reciver_area'],//收件人必区，必填
            'reciver_address' => $orderInfo['reciver_address'],//收件人地址（省市区除外），必填
            'reciver_phone' => $orderInfo['reciver_phone'],//收件人电话，必填
            'reciver_zipcode' => $orderInfo['reciver_zipcode'],//邮编
            'sender' => $orderInfo['sender'], //寄件人姓名，必填
            'sender_province' => $orderInfo['sender_province'], //寄件人省，必填
            'sender_city' => $orderInfo['sender_city'], //寄件人城市，必填
            'sender_area' => $orderInfo['sender_area'], //寄件人区，必填
            'sender_address' => $orderInfo['sender_address'], //寄件人地址（省市区除外），必填
            'sender_phone' => $orderInfo['sender_phone'], //必
            'sender_zipcode' => $orderInfo['sender_zipcode'], //寄件人邮编
            'identity_code' => $orderInfo['identity_code'], 
            'id_card_front' => 'http://'.$_SERVER['HTTP_HOST'].  str_replace('../', '/', $orderInfo['id_card_front']),
            'id_card_back' => 'http://'.$_SERVER['HTTP_HOST'].  str_replace('../', '/', $orderInfo['id_card_back']),
            'detail_list' => $goods
        );
        $res = $this->_call($data);
        $res = json_decode($res,true);
        return $res;
    }
    //通知货站发货
    public function noticeWmsShip($shipping_code) {
        $data = array(
            'funcode' => 'transOrders_changeShipping', //必填
            'customer_name' => $this->config['customer_name'], //必填
            'sign' => md5($this->config['customer_name'] . $this->config['secret_key'] . time()), //必填
            'qTime' => time(), //必填
            'shipping_code' => $shipping_code
        );
        //file_put_contents('pay.log', print_r($data,true).PHP_EOL);
        $res = $this->_call($data);
        $res = json_decode($res,true);
        //file_put_contents('pay.log', print_r($res,true).PHP_EOL,FILE_APPEND);
        return $res;
    }

}
