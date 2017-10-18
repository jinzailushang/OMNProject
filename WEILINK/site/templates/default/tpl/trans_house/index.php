<div class="btn-main" style="height: auto;">
  <input type="hidden" id="tab_id" name="tab_id" value="10"/>
  <label>货站编码</label>
  <input type="text" id="tc_code" class="search_input" value="<?php echo $_GET['tc_code'] ?>"
         onkeydown="search(event);"/>
  <label>货站名称</label>
  <input type="text" id="tc_name" class="search_input" value="<?php echo $_GET['tc_name'] ?>"
         onkeydown="search(event);"/>

  <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>


<div class="center">
  <div class="navTwo_cent">
    <ul class="navTwo_menu">
      <li><a href="javascript:void(0);" id="" onclick="changeTabs(10, this)" class="choose">货站列表</a></li>
    </ul>
  </div>
  <div class="operationsbox">
    <?php if (!empty($output['is_super'])) { ?>
<!--    <input id="create-div" type="button" class="button" value="添加" style="cursor: pointer;">-->
      <a class="btn-enter" href="javascript:;" id="create-div" style="margin-left: 6px;">添加 <i class="ico-warehouse" ></i></a>
    <?php }?>
    <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
  </div>
  <div class="pro-center-box">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
      <tr class="order-th">
        <th scope='col' nowrap="nowrap">货站编码</th>
        <th scope='col' nowrap="nowrap">货站名称</th>
<!--        <th scope='col' nowrap="nowrap">货站类型</th>-->
        <th scope='col' nowrap="nowrap">国家</th>
        <th scope='col' nowrap="nowrap">省\州</th>
        <th scope='col' nowrap="nowrap">城市</th>
<!--        <th scope='col' nowrap="nowrap">地区</th>-->
        <th scope='col' nowrap="nowrap">地址</th>
        <th scope='col' nowrap="nowrap">邮编</th>
        <th scope='col' nowrap="nowrap">电话</th>
        <th scope='col' nowrap="nowrap">收件人</th>
        

        <th scope='col' nowrap="nowrap">操作</th>

      </tr>
    </table>
  </div>
  <div id="pageSpace"></div>
</div>
<script type="text/javascript">
  function initData(page) {
    $('#loading-mask').show();
    var url = '<?php echo SITE_SITE_URL ?>/index.php?act=trans_house&op=get_data';
    var tc_code = getIdValue('tc_code'), tc_name = getIdValue('tc_name');

    $.ajax({
      url: url,
      data: {curpage: page, tc_code: tc_code, tc_name: tc_name},
      type: 'get',
      dataType: 'json',
      success: function (res) {
        $('#loading-mask').hide();
        $('.hover').remove();
        $('.order-table-box tr:gt(0)').remove();
        if (res.status == 1) {
          var r = '';
          var html = '';
          for (var i in res.data) {
            r = res.data[i];
            html += '<tr height="30" class="hover">';
            html += '<td scope="row" align="center">' + r.tc_code + '</td>';
            html += '<td scope="row" align="center">' + r.tc_name + '</td>';
            //html += '<td scope="row" align="center">' + r.tc_type + '</td>';
            html += '<td scope="row" align="center">' + r.country + '</td>';
            html += '<td scope="row" align="center">' + r.province + '</td>';
            html += '<td scope="row" align="center">' + r.city + '</td>';
//            html += '<td scope="row" align="center">' + r.area + '</td>';
            html += '<td scope="row" align="center">' + r.address + '</td>';
            html += '<td scope="row" align="center">' + r.zipcode + '</td>';
            html += '<td scope="row" align="center">' + r.phone + '</td>';
            html += '<td scope="row" align="center">' + r.receiver + '</td>';
            html += '<td scope="row" align="center">';
            html += '<a href="javascript:;" onclick="detail('+r.tid+')">收费标准</a>';
            <?php if (!empty($output['is_super'])){?>
            html += ' | <a href="javascript:;" onclick="del_th('+r.tid+')">删除</a>';
            html += ' | <a href="javascript:;" onclick="edit_th(\''+JSON.stringify(r).split('"').join('__-__')+'\')">编辑</a>';
            
            <?php }?>
            html += '</td>'
            html += '</tr>';
          }
          $('#pageSpace').show().html(res.page);
        } else {
          html = '<tr height="30" class="hover">';
          html += '<td scope="row" align="center" colspan="14">' + res.msg + '</td>';
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

<?php
include __DIR__ .'/form.php';
?>