<link rel="stylesheet" href="templates/default/ionic/css/ionicons.min.css">
<style>
  #pay-status-page{
    padding: 20px;
    font-size: 14px;
    line-height:200%;
  }

  #pay-status-page hr{
    size:1px;
    border-width: 1px 0 0 0;
    border-color: #efefef;
    border-style: solid;
    margin: 14px 0;
  }

  #pay-status-page h2{
    font-size:22px;
    text-align: center;
    line-height:42px;
  }
 #pay-status-page h2 i{
   font-size: 38px;
   position: relative;
   bottom:-6px;
 }
  #pay-status-page h2 i.success{
    color:green;
  }
  #pay-status-page h2 i.fail{
    color:red;
  }

  #pay-status-page dt {
    width: 80px;
    float:left;
  }
  #pay-status-page dd {
    float:left;
  }

  #pay-status-page dl:after {
    clear:both;
    display: block;
    content: '';
  }

  .pay-status-page-buttons {
    margin-top:50px;
    text-align: center;
  }
  .pay-status-page-button {
    /*background-color: #fcc300;*/
    /*color: #fff;*/
    width: 136px;
    height: 36px;
    font-size: 14px;
    font-weight: bold;
    /*border-color: #fcb81c;*/
    display: inline-block;
  }
  .pay-status-page-button+button{
    margin-left:15px;
  }

  #pay-status-page-order-detail {
    background-color: #eee;
    color: #666;
    border-color: #ddd;
  }

  #pay-status-page-back,#pay-status-page-repay {
    background-color: #fcc300;
    color: #fff;
    border-color: #fcb81c;
  }

  .pay-status-page-button:hover {
    opacity: .85;
  }
</style>
<div id="pay-status-page">
<?php
$pay_for = str_replace(array('order_shipping_fee', 'recharge','tax'), array('订单支付', '充值', '缴税'), $output['payLog']['pay_for']);
if ($output['status'] == 'success') {
  ?>
  <h2><i class="ion-checkmark-circled success"></i> <span><?php echo $pay_for?>成功</span></h2>
<?php } elseif ($output['status']=='fail') {
  ?>
  <h2><i class="ion-close-circled fail"></i> <span><?php echo $pay_for?>失败</span></h2>
  <?php }
?>
  <hr>
  <dl>
    <dt>订单号：</dt>
    <dd><?php echo $output['payLog']['order_sn']?></dd>
  </dl>
  <?php if ($output['payLog']){?>
  <dl>
    <dt>支付方式：</dt>
    <dd><?php echo str_replace(array('11','12','13','99'), array('银联支付','支付宝支付','微信支付','余额支付'), $output['payLog']['payment'])?></dd>
  </dl>
  <dl>
    <dt>支付时间：</dt>
    <dd><?php echo date('Y-m-d H:i:s', $output['payLog']['pay_time'])?></dd>
  </dl>
  <?php }?>
  <div class="pay-status-page-buttons">
  <?php
  if (in_array($output['payLog']['pay_for'],array('order_shipping_fee','tax'))) {
    if ($_GET['status'] == 'success') {
      ?>
      <button id="pay-status-page-order-detail" class="button pay-status-page-button">查看订单</button>
      <button id="pay-status-page-back" class="button pay-status-page-button">返回转运服务</button>
    <?php } else { ?>
      <button id="pay-status-page-order-detail" class="button pay-status-page-button">查看订单</button>
      <button id="pay-status-page-repay" class="button pay-status-page-button">继续支付</button>
    <?php }
  } else {
    ?>
    <button id="pay-status-page-repay" class="button pay-status-page-button">继续充值</button>
  <?php }
  ?>
    </div>
  </div>
<script>
  <?php
if (in_array($output['payLog']['pay_for'],array('order_shipping_fee','tax'))) {
  ?>
  $('#pay-status-page-order-detail').bind('click', function() {
    window.parent.current_func = 'detail';
    window.parent.layer.closeAll();
  });
  $('#pay-status-page-back').bind('click', function(){
    window.parent.current_func = 'initData';
    window.parent.layer.closeAll();
  });
  <?php }?>
  $('#pay-status-page-repay').bind('click', function() {
    window.parent.current_func = 'pay';
    window.parent.layer.closeAll();
  });
</script>
