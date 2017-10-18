<?php
    require_once '../functions.php';
    require_once '../conf/Config.php';
    require_once '../services/Services.php';
    /**
     * 
     * @author Jupiter
     *
     * 消费接口类:
     * 用于对支付信息进行重组和签名，并将请求发往现在支付
     * 
     */
    class Trade{
        public function main(){
            $req=array();

            $pay_for = $_GET['mhtReserved'];
            
            $req["mhtOrderName"]=$_GET["mhtOrderName"];
            $req["mhtOrderAmt"]=$_GET["mhtOrderAmt"];
            $req["mhtOrderDetail"]=$_GET["mhtOrderDetail"];
            $req["funcode"]=Config::TRADE_FUNCODE;
            $req["appId"]=Config::$appId;//应用ID
            //$req["mhtOrderNo"]=date("YmdHis");
            $req["mhtOrderNo"]=$_GET['mhtOrderNo'];
            $req["mhtOrderType"]=Config::TRADE_TYPE;
            $req["mhtCurrencyType"]=Config::TRADE_CURRENCYTYPE;
            $req["mhtOrderTimeOut"]=Config::$trade_time_out;
            $req["mhtOrderStartTime"]=date("YmdHis");
            $req["notifyUrl"]=!empty($_GET['notifyUrl'])? $_GET['notifyUrl']:Config::$back_notify_url;
            $req["frontNotifyUrl"]=!empty($_GET['frontNotifyUrl'])?$_GET['frontNotifyUrl']:Config::$front_notify_url;
            $req["mhtCharset"]=Config::TRADE_CHARSET;
            $req["deviceType"]=Config::TRADE_DEVICE_TYPE;
            $req["payChannelType"]=$_GET['payChannelType'];
            $req["mhtReserved"]=md5($req["mhtOrderNo"].'###'.$req["mhtOrderStartTime"]);
            $req["mhtSignature"]=Services::buildSignature($req);
            $req["mhtSignType"]=Config::TRADE_SIGN_TYPE;

            // 写入日志
            $req_str = Services::trade($req);
            //header("Location:" . Config::TRADE_URL . "?" . $req_str);
            header('Location:../../../site/index.php?act=order_tp&op=payLog&'.http_build_query($req).'&pay_for='.$pay_for.'&pay_url='.safe_b64encode(Config::TRADE_URL . "?" . $req_str));
        }
    }
    $p=new Trade();
    $p->main();
