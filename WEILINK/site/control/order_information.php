<?php
/**
 * 订单API
 * @copyright (c) 2016-06-03 14:12:23, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class order_informationControl extends apiControl {


    public function __construct() {
        parent::__construct();
    }
    
    public function indexOp() {
        $post_data = $this->getPostArray();

        if(empty($post_data['shippingCode']) || empty($post_data['sign']) || empty($post_data['qTime']) ){
            $this->output(0,'参数缺失！请检查！');
        }
        $sign = md5(C('api_key').$post_data['shippingCode'].$post_data['qTime']);

        if($post_data['sign'] !=  $sign){
            $this->output(0,'签名错误！');
        }

        $shipping_code = $post_data['shippingCode'];
        $order_info = Model('order')->getOrderInfo(array('shipping_code'=>$shipping_code),'track_no,pre_track_no');
        $track_no = $order_info['track_no'] ? $order_info['track_no'] : $order_info['pre_track_no'];
        if($track_no){
            $result = Model('package_service')->queryOrderStatus($track_no);
            if($result->ResponseResult == 'Success'){
                $data = (array)$result->Data->TraceFlow->TraceStatus;
                if(!isset($data[0])){
                    $data_list[0] = $data;
                }else{
                    $data_list = $data;
                }
                $res_data = array('shipping_code'=>$shipping_code,'tracking'=>$data_list,'status'=>'success','querytime'=>  format_time(time()));
                echo json_encode($res_data);
            }else{
                echo json_encode(array('shipping_code'=>$shipping_code,'msg'=>$result->ResponseError->ShortMessage,'status'=>'fail','querytime'=> format_time(time())));
            }

        }else{
            echo json_encode(array('shipping_code'=>$shipping_code,'msg'=>'不存在的物流单号！','status'=>'fail','querytime'=> format_time(time())));
        }
    }
}
