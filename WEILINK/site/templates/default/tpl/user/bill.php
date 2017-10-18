<div class="btn-main" style="height: auto;">
  <input type="hidden" id="tab_id" name="tab_id" value="10"/>
  <label>客户订单号</label>
  <input id="order_sn" class="txt date search_input" type="text" name="order_sn"
         value="<?php echo $_GET['order_sn']; ?>">
  <label>物流单号</label>
  <input id="shipping_code" class="txt date search_input" type="text" name="shipping_code"
         value="<?php echo $_GET['shipping_code']; ?>">
  <label>预留快递单号</label>
  <input id="track_no" class="txt date search_input" type="text" name="track_no"
         value="<?php echo $_GET['track_no']; ?>">
  <label>收件人名称</label>
  <input id="reciver_name" class="txt date search_input" type="text" name="reciver_name"
         value="<?php echo $_GET['reciver_name']; ?>">
  <?php if (!empty($output['is_super'])) { ?>
    <label>会员名</label>
    <input id="user_name" class="txt date search_input" type="text" name="user_name"
           value="<?php echo $_GET['user_name']; ?>">
  <?php }?>

  <label>中转仓名称</label>
  <select id="tc_code" class="txt date search_input" name="tc_code">
    <option value="">全部</option>
    <?php foreach($output['trans_houses'] as $th) {?>
    <option value="<?php echo $th['tc_code']?>"<?php if ($_GET['tc_code']==$th['tc_code'])echo ' selected'?>><?php echo $th['tc_name']?></option>
    <?php }?>
    </select>

  <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
  <div class="navTwo_cent">
    <ul class="navTwo_menu">
      <li><a href="javascript:void(0);" id="" onclick="changeTabs(10, this)" class="choose">账单查询</a></li>
    </ul>
  </div>
  <div class="operationsbox">
    <ul class="operNav" style="padding:3px 10px">
      <li> 账户余额：<font style="font-size: 14px;color: #009F95;font-weight: bold"><?php echo $output['money_info']['balance']? $output['money_info']['balance']: '0.00'?></font>元 </li>
    </ul>
    <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
  </div>
  <div class="pro-center-box">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
      <tr class="order-th">
        <th scope='col' nowrap="nowrap">账单流水号</th>
        <th scope='col' nowrap="nowrap">客户订单号</th>
        <th scope='col' nowrap="nowrap">物流单号</th>
        <th scope='col' nowrap="nowrap">预留快递单号</th>
        <th scope='col' nowrap="nowrap">中转仓名称</th>
        <th scope='col' nowrap="nowrap">申报价值（元）</th>
        <th scope='col' nowrap="nowrap">收件人</th>
        <th scope='col' nowrap="nowrap">包裹重量（KG）</th>
        <th scope='col' nowrap="nowrap">物流费（元）</th>
        <th scope='col' nowrap="nowrap">增值服务费（元）</th>
        <th scope='col' nowrap="nowrap">税费（元）</th>
        <th scope='col' nowrap="nowrap">合计费用（元）</th>
        <th scope='col' nowrap="nowrap">交易时间</th>
      </tr>
    </table>
  </div>
  <div id="pageSpace"></div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    initData(1);
  });
  function initData(page) {
    $('#loading-mask').show();
    var url = '<?php echo SITE_SITE_URL ?>/index.php?act=user&op=getBill';
    var order_sn = getIdValue('order_sn'), shipping_code = getIdValue('shipping_code'), track_no = getIdValue('track_no'), reciver_name = getIdValue('reciver_name'),user_name = getIdValue('user_name');
    var tc_code = getIdValue('tc_code');

    $.ajax({
      url: url,
      data: {
        curpage: page,
        order_sn: order_sn,
        shipping_code: shipping_code,
        track_no:track_no,
        reciver_name: reciver_name,
        tc_code: tc_code,
        user_name: user_name
      },
      type: 'get',
      dataType: 'json',
      success: function (res) {
        $('#loading-mask').hide();
        $('.hover').remove();
        if (res.status == 1) {
          var r = '';
          var html = '';
          for (var i in res.data) {
            r = res.data[i];
            html += '<tr height="30" class="hover">';
            html += '<td scope="row" align="center">' + r.flow_id + '</td>';
            html += '<td scope="row" align="center">' + r.order_sn + '</td>';
            html += '<td scope="row" align="center">' + r.shipping_code + '</td>';
            html += '<td scope="row" align="center">' + (r.pre_track_no|| r.track_no) + '</td>';
            html += '<td scope="row" align="center">' + r.tc_name + '</td>';
            html += '<td scope="row" align="center">' + r.order_amount + '</td>';
            html += '<td scope="row" align="center">' + r.reciver_name + '</td>';
            html += '<td scope="row" align="center">' + r.order_weight + '</td>';
            html += '<td scope="row" align="center">' + r.shipping_fee + '</td>';
            html += '<td scope="row" align="center">' + r.extra_service_fee + '</td>';
            html += '<td scope="row" align="center">' + r.tariff_fee + '</td>';
            html += '<td scope="row" align="center">' + (parseFloat(r.shipping_fee)+parseFloat(r.extra_service_fee)+parseFloat(r.tariff_fee)).toFixed(2).toString() + '</td>';
            html += '<td scope="row" align="center">' + date(r.notify_time, true) + '</td>';
            html += '</tr>';
          }
          $('#pageSpace').show().html(res.page);
        } else {
          html = '<tr height="30" class="hover">';
          html += '<td scope="row" align="center" colspan="20">' + res.msg + '</td>';
          html += '</tr>';
          $('#pageSpace').hide();
        }
        $('#count_data').html(res.count);
        $('.order-table-box').show().append(html);
        initTable();
      }
    });
  }

</script>
