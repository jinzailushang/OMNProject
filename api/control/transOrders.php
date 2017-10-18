<?php
/**
 * 转运单
 * @copyright (c) 2016-07-28 14:24:51, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class transOrdersControl extends apiControl {
    
    public function __construct() {
        parent::__construct();
    }
    /**
     * wms审核转运单后调用此接口的方法
     */
    public function changeStatusOp() {
        $pre_track_no = $this->post_data['pre_track_no'];
        $status = $this->post_data['status'];
        $remark = $this->post_data['remark'];
        $update_arr = array();
        //wms审核不通过
        if($status == 10){ 
            $update_arr['order_state'] = 24; //审核失败
            $update_arr['reason'] = $remark;
        }elseif($status == 8){
            $update_arr['order_state'] = 40; //发货
            $update_arr['ship_time'] = time();
        }
        $cond['pre_track_no'] = array('in',  explode(',', $pre_track_no));
        $res_update = Model('order')->updateOrder($update_arr,$cond);
        if($res_update){
            $this->output(1, '同步更新成功！');
        }
        $this->output(0, '同步更新失败！');
    }
    //wms称重后调用此接口的方法
    public function setWeightOp() {
//        $pre_track_no = $this->post_data['pre_track_no'];
//        $weight = $this->post_data['weight'];
//        
//        $model_order = Model('order');
//        $info = $model_order->getOrderInfo(array('pre_track_no'=>$pre_track_no),'u_id,tc_code,ship_method');
//        $ship_fee = $model_order->getShippingFees($weight,$info['tc_code'],$info['ship_method'],$info['u_id]);
//        
//        $update_arr = array();
//        $update_arr['order_state'] = 35;  //待付款
//        $update_arr['order_weight'] = $weight;
//        $update_arr['shipping_fee'] = $ship_fee;
//        $res_update = $model_order->updateOrder($update_arr,array('pre_track_no'=>$pre_track_no));
//        if($res_update){
//            $this->output(1, '同步到威廉成功！');
//        }
//        $this->output(0, '同步到威廉失败！');
        
        
        $pre_track_no = $this->post_data['pre_track_no'];
        $weight = $this->post_data['weight'];
        
        //$user_info = unserialize(decrypt($_SESSION['sys_key'], MD5_KEY));
        $model_order = Model('order');
        //获取订单信息
        $info = $model_order->getOrderInfo(array('pre_track_no'=>$pre_track_no),'u_id,tc_code,ship_method,order_sn,shipping_code,extra_service_fee');
        
        $user_other = Model('user_other')->getUserOtherInfo(array('u_id'=>$info['u_id']),'pay_method');
        $pay_method = 1;
        if($user_other['pay_method']){
            $pay_method = $user_other['pay_method'];
        }
        //计算费用
        $ship_fee = $model_order->getShippingFees($weight,$info['tc_code'],$info['ship_method'],$info['u_id']);
        $ship_fee = $ship_fee + $info['extra_service_fee'];
        
        $update_arr = array(
            'order_state' => 35, //待付款
            'order_weight' => $weight,
            'shipping_fee' => $ship_fee,
            'tracking_number'=>$this->post_data['tracking_number'],
        );  
        switch ($pay_method) {
            case 1:  //现付：必须操作付款后订单才可跳转已发货状态
                $res_update = $model_order->updateOrder($update_arr,array('pre_track_no'=>$pre_track_no));
                break;
            case 2: //充值抵扣：账户余额大于订单金额的情况下，系统自动执行付款操作，订单自动跳转已发货状态
                $money = Model('money')->getMoneyInfo(array('u_id'=>$info['u_id']));
                //如果余额充足，则进行扣款，更新订单为发货状态，并通过api更新仓库的订单状态为付款
                if($money && $money['balance'] >= $ship_fee){
                    //余额支付
                    $res_update  = $this->balance_pay($pre_track_no,$weight,$update_arr,$info,$money['balance']);
                    if($res_update){
                        $this->output(1, '同步到威廉成功！',array('pay_status'=>1));
                    }
                }else{
                    //余额不足，则更新为待付款状态
                    $res_update = $model_order->updateOrder($update_arr,array('pre_track_no'=>$pre_track_no));
                }
                break;
            case 3: //月结：账户余额可为负数，系统自动执行付款操作，订单自动跳转已发货状态
                $money = Model('money')->getMoneyInfo(array('u_id'=>$info['u_id']));
                if($money){
                    $res_update  = $this->balance_pay($pre_track_no,$weight,$update_arr,$info,$money['balance']);
                }else{
                    Model('money')->addMoney(array('u_id'=>$info['u_id']));
                    $res_update  = $this->balance_pay($pre_track_no,$weight,$update_arr,$info,0);
                }
                if($res_update){
                    $this->output(1, '同步到威廉成功！',array('pay_status'=>1));
                }
                break;
            default:
                break;
        }
        if($res_update){
            $this->output(1, '同步到威廉成功！',array('pay_status'=>0));
        }
        $this->output(0, '同步到威廉失败！');
    }
    /**
     * //余额支付
     * @param type $pre_track_no 预留单号
     * @param type $weight 订单重量
     * @param int $update_arr 更新的数据
     * @param type $order_info 订单信息
     * @param type $balance 会员余额
     * @return boolean
     * @throws Exception
     */
    public function balance_pay($pre_track_no,$weight,$update_arr,$order_info,$balance){
        $model_order = Model('order');
        $model_order->beginTransaction();
        try{
            $time = time();
            $ship_fee = $update_arr['shipping_fee'];
            $update_arr['order_state'] = 40; //发货状态
            //1、更新订单为发货状态
            $res_update = $model_order->updateOrder($update_arr,array('pre_track_no'=>$pre_track_no));
            if(!$res_update){
                throw new Exception('更新威廉订单出错！');
            }
            $data = array(
                'order_sn' => $order_info['order_sn'],
                'u_id' => $order_info['u_id'],
                'amount' => $ship_fee,
                'payment' => '99',
                'signature' => '',
                'pay_time' => $time,
                'notify_time' => $time,
                'timeout' => '',
                'status' => 'done',
                'pay_data' => '',
                'pay_for' => 'order_shipping_fee'
            );
            $data['signature'] = md5(http_build_query($data));
            $balance = $balance - $ship_fee;
            //2、记录日志
            $flow_id = Model('pay_log')->addPayLog($data);
            if(!$flow_id){
                throw new Exception('记录付款日志出错！');
            }
            //3、扣除余额
            $update_money = Model('money')->updateMoney(array('balance' => $balance), array('u_id' => $order_info['u_id']));
            if(!$update_money){
                throw new Exception('余额扣除失败！');
            }
            //4、记录余额扣除日志
            $add_log = Model('money_log')->addMoneyLog(array('u_id' => $order_info['u_id'], 'title' => '订单物流费用', 'type' => 'out', 'amount' => $ship_fee, 'balance' => $balance, 'add_time' => $time, 'flow_id' => $flow_id));
            if(!$add_log){
                throw new Exception('记录余额日志失败！');
            }
            //file_put_contents('synn.log', 'here'.PHP_EOL);
            //5、通过api更新仓库的订单状态为付款
            //$ress = Model('api/transOrder')->noticeWmsShip($order_info['shipping_code']);
            //file_put_contents('synn.log', print_r($ress,true).PHP_EOL,FILE_APPEND);
            $model_order->commit();
            //$this->output(1, '同步到威廉成功！');
            return true;
        } catch (Exception $ex) {
            $model_order->rollback();
            //$this->output(0, '同步到威廉失败！');
            return false;
        }
    }
    //获取物流单号
    public function getShipmentCodeOp() {
        $pre_track_no = $this->post_data['pre_track_no'];
        $info = Model('order')->getOrderInfo(array('pre_track_no'=>$pre_track_no),'shipping_code');
        $shipping_code = $info ? $info['shipping_code'] : '';
        if($shipping_code){
            $this->output(1, '查询成功！',array('shipping_code'=>$shipping_code));
        }else{
            $this->output(0, '获取失败！');
        }
    }
}

