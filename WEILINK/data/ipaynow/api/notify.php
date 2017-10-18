<?php
require_once '../functions.php';
    require_once '../utils/Log.php';
    require_once '../services/Services.php';
    /**
    * @author Jupiter
    *
    * 通知接口
    *
    * 用于被动接收中小开发者支付系统发过来的通知信息，并对通知进行验证签名，
    * 签名验证通过后，商户可对数据进行处理。
    *
    * 通知频率:2min、10min、30min、1h、2h、6h、10h、15h
    * 说明:以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写，并非一定要使用该代码。该代码仅供参考
    */
//    $request=file_get_contents('php://input');
//    Log::outLog("网银通知接口", $request);
//    parse_str($request,$request_form);
$request_form = $_GET;
    if (Services::verifySignature($request_form)){
        $tradeStatus=$request_form['tradeStatus'];
        echo "支付成功，正在跳转...";
        if($tradeStatus!=""&&$tradeStatus=="A001"){
            /**
            * 在这里对数据进行处理
            */
            $logs = db_query("SELECT u_id,pay_for,amount FROM wl_pay_log WHERE  order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}' AND status = 'paying'");
            if ($logs && !empty($logs[0])) {
                if ($logs[0]['pay_for'] == 'order_shipping_fee') {  //支付订单金额
                    db_execute("UPDATE wl_order SET order_state = '40' WHERE order_sn = '{$_GET['mhtOrderNo']}'");
                } elseif($logs[0]['pay_for'] == 'tax'){  //缴税
                    db_execute("UPDATE wl_order SET pay_tariff_status = '1',pay_tariff_time=".time()." WHERE order_sn = '{$_GET['mhtOrderNo']}'");
                }elseif ($logs[0]['pay_for'] == 'recharge') {  //充值
                    $moneys = db_query("SELECT u_id,balance FROM wl_money WHERE u_id = {$logs[0]['u_id']}");
                    if (!$moneys || empty($moneys[0])) {
                        db_execute("INSERT INTO wl_money SET u_id = {$logs[0]['u_id']}, balance = {$logs[0]['amount']}, recharge_total = {$logs[0]['amount']}");
                        db_execute("INSERT INTO wl_money_log SET u_id = {$logs[0]['u_id']}, type = 'in', amount = {$logs[0]['amount']}, balance = {$logs[0]['amount']}, title = '', add_time = ".time());
                    } else {
                        db_execute("UPDATE wl_money SET balance = balance + {$logs[0]['amount']}, recharge_total = recharge_total + {$logs[0]['amount']} WHERE u_id = {$logs[0]['u_id']}");
                        db_execute("INSERT INTO wl_money_log SET u_id = {$logs[0]['u_id']}, type = 'in', amount = {$logs[0]['amount']}, balance = {$logs[0]['amount']} + {$moneys[0]['balance']}, title = '', add_time = ".time());
                    }
                }
            }
            db_execute("UPDATE wl_pay_log SET `status` = 'done', notify_time = ".time()." WHERE order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}'");
            echo '<script>window.opener.location.href="../../../site/index.php?act=order_tp&op=payStatusPage&status=success&order_sn='.$_GET['mhtOrderNo'].'&signature='.$_GET['mhtReserved'].'";window.setTimeout(function(){window.close();},10);</script>';
        } else {
            //支付失败
            db_execute("UPDATE wl_pay_log SET `status` = 'fail', notify_time = ".time()." WHERE order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}'");
            $logs = db_query("SELECT pay_for FROM wl_pay_log WHERE  order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}'");
//            if ($logs && !empty($logs[0]) && $logs[0]['pay_for'] == 'order_shipping_fee') {
            echo '<script>window.opener.location.href="../../../site/index.php?act=order_tp&op=payStatusPage&status=fail&order_sn=' . $_GET['mhtOrderNo'] . '&signature=' . $_GET['mhtReserved'] . '";window.setTimeout(function(){window.close();},10);</script>';
//            }
        }
    }
    //验证签名失败