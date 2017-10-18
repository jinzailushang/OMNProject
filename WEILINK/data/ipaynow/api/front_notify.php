<?php
require_once '../functions.php';
    require_once '../utils/Log.php';
    require_once '../services/Services.php';
    /**
    * @author Jupiter
    *
    * 前台通知接口
    *
    * 用于被动接收中小开发者支付系统发过来的通知信息，并对通知进行验证签名，
    * 签名验证通过后，商户可对数据进行处理。(交易状态以后台通知为准)
    *
    * 说明:以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写，并非一定要使用该代码。该代码仅供参考
    */
    $response="";
    foreach($_GET as $key=>$value){
    $response.=$key."=".$value."&";
    }
    Log::outLog("网银前台通知接口", $response);
    if (Services::verifySignature($_GET)){
        $tradeStatus=$_GET['tradeStatus'];
        if($tradeStatus!=""&&$tradeStatus=="A001"){
            // funcode=N002&appId=1464869664154114&mhtOrderNo=1000000002&mhtCharset=UTF-8&transStatus=A001&tradeStatus=A001&mhtReserved=--&signType=MD5&signature=fa897451d4cdf031653acb4beced85df
            //支付成功
            /**
             * 在这里对数据进行处理
             */
            $logs = db_query("SELECT u_id,pay_for,amount FROM wl_pay_log WHERE  order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}' AND status = 'paying'");
            if ($logs && !empty($logs[0])) {
                if ($logs[0]['pay_for'] == 'order_shipping_fee') {
                    db_execute("UPDATE wl_order SET order_state = '40' WHERE order_sn = '{$_GET['mhtOrderNo']}'");
                } elseif($logs[0]['pay_for'] == 'tax'){  //缴税
                    db_execute("UPDATE wl_order SET pay_tariff_status = '1',pay_tariff_time=".time()." WHERE order_sn = '{$_GET['mhtOrderNo']}'");
                }elseif ($logs[0]['pay_for'] == 'recharge') {
                    $moneys = db_query("SELECT u_id,balance FROM wl_money WHERE u_id = {$logs[0]['u_id']}");
                    if (!$moneys || empty($moneys[0])) {
                        db_execute("INSERT INTO wl_money SET u_id = {$logs[0]['u_id']}, balance = {$logs[0]['amount']}, recharge_total = {$logs[0]['amount']}");
                    } else {
                        db_execute("UPDATE wl_money SET balance = balance + {$logs[0]['amount']}, recharge_total = recharge_total + {$logs[0]['amount']} WHERE u_id = {$logs[0]['u_id']}");
                    }
                    db_execute("INSERT INTO wl_money_log SET u_id = {$logs[0]['u_id']}, type = 'in', amount = {$logs[0]['amount']}, balance = {$logs[0]['amount']} + {$moneys[0]['balance']}, add_time = ".time());
                }
            }
            db_execute("UPDATE wl_pay_log SET `status` = 'done', notify_time = ".time()." WHERE order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}'");
            echo '<script>window.opener.location.href="../../../site/index.php?act=order_tp&op=payStatusPage&status=success&order_sn='.$_GET['mhtOrderNo'].'&signature='.$_GET['mhtReserved'].'";window.setTimeout(function(){window.close();},10);</script>';
        }else{
            //支付失败
            db_execute("UPDATE wl_pay_log SET `status` = 'fail', notify_time = ".time()." WHERE order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}'");
            $logs = db_query("SELECT pay_for FROM wl_pay_log WHERE  order_sn = '{$_GET['mhtOrderNo']}' AND signature = '{$_GET['mhtReserved']}'");
//            if ($logs && !empty($logs[0]) && $logs[0]['pay_for'] == 'order_shipping_fee') {
            echo '<script>window.opener.location.href="../../../site/index.php?act=order_tp&op=payStatusPage&status=fail&order_sn=' . $_GET['mhtOrderNo'] . '&signature=' . $_GET['mhtReserved'] . '";window.setTimeout(function(){window.close();},10);</script>';
//            }
        }
    }else{
        //验证签名失败
    }