<script type="text/javascript" SRC="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/layer.js"></script>
<table cellspacing="0" cellpadding="0" border="0" class="order-table-box" style="width:96%;margin: 5px auto;font-size: 12px">	
    <tbody><tr class="order-th">
            <th>订单报文</th>
            <th>运单报文</th>
            <th>支付报文</th>
            <th>个人申报报文</th>
            <th>状态</th>
        </tr>
        <?php if ($output['info']): ?>
            <tr height="30" class="oddListRow s_jishuhang">
                <td align="center"><?php echo $output['info']['importOrder'] ? $output['info']['importOrder']['st_cn'] : '--' ?></td>
                <td align="center"><?php echo $output['info']['importBill'] ? $output['info']['importBill']['st_cn'] : '--' ?></td>
                <td align="center"><?php echo $output['info']['paymentOrder'] ? $output['info']['paymentOrder']['st_cn'] : '--' ?></td>
                <td align="center"><?php echo $output['info']['personalGoodsDeclar'] ? $output['info']['personalGoodsDeclar']['st_cn'] : '--' ?></td>
                <td align="center"><?php echo $output['order_info']['st_cn'] ?></td>
            </tr>
            <tr height="30" class="oddListRow s_jishuhang">
                <td align="center"><a href="javascript:;" onclick="top.openxml(1,<?php echo $output['info']['importOrder']['id']; ?>)">XML报文</a></td>
                <td align="center"><a href="javascript:;" onclick="top.openxml(2,<?php echo $output['info']['importBill']['id']; ?>)">XML报文</a></td>
                <td align="center"><a href="javascript:;" onclick="top.openxml(3,<?php echo $output['info']['paymentOrder']['id']; ?>)">XML报文</a></td>
                <td align="center"><a href="javascript:;" onclick="top.openxml(4,<?php echo $output['info']['personalGoodsDeclar']['id']; ?>)">XML报文</a></td>
                <td align="center">--</td>
            </tr>
        <?php endif; ?>
        <?php if ($output['order_info']['customs_state'] != '30'): ?>
            <tr height="30" class="oddListRow s_jishuhang">
                <td align="center"><a class="btn" href="javascript:;" onclick="repush(1,<?php echo $output['order_info']['order_id']; ?>)">推送</a></td>
                <td align="center"><a class="btn" href="javascript:;" onclick="repush(2,<?php echo $output['order_info']['order_id']; ?>)">推送</a></td>
                <td align="center"><a class="btn" href="javascript:;" onclick="repush(3,<?php echo $output['order_info']['order_id']; ?>)">推送</a></td>
                <td align="center"><?php if ($output['order_info']['customs_state'] != 30): ?><a class="btn" href="javascript:;" onclick="repush(4,<?php echo $output['order_info']['order_id']; ?>)">推送</a><?php endif; ?></td>
                <td align="center"><?php if ($output['order_info']['customs_state'] != 30): ?><a class="btn" href="javascript:;" onclick="repush(999,<?php echo $output['order_info']['order_id']; ?>)">批量推送</a><?php endif; ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<div style="left: -2px; top: 0px; width: 100%; height: 100%; display: none;" id="loading-mask">
    <p id="loading_mask_loader" class="loader"><img alt="Loading..." src="<?php echo SITE_TEMPLATES_URL; ?>/images/ajax-loader-tr.gif" /><br /> Please wait...</p>
</div>
<script type="text/javascript">
    function repush(act, order_id) {
        $('#loading-mask').show();
        var url = "<?php echo SITE_SITE_URL ?>/index.php?act=pgd&op=heavy";
        $.getJSON(url, {actions: act, order_id: order_id}, function (res) {
            $('#loading-mask').hide();
            if (res.status) {
                layer.alert(res.msg, {icon: 1});
            } else {
                layer.alert(res.msg, {icon: 2});
            }
        });
    }

</script>