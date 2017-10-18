<div class="btn-main" style="height: auto;">
  <input type="hidden" id="tab_id" name="tab_id" value="10"/>
  <label>交易时间</label>
  <input id="start_date" class="txt date search_input" type="text" name="start_date"
         value="<?php echo $_GET['start_date']; ?>">
  <label style="position: relative; top: 6px;">~</label>
  <input id="end_date" class="txt date search_input" type="text" name="end_date"
         value="<?php echo $_GET['end_date']; ?>">

  <?php if (!empty($output['is_super'])) { ?>
    <label>会员名</label>
    <input id="user_name" class="txt date search_input" type="text" name="user_name"
           value="<?php echo $_GET['user_name']; ?>">
  <?php }?>

  <select id="inout" class="txt date search_input" name="inout">
    <option value="">全部</option>
    <option value="in"<?php if ($_GET['inout']=='in')echo ' selected'?>>充值</option>
    <option value="out"<?php if ($_GET['inout']=='out')echo ' selected'?>>支出</option>
    </select>
  
  
  <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
  <div class="navTwo_cent">
    <ul class="navTwo_menu">
      <li><a href="javascript:void(0);" id="" onclick="changeTabs(10, this)" class="choose">交易明细</a></li>
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
        <th scope='col' nowrap="nowrap">交易流水号</th>
        <th scope='col' nowrap="nowrap">交易时间</th>
        <th scope='col' nowrap="nowrap">交易类型</th>
        <th scope='col' nowrap="nowrap">支出金额(元)</th>
        <th scope='col' nowrap="nowrap">收入金额(元)</th>
        <th scope='col' nowrap="nowrap">账户余额(元)</th>
      </tr>
    </table>
  </div>
  <div id="pageSpace"></div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    laydate({elem: '#start_date', issure: false});
    laydate({elem: '#end_date', issure: false});
    initData(1);
  });
  function initData(page) {
    $('#loading-mask').show();
    var url = '<?php echo SITE_SITE_URL ?>/index.php?act=user&op=getTransacteDetail';
    var start_date = getIdValue('start_date'), end_date = getIdValue('end_date'),user_name = getIdValue('user_name');
    var inout = getIdValue('inout');

    $.ajax({
      url: url,
      data: {curpage: page, start_date: start_date, end_date: end_date, inout:inout,user_name:user_name},
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
            html += '<td scope="row" align="center">' + r.log_id + '</td>';
            html += '<td scope="row" align="center">' + date(r.add_time, true) + '</td>';
            html += '<td scope="row" align="center">' + (r.type=='in'&&'充值'||'支出') + '</td>';
            html += '<td scope="row" align="center">' + (r.type=='out'&&'<font color="red">'+(parseFloat(r.amount).toFixed(2))+'</font>'||'--') + '</td>';
            html += '<td scope="row" align="center">' + (r.type=='in'&&'<font color="green">'+(parseFloat(r.amount).toFixed(2))+'</font>'||'--') + '</td>';
            html += '<td scope="row" align="center">' + r.balance + '</td>';
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
