<?php

class cronControl extends SystemControl
{
  public function __construct()
  {
  }

  public function trackOp() {
    set_time_limit(0);
    ignore_user_abort(1);
    $list = Model('order')->getOrderList(array('order_state'=>'40'));
    foreach ($list as $order) {
      $track_no = $order['order_type'] == 1? $order['track_no']:$order['pre_track_no'];
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
        }
        $data = (array)$flows->TraceStatus;
        if (!isset($data[0])) {
          $data[0] = $data;
        }
        foreach ($data as $k=>$row) {
          $data[$k] = array('CreatedTime' => preg_replace('/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})(\.\d{1,3})?/s', '$1 $2', $row->CreatedTime), 'StatusDesc' => $row->StatusDesc);
        }
        Model()->execute("REPLACE INTO wl_order_logistics_log SET order_id = '{$order['order_id']}', `log` = '"
          .ch_json_encode($data) ."'");
      } elseif ($result->ResponseError->LongMessage) {
        $exception[] = array('order_id'=>$order['order_id'], 'msg'=>$result->ResponseError->LongMessage);
      }
    }
    echo 'done.';
  }
}