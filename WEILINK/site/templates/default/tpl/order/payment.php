<style>
    #payment-page {
        padding: 20px;
        font-size: 14px;
        line-height:200%;
    }
    .payment-class {
        color: orange;
    }

    .payment-class:before {
        content: '￥';
        margin-right: 3px;
    }

    #payment-page hr{
        size:1px;
        border-width: 1px 0 0 0;
        border-color: #efefef;
        border-style: solid;
        margin: 14px 0;
    }

    #payment-page dt {
        width: 80px;
        float:left;
    }
    #payment-page dd {
        float:left;
    }

    #payment-page dl:after {
        clear:both;
        display: block;
        content: '';
    }

    .payment-name {
        display: inline-block;
        width:110px;
        height:32px;
        border:1px solid #ddd;
        margin-right:10px;
        margin-top:10px;
        text-align: center;
        line-height:32px;
    }

    .payment-name.active{
        border:0;
        background:url(templates/default/images/payment-selected.png);
    }
    .payment-name.disabled {
        opacity: .65;
        cursor: not-allowed;
    }
    .payment-name.disabled:hover {
        color:#444;
    }

    #pay-btn {
        background-color: #fcc300;
        color: #fff;
        width: 136px;
        height: 36px;
        font-size: 14px;
        font-weight: bold;
        border-color: #fcb81c;
        display: block;
        margin: 50px auto 0 auto;
    }

    #pay-btn:hover {
        opacity: .85;
    }

    .remain-amount{
        font-size:12px;
        color:orange;
    }
</style>
<div id="payment-page">
    <?php if ($_GET['pay_for'] == 'order_shipping_fee' || $_GET['pay_for'] == 'tax') { ?>
        <dl>
            <dt>订单号：</dt>
            <dd><?php echo $output['orderInfo']['order_sn'] ?></dd>
        </dl>
        <dl>
            <dt>收货人：</dt>
            <dd><?php echo $output['orderInfo']['reciver_name'] ?></dd>
        </dl>
        <dl>
            <dt>收货地址：</dt>
            <dd><?php echo $output['orderInfo']['reciver_state'] . $output['orderInfo']['reciver_city'] . $output['orderInfo']['reciver_city'] . $output['orderInfo']['reciver_area'] . $output['orderInfo']['reciver_address'] ?></dd>
        </dl>
    <?php } elseif ($_GET['pay_for'] == 'recharge') { ?>
        <dl>
            <dt>充值金额：</dt>
            <dd><input type="text" id="recharge-amount"> 元</dd>
        </dl>
    <?php } ?>
    <hr>
    <h3>支付方式</h3>
    <a class="payment-name disabled" href="#" data-paymenttype="13">微信支付</a>
    <a class="payment-name" href="#" data-paymenttype="12">支付宝支付</a>
    <a class="payment-name disabled" href="#" data-paymenttype="11">银联支付</a>
    <?php if ($_GET['pay_for'] != 'recharge' && $output['money']['balance'] >= $output['orderInfo']['shipping_fee'] + $output['orderInfo']['extra_service_fee']) { ?>
        <br>
        <a class="payment-name" href="#" data-paymenttype="99">余额支付</a> <span class="remain-amount">(余额：￥<?php echo $output['money']['balance'] ?>)</span>
    <?php } ?>
    <hr>
    <h4><?php echo $_GET['pay_for'] == 'recharge' ? '充值' :
            '付款'
    ?>金额：<span class="payment-class">
        <?php 
        $fee = $_GET['pay_for'] == 'order_shipping_fee' ? $output['orderInfo']['shipping_fee'] + $output['orderInfo']['extra_service_fee']  : $output['orderInfo']['tariff_fee'];
        echo sprintf('%.2f', $fee) ;
                ?>
    </span></h4>

    <button id="pay-btn" class="button"><?php echo $_GET['pay_for'] == 'recharge' ? '充值' :
                '付款'
    ?></button>
</div>

<script>
    $('.payment-name').bind('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
            return false;
        }
        $('.payment-name').removeClass('active');
        $(this).addClass('active');
    }).eq(1).trigger('click');

    $('#pay-btn').bind('click', function () {
        if (parseFloat($('.payment-class').text()) < 0.01) {
            alert('无效金额，支付金额不可小于0.01元');
            return false;
        }
        window.open('<?php echo $GLOBALS['config']['site_site_url'] ?>/index.php?act=order_tp&op=gotoPay&pay_for=<?php echo $_GET['pay_for'] ?>&order_id=<?php echo $_GET['order_id'] ?>&recharge_amount=' + parseFloat($('#recharge-amount').val()).toString() + '&paymentType=' + $('.payment-name.active').data('paymenttype'));
//    $(this).prop('disabled', true);
    });
    $('#recharge-amount').bind('change', function () {
        var amount = parseFloat($(this).val());
        $('.payment-class').text((amount > 0 && amount || 0).toFixed(2))
    }).trigger('change').focus();
</script>