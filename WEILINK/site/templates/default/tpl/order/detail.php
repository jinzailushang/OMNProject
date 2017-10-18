<div class="infor-table">
  <div class="order-info">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 12px">
      <tr>
        <td width="100%">运单明细</td>
      </tr>
    </table>
  </div>
  <div class="contact-info">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%">订单状态： <span
            class="price"><?php echo $output['info'] ? str_replace(array(20,30,35,40,45), array('待发出','待入仓','待付款','已发货','已完成'), $output['info']['order_state']) : $output['empty_str'] ?></span></td>
        <td width="50%">客户订单号： <span
            class="country"><?php echo $output['info'] ? $output['info']['order_sn'] : $output['empty_str'] ?></span>
        </td>
      </tr>
      <tr>
        <td width="50%">订单类型： <span
            class="price"><?php echo $output['info'] ? $output['info']['order_type'] : $output['empty_str'] ?></span></td>
        <td width="50%">运输方式： <span
            class="country"><?php echo $output['info'] ? $output['info']['ship_method'] : $output['empty_str'] ?></span>
        </td>
      </tr>
      <tr>
        <td width="50%">物流单号： <span
            class="price"><?php echo $output['info'] ? $output['info']['shipping_code'] : $output['empty_str'] ?></span></td>
        <td width="50%">转运仓名称： <span
            class="country"><?php echo $output['info'] ? $output['info']['tc_name'] : $output['empty_str'] ?></span>
        </td>
      </tr>
      <tr>
        <td width="50%">物流公司： <span
            class="price"><?php echo $output['info'] ? $output['info']['company'] : $output['empty_str'] ?></span></td>
        <td width="50%">快递单号： <span
            class="country"><?php echo $output['info'] ? ($output['info']['track_no']? $output['info']['track_no']:($output['info']['pre_track_no']? $output['info']['pre_track_no']: $output['empty_str'])) : $output['empty_str'] ?></span>
        </td>
      </tr>
    </table>
    <div class="line-row"></div>
    <?php if ($output['info']['order_type'] == 2): ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td>已选增值服务类型：
           <?php
           $extra_service_keys = array();
           foreach ($output['extra_service_list'] as $skey=>$sval) {
             list($k,$v) = explode(':', $skey);
             if (!in_array($k,$extra_service_keys)) {
               $extra_service_keys[] = $k;
             }
           }
           $index =  0;
           foreach ($extra_service_keys as $loop_index=>$key) {
             if ($output['extra_service_list'][$key.':'.$output['info'][$key]]['text']) {
               echo ($index? ',':'').$output['extra_service_list'][$key.':'.$output['info'][$key]]['text'];
               $index++;
             }
           }
           if (!$index) {
             echo '无';
           }
           ?>
         </td>
       </tr>
        <tr>
          <td>
            备注：<?php echo $output['info']['remark']? $output['info']['remark']: '-'?>
          </td>
        </tr>
      </table>
      <div class="line-row"></div>
    <?php endif; ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="7%">收货人： <span
            class="r-name"><?php echo $output['info'] ? $output['info']['reciver_name'] : $output['empty_str'] ?></span>
        </td>
        <td width="7%">收货人电话： <span
            class="r-tel"><?php echo $output['info'] ? $output['info']['reciver_phone'] : $output['empty_str'] ?></span>
        </td>
      </tr>
      <tr>
        <td width="7%">收货人邮编： <span
            class="r-code"><?php echo $output['info'] ? $output['info']['reciver_zipcode'] : $output['empty_str'] ?></span>
        </td>
        <td width="7%">
          身份证号：<span><?php echo $output['info']['identity_code']?></span>
        </td>
      </tr>
      <tr>
        <td colspan="2">收货人地址： <span
            class="r-add"><?php echo $output['info'] ? $output['info']['reciver_state'] . $output['info']['reciver_city'] . $output['info']['reciver_address'] : $output['empty_str'] ?></span>
        </td>
      </tr>
      <tr>
        <td width="7%">
          身份证正面照: <?php echo $output['info'] && $output['info']['id_card_front']?
          "<img src='{$output['info']['id_card_front']}' style='max-width:120px;max-height:90px' onerror=this.src='/site/templates/default/images/no-picture100.png'
          >":'-'?>
        </td>
        <td width="7%">
          身份证背面照:<?php echo $output['info'] && $output['info']['id_card_back']?
          "<img src='{$output['info']['id_card_back']}' style='max-width:120px;max-height:90px' onerror=this.src='/site/templates/default/images/no-picture100.png'>":'-'?>
        </td>
      </tr>
      <tr>
        <td width="7%"><span></span></td>
      </tr>
      <tr>
        <td width="7%"><span></span></td>
      </tr>
    </table>
    
    <div class="line-row"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2">
          <?php if ($output['info'] && $output['info']['add_time']) {?>
          <p>订单创建时间：<?php echo date('Y-m-d H:i', $output['info']['add_time'])?></p>
          <?php }?>
          <?php if ($output['info'] && $output['info']['inner_time']) {?>
          <p>订单入仓时间：<?php echo date('Y-m-d H:i', $output['info']['inner_time'])?></p>
          <?php }?>
          <?php if ($output['info'] && $output['info']['pay_time']) {?>
          <p>订单付款时间：<?php echo date('Y-m-d H:i', $output['info']['pay_time'])?></p>
          <?php }?>
          <?php if ($output['info'] && $output['info']['finish_time']) {?>
          <p>订单完成时间：<?php echo date('Y-m-d H:i', $output['info']['finish_time'])?></p>
          <?php }?>
        </td>
      </tr>
    </table>
  </div>
</div>
<div class="infor-table commodity">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th class="align-center">增值服务费</th>
      <th class="align-center">包裹重量</th>
      <th class="align-center">物流费</th>
      <th class="align-center">合计物流费</th>
      <th class="align-center">缴税金额</th>
    </tr>
        <tr class="table-cell">
          <td>￥<?php echo $output['info']['extra_service_fee'] ?></td>
          <td><?php echo $output['info']['order_weight'] ?>KG</td>
          <td>￥<?php echo $output['info']['shipping_fee'] ?></td>
          <td>￥<?php echo sprintf('%.2f',$output['info']['extra_service_fee'] + $output['info']['shipping_fee']) ?></td>
          <td>￥<?php echo $output['info']['tariff_fee'] ?></td>
        </tr>
  </table>
</div>
<div class="infor-table commodity">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th width="22%">品类</th>
      <th width="19%">商品名称</th>
      <th width="16%" class="align-center">品牌</th>
      <th width="14%" class="align-center">商品数量</th>
      <th width="14%" class="align-center">计量单位</th>
      <th width="14%" class="align-center">商品单价(<?php echo $output['detail'][0]['currency'] ?>)</th>
    </tr>
    <?php if ($output['detail']): ?>
      <?php foreach ($output['detail'] as $k => $row): ?>
        <tr class="table-cell">
          <td><?php echo $row['cat_name'] ?></td>
          <td><?php echo $row['goods_name'] ?></td>
          <td><?php echo $row['bland'] ?></td>
          <td><?php echo $row['goods_num'] ?></td>
          <td><?php echo $row['goods_unit'] ?></td>
          <td>￥<?php echo $row['goods_price'] ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>
</div>


