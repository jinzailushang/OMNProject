function initData(page) {
  $('#loading-mask').show();
  var url = SITE_SITE_URL + '/index.php?act=order_tp&op=get_data';
  var status = getIdValue('tab_id'), order_sn = getIdValue('search-form-order_sn'), tc_code = getIdValue('search-form-tc_code'), reciver_name = getIdValue('search-form-reciver_name'),
    pre_track_no = getIdValue('search-form-pre_track_no'), shipping_code = getIdValue('search-form-shipping_code'), order_type = getIdValue('search-form-order_type'), ship_method = getIdValue('search-form-ship_method');
  var param = '&type=2&reciver_name=' + reciver_name + '&status=' + status + '&tc_code=' + tc_code + '&order_sn=' + order_sn+'&pre_track_no='+pre_track_no+'&shipping_code='+shipping_code;
  param += '&order_type='+order_type+'&ship_method='+ship_method;
  $('#export').attr('href', SITE_SITE_URL + '/index.php?&act=order_tp&op=export' + param);
  $.ajax({
    url: url,
    data: {
      curpage: page,
      type: 2,
      status: status,
      reciver_name: reciver_name,
      tc_code: tc_code,
      order_sn: order_sn,
      pre_track_no: pre_track_no,
      shipping_code: shipping_code,
      order_type:order_type,
      ship_method:ship_method
    },
    type: 'get',
    dataType: 'json',
    success: function (res) {
      $('#loading-mask').hide();
      $('.hover').remove();
      if (res.status == 1) {
        var r = '';
        var html = '';
        var fields = {
          0: {
            status: '全部',
            fields: 'shipping_code,customer_code,add_time,tc_name,order_type,ship_method,reciver_name,identity_code,order_amount,order_weight,shipping_fee,extra_service_fee,count_fee,tariff_fee,order_state',
            // 物流单号 客户订单号 创建时间 中转仓名称 商品名称 收件人名称 是否上传身份证 申报价值 包裹重量 物流费 增值服务费 物流费用合计 缴税金额 订单状态 操作
            operate: ''
            // @todo 依赖其他状态
          },
          20: {
            status: '待发出',
            fields: 'customer_code,add_time,tc_name,order_type,ship_method,reciver_name,identity_code,order_amount,company,pre_track_no,order_state',
            // 客户订单号 创建时间 中转仓名称 商品名称 收件人名称 是否上传身份证 申报价值 境外快递公司 境外快递单号 订单状态 操作
            operate: '<div class="jt_select_div">' +
            ' <div class="jt_select_show_val">操作</div>' +
            '   <span class="jt_select_icon ico"></span>' +
            '   <div class="jt_select_option_div">' +
            '     <ul class="jt_select_option_ul">' +
            '       <li><a href="javascript:;" onclick="detail(%order_id%)">查看详情</a></li>' +
            '       <li><a href="javascript:;" onclick="edit(%order_id%)">编辑运单</a></li>' +
            '       <li><a href="javascript:;" onclick="sync(%order_id%)">同步运单</a></li>' +
            '       <li><a href="javascript:;" onclick="card(%order_id%)">上传身份证</a></li>' +
            //'       <li><a href="javascript:;" onclick="sync_idcard(%order_id%)">同步身份证</a></li>' +
            '       <li><a href="javascript:;" onclick="drop(this, %order_id%)">删除运单</a></li>' +
            '     </ul>' +
            '   </div>' +
            ' </div>'
          },
          25: {
            status: '审核中',
            fields: 'customer_code,add_time,tc_name,order_type,ship_method,reciver_name,identity_code,order_amount,company,pre_track_no,order_state',
            // 客户订单号 创建时间 中转仓名称 商品名称 收件人名称 是否上传身份证 申报价值 境外快递公司 境外快递单号 订单状态 操作
            operate: '<div class="jt_select_div">' +
            ' <div class="jt_select_show_val">操作</div>' +
            '   <span class="jt_select_icon ico"></span>' +
            '   <div class="jt_select_option_div">' +
            '     <ul class="jt_select_option_ul">' +
            '       <li><a href="javascript:;" onclick="detail(%order_id%)">查看详情</a></li>' +
            '       <li><a href="javascript:;" onclick="card(%order_id%)">上传身份证</a></li>' +
            '     </ul>' +
            '   </div>' +
            ' </div>'
          },
          30: {
            status: '待入仓',
            fields:'shipping_code,customer_code,add_time,tc_name,order_type,ship_method,reciver_name,order_amount,company,pre_track_no,order_state',
            // 物流单号 客户订单号 入仓时间 中转仓名称 商品名称 收件人名称 申报价值 境外快递公司 境外快递单号 订单状态 操作
            //operate: '<a href="javascript:;" class="button" onclick="card(%order_id%)">上传身份证</a> <a href="javascript:;" class="button" onclick="detail(%order_id%)">查看详情</a>'
            operate: '<div class="jt_select_div">' +
            ' <div class="jt_select_show_val">操作</div>' +
            '   <span class="jt_select_icon ico"></span>' +
            '   <div class="jt_select_option_div">' +
            '     <ul class="jt_select_option_ul">' +
            '       <li><a href="javascript:;" onclick="detail(%order_id%)">查看详情</a></li>' +
            '       <li><a href="javascript:;" onclick="card(%order_id%)">上传身份证</a></li>' +
            '     </ul>' +
            '   </div>' +
            ' </div>'
          },
          35: {
            status: '待付款',
            fields:'shipping_code,customer_code,add_time,tc_name,order_type,ship_method,reciver_name,order_amount,order_weight,shipping_fee,extra_service_fee,count_fee,order_state',
            // 物流单号 客户订单号 创建时间 中转仓名称 商品名称 收件人名称 申报价值 包裹重量 物流费 增值服务费 物流费用合计 订单状态 操作
            //operate: '<a href="javascript:;" class="button" onclick="detail(%order_id%)">查看详情</a> <a href="javascript:;" class="button btn-orange" onclick="pay(%order_id%)">立即付款</a>'
            
            operate: '<div class="jt_select_div">' +
            ' <div class="jt_select_show_val">操作</div>' +
            '   <span class="jt_select_icon ico"></span>' +
            '   <div class="jt_select_option_div">' +
            '     <ul class="jt_select_option_ul">' +
            '       <li><a href="javascript:;" onclick="detail(%order_id%)">查看详情</a></li>' +
            '       <li><a href="javascript:;" onclick="pay(%order_id%)">立即付款</a></li>' +
            '       <li><a href="javascript:;" onclick="card(%order_id%)">上传身份证</a></li>' +
            //'       <li><a href="javascript:;" onclick="sync_idcard(%order_id%)">同步身份证</a></li>' +
            '     </ul>' +
            '   </div>' +
            ' </div>'
          },
          40: {
            status: '已发货',
            fields: 'shipping_code,customer_code,add_time,tc_name,order_type,ship_method,reciver_name,order_amount,order_weight,shipping_fee,extra_service_fee,count_fee,tariff_fee',
            // 物流单号 客户订单号 创建时间 中转仓名称 商品名称 收件人名称 申报价值 包裹重量 物流费 增值服务费 物流费用合计 缴税金额 操作
            //operate: '<a href="javascript:;" class="button" onclick="detail(%order_id%)">查看详情</a> <a href="javascript:;" class="button btn-orange" onclick="tariff(%order_id%)">缴税</a>'
            //operate: '<a href="javascript:;" class="button" onclick="detail(%order_id%)" style="width:65px;height:20px;line-height:20px;">查看详情</a>',
            operate: '<div class="jt_select_div">' +
            ' <div class="jt_select_show_val">操作</div>' +
            '   <span class="jt_select_icon ico"></span>' +
            '   <div class="jt_select_option_div">' +
            '     <ul class="jt_select_option_ul">' +
            '       <li><a href="javascript:;" onclick="detail(%order_id%)">查看详情</a></li>' +
            '       <li><a href="javascript:;" onclick="paytax(%order_id%)">缴税</a></li>' +
            '     </ul>' +
            '   </div>' +
            ' </div>'
          },
          
          24: {
            status: '审核失败',
            fields: '',
            operate: ''
          },
          45: {
            status: '已完成',
            fields: 'shipping_code,customer_code,add_time,tc_name,order_type,ship_method,reciver_name,order_amount,order_weight,shipping_fee,extra_service_fee,count_fee,tariff_fee',
            // 物流单号 客户订单号 创建时间 中转仓名称 商品名称 收件人名称 申报价值 包裹重量 物流费 增值服务费 物流费用合计 缴税金额 操作
            operate: '<a href="javascript:;" class="button" onclick="detail(%order_id%)">查看详情</a>'
          }
        };
        fields[24].fields = fields[20].fields;
        fields[24].operate = fields[20].operate;

        var status_fields = fields[status];
        var tb_fields = status_fields.fields.split(',');
        for (var i in res.data) {
          var r = res.data[i];
          html += '<tr height=30 class=hover data-list-order-id="'+ r.order_id+'" data-has-identity="'+ (r.identity_code&&1||0)+'">';
          for(var j = 0; j < tb_fields.length; j++) {
            var field_name = tb_fields[j], field_val = r[field_name];
              d = field_name == 'order_state' && field_val.split('20').join('待发出').split('24').join('审核失败').split('25').join('审核中')
                  .split('30').join('待入仓').split('35').join('待付款').split('40').join('已发货').split('45').join('已完成') ||
                // 身份证
              field_name == 'identity_code' && (field_val && '已上传' || '<font color=#FF9900>未上传</font>') ||
                // 物流费用合计
                field_name == 'count_fee' && (parseFloat(r.shipping_fee > 0 && r.shipping_fee||0) + parseFloat(r.extra_service_fee>0 && r.extra_service_fee||0)>0&& '<font color="#FF9900">'+(parseFloat(r.shipping_fee > 0 && r.shipping_fee||0) + parseFloat(r.extra_service_fee>0 && r.extra_service_fee||0)).toFixed(2)+'</font>' || '未产生') ||
                // 费用明细
                $.inArray(field_name, ['tariff_fee', 'shipping_fee']) > -1 && (field_val > 0 && field_val || '未产生') ||
                  // 时间转换
                  field_name.indexOf('_time') > 0 && date(field_val,true) ||
                  // 重量
                  field_name == 'order_weight' && field_val + 'KG' ||
                  // 物流单号
                  field_name == 'shipping_code' && (field_val && field_val != '--' && '<a href="javascript:;" style="color:blue" onclick="logistics('+ r.order_id+',\''+field_val+'\')">'+field_val+'</a>') ||
              field_val || '--';
            html += '<td scope=row align=center>' + d + '</td>';
          }
          html += '<td scope=row align=center>';
          if (status == 0) {
            html += fields[r.order_state].operate.split('%order_id%').join(r.order_id);
          } else {
            var operate = status_fields.operate.split('%order_id%').join(r.order_id);
            // 判断是否要缴税
            if (status == 40 && parseInt(r.tariff_fee) == 0) {
              operate = operate.replace(/<li><a[^>]*?onclick="paytax\([^>]+\)">缴税.*?<\/li>/ig,'');
            }
            html += operate;
          }
          html += '</td></tr>';
        }
        $('#pageSpace').show().html(res.page);
      } else {
        html = '<tr height="30" class="hover">';
        html += '<td scope="row" align="center" colspan="20">' + res.msg + '</td>';
        html += '</tr>';
        $('#pageSpace').hide();
      }
      $('#count_data').html(res.count);
      $('.pro-center-box > .order-table-box:visible').append(html);
      bindTableAction();
      initTable();
    }
  });
}
//同步订单到纵腾
function sync(order_id) {
  if (!$('[data-list-order-id="'+order_id+'"]').data('has-identity')) {
    layer.alert('您的订单还未上传身份证，请先上传身份证！', {icon:3}, function (index) {
      card(order_id);
      layer.close(index);
    });
    return;
  }

    $('#loading-mask').show();
    $.ajax({
      url: SITE_SITE_URL + "/index.php?act=order&op=print_tp",
      data: {order_id: order_id},
      type: 'post',
      dataType: 'json',
      success: function (res) {
        $('#loading-mask').hide();
        if (res.status == 1) {
          layer.alert(res.msg, {icon: 1}, function (index) {
            location.reload();
            layer.close(index);
          });
        } else {
          layer.alert(res.msg, {icon: 2}, function (index) {
            layer.close(index);
          });
        }
      }
    });

}
//同步身份证到纵腾
function sync_idcard(order_id) {
  $('#loading-mask').show();
  $.ajax({
    url: SITE_SITE_URL + "/index.php?act=order&op=sync_idcard",
    data: {order_id: order_id},
    type: 'post',
    dataType: 'json',
    success: function (res) {
      $('#loading-mask').hide();
      layer.alert(res.msg, {icon:1}, function (index) {
        layer.close(index);
        initData(1);
      });
    }
  });
}
//详情弹出层
function detail(order_id,refresh) {
  layer.open({
    type: 2,
    //skin: 'layui-layer-molv',
    title: '详细信息',
    fix: false,
    maxmin: false,
    //shift: 4, //动画
    area: ['900px', '600px'],
    shadeClose: true, //点击遮罩关闭
    end: function() {
      if (refresh) {
        window.location.reload(true);
      }
    },
    content: SITE_SITE_URL + '/index.php?act=order&op=detail&order_id=' + order_id
  });
}
function drop(obj, order_id) {
  layer.confirm('此操作不可恢复，确定要删除吗？', {icon:3}, function (index) {
    var url = SITE_SITE_URL + '/index.php?act=order&op=drop';
    $.getJSON(url, {order_id: order_id}, function (res) {
      layer.alert(res.msg, {icon:1}, function () {
          initData(1);
        layer.closeAll();
      });
    });
  });
}
//上传身份证弹出层
function card(order_id) {
  layer.open({
    type: 2,
    //skin: 'layui-layer-molv',
    title: '上传身份证',
    fix: false,
    maxmin: false,
    //shift: 4, //动画
    area: ['600px', '400px'],
    shadeClose: false, //点击遮罩关闭
    end: function() {
      initData(1);
    },
    content: SITE_SITE_URL + '/index.php?act=order&op=card&order_id=' + order_id
  });
}
//选择省份后显示相应的市
function sub(obj, area_id, t, type) {
  var url = SITE_SITE_URL + '/index.php?act=user&op=get_area', t = t + 1;
  var sclass = 'arealist' + type;
  if (!area_id) {
    $(obj).closest('div').nextAll('.' + sclass).remove();
    return false;
  }
  if (t == 3) {
    return false;
  }
  var name = t == 1 ? 'city' + type : 'area' + type;
  var ulid = 'List' + t + type;

  $.getJSON(url, {area_id: area_id}, function (res) {
    if (res.status) {
      $(obj).closest('div').nextAll('.' + sclass).remove();
      var html = '<div class="dropdown shop-select ' + sclass + '">';
      html += '<a class="selectui-result dropdown-toggle"><input type="text" id="' + name + '" name="' + name + '" class="control-input" value="" placeholder="请选择" onfocus="showAndHide(\'' + ulid + '\', \'show\');" onblur="showAndHide(\'' + ulid + '\', \'hide\');"/><i class="selectIcon"></i></a>';
      html += '<ul id="' + ulid + '" class="dropdown-menu border-dropdown w-90">';
      for (var i in res.data) {
        html += '<li class="j_company" onmousedown="getVal(\'' + name + '\', \'' + res.data[i].area_name + '\');showAndHide(\'' + ulid + '\', \'hide\');sub(this,' + res.data[i].area_id + ',' + t + ',\'' + type + '\')"><a>' + res.data[i].area_name + '</a></li>';
      }
      html += '</ul></div>';
      t++;
      $(obj).closest('div').after(html);
      var receiver_origin = $('#provincer').data('origin');
      if (receiver_origin!=',,') {
        $("#"+name).parent().next('ul').find("li:contains('"+receiver_origin.split(',')[t-1]+"')").trigger('mousedown');
      }
      if (t == receiver_origin.split(',').length) {
        $('#provincer').data('origin',',,');
      }
    } else {
      $(obj).closest('div').nextAll('.' + sclass).remove();
    }
  });
}
//选择货站
function fill_tc(tid,ship_method) {
  var url = SITE_SITE_URL + '/index.php?act=trans_house&op=get_tc_info',chn_html = '';
  $.getJSON(url, {tid: tid,ship_method:ship_method}, function (res) {
    if (res.status) {
      $('#tid').val(res.data.tid);
      $('#tc_code').val(res.data.tc_code);
      $('#tcid span:eq(0)').html(res.data.receiver);
      $('#tcid span:eq(1)').html(res.data.address);
      $('#tcid span:eq(2)').html(res.data.city);
      $('#tcid span:eq(3)').html(res.data.province);
      $('#tcid span:eq(4)').html(res.data.zipcode);
      $('#tcid span:eq(5)').html(res.data.phone);
      
      var r='';
      console.log(ship_method);
      if(!ship_method && res.channel_list){
          for(var j in res.channel_list){
              r = res.channel_list[j];
              chn_html += '<li class="j_company" onmousedown="getVal(\'ship_method_name\', \''+r.channel_name+'\');showAndHide(\'ship_method_l\', \'hide\');$(\'#ship_method\').val(\''+r.channel_code+'\');selectTransCountry($(\'#tid\').val(), \''+r.channel_code+'\');"><a>'+r.channel_name+'</a></li>';
          }
          $('#ship_method_l').html(chn_html);
      }
      if(ship_method){
         $('#fee_type').text('服务收费：首重'+res.channel_info.first_weight+'，续重'+res.channel_info.first_weight+'，首重费'+res.channel_info.first_weight_fee+'，续重费'+res.channel_info.continue_weight_fee);
         }
      // extra_service_fee
      $('.addSecvi-container li').filter(function(){
        return !!($(this).data('key') && $(this).data('val'));
      }).each(function(){
        var d = JSON.parse(res.data[$(this).data('key')]),
          v = d[$(this).data('val')];
        $(this).data('value', v);
        $(this).find('font').text(v);
      });
      var serviceSum = 0;
      $tab.filter('.addSecvi-select').each(function () {
        serviceSum += parseFloat($(this).data('value'));
      });
      $("#addServiceSum").text(serviceSum.toString() + "元");
    }
  });
}
//复制更多商品
function mulgoods() {
  var blocknum = $('.detail-block').length;
  var newDom = '<div class="detail-block goods_block">' + $('.goods_block:first').html() + '</div>';
  newDom = newDom.replace('<a class="document" onclick="mulgoods()">+ 添加商品</a>', '<a class="document" onclick="dropDOM(this)">- 取消</a>');
  newDom = newDom.split('List7').join('cblock' + blocknum);
  newDom = newDom.split('List8').join('ublock' + blocknum);
  newDom = newDom.split('category').join('category' + blocknum);
  newDom = newDom.split('goods_unit').join('goods_unit' + blocknum);
  $('.goods_block:last').after(newDom);
}
//取消商品
function dropDOM(obj) {
  $(obj).closest('.detail-block').remove();
}
//手动创建订单
function psubmit() {
  var tc_code = getIdValue('tc_code'), express = getIdValue('express'), express_no = getIdValue('express_no'), remark = getIdValue('remark'),
    /*force_type = $('.addSecvi-select[data-key=force_type]').data('val'),
    is_cover = $('.addSecvi-select[data-key=is_cover]').data('val'),
    is_invoice = $('.addSecvi-select[data-key=is_invoice]').data('val'),
    is_box_ch = $('.addSecvi-select[data-key=is_box_ch]').data('val'),
    is_open = $('.addSecvi-select[data-key=is_open]').data('val'),
    is_auto_ch = $('.addSecvi-select[data-key=is_auto_ch]').data('val'),*/
    reciver_name = getIdValue('reciver_name'), provincer = getIdValue('provincer'), cityr = getIdValue('cityr'), arear = getIdValue('arear'),
    reciver_address = getIdValue('reciver_address'), reciver_zipcode = getIdValue('reciver_zipcode'), reciver_phone = getIdValue('reciver_phone'),
    sender_name = getIdValue('sender_name'), provinces = getIdValue('provinces'), citys = getIdValue('citys'), areas = getIdValue('areas'),
    sender_address = getIdValue('sender_address'), sender_zipcode = getIdValue('sender_zipcode'), sender_phone = getIdValue('sender_phone'),
    id_number = getIdValue('id_card_number'),ship_method = getIdValue('ship_method'),
    id_front = $('#id_card_front_img').attr('src') != 'templates/default/images/sfz.png' && $('#id_card_front_img').attr('src') || '',
    id_back = $('#id_card_back_img').attr('src') != 'templates/default/images/sff.png' && $('#id_card_back_img').attr('src') || ''
    ;

  var goods = [];
  $('.goods_block table tr:gt(0)').each(function() {
    // $cat['cat_name'], $unit['measure_name_cn'], $goods['name'], $goods['brand'], $goods['price'], $g['goodsNumber']
    var chr = $(this).children('td');
    goods.push([chr.eq(1).text(),chr.eq(2).text(),chr.eq(0).text(),chr.eq(3).text(),chr.eq(4).text(),chr.eq(5).text()]);
  });

  var url = SITE_SITE_URL + '/index.php?act=order_tp&op=save_order', err = '';
  if (!tc_code)
    err += "请选择转运国！<br>";
  if (!express)
    err += "请选择快递公司！<br>";
  if (!express_no)
    err += "请填写快递单号！<br>";
  if (!reciver_name)
    err += "请填写收货人姓名！<br>";
  if (!provincer)
    err += "请选择收货人省份！<br>";
  if (!cityr)
    err += "请选择收货人城市！<br>";
  if (!arear)
    err += "请选择收货人区域！<br>";
  if ($('#sender_name').length>0) {
    if (!sender_name)
      err += "请填写发货人姓名！<br>";
    if (!provinces)
      err += "请选择发货人省份！<br>";
    if (!citys)
      err += "请选择发货人城市！<br>";
    if (!areas)
      err += "请选择发货人区域！<br>";
  }
  if(ship_method == 'CH0001'){
    //if (!id_number || !/^\d{17}[\dx]$/i.test(id_number) || !(new IDValidator()).isValid(id_number))
      //err += "请输入正确的身份证号码！<br>";
    //if (!id_front)
      //err += "请上传身份证正面照片！<br>";
    //if (!id_back)
      //err += "请上传身份证反面照片！<br>";
  }
  if (!reciver_address)
    err += "请填写收货人详细地址！<br>";
  if (goods.length<1)
    err += '商品信息请填写完整！';
  // extra_service_fee
  var extra_services = {};
  $('.addSecvi-container li.addSecvi-select').filter(function(){
    return !!($(this).data('key') && $(this).data('val'));
  }).each(function(){
    extra_services[$(this).data('key')] = $(this).data('val');
  });
  if (err) {
    layer.alert(err, {icon:2});
    return false;
  }
  var data = {
    type: 'tp',
    tc_code: tc_code,
    express: express,
    express_no: express_no,
    remark: remark,
    /*force_type: force_type,
    is_cover: is_cover,
    is_invoice: is_invoice,
    is_box_ch: is_box_ch,
    is_open: is_open,
    is_auto_ch: is_auto_ch,*/
    ship_method:ship_method,
    reciver_name: reciver_name,
    provincer: provincer,
    cityr: cityr,
    arear: arear,
    reciver_address: reciver_address,
    reciver_zipcode: reciver_zipcode,
    reciver_phone: reciver_phone,
    sender_name: sender_name,
    provinces: provinces,
    citys: citys,
    areas: areas,
    sender_address: sender_address,
    sender_zipcode: sender_zipcode,
    sender_phone: sender_phone,
    id_number: id_number,
    id_front: id_front,
    id_back: id_back,
    goods: goods
  };
  if ($('#order-form-order-id').val()) {
    data.order_id = $('#order-form-order-id').val();
  }
  if ($('#order-form-consignee-id').val()) {
    data.consignee_id = $('#order-form-consignee-id').val();
  }
  $('#loading-mask').show();
  $.ajax({
    url: url,
    data: $.extend(data, extra_services),
    type: 'post',
    dataType: 'json',
    success: function (res) {
      $('#loading-mask').hide();
      if (res.status) {
        layer.alert(res.msg, {icon:1}, function (index) {
            location.reload();
          layer.close(index);
          //$('.test-slide').removeClass('in');
          closeForm();
        });
      } else {
        layer.alert(res.msg, {icon:2});
        
      }
    }
  });
}

//物流弹出层
function logistics(order_id,shipping_code) {
  layer.open({
    type: 2,
    //skin: 'layui-layer-molv',
    title: '物流详情：'+shipping_code,
    fix: false,
    maxmin: false,
    //shift: 4, //动画
    area: ['600px', '600px'],
    shadeClose: true, //点击遮罩关闭
    content: SITE_SITE_URL + '/index.php?act=order&op=track&order_id=' + order_id
  });
}

function edit(order_id) {
  showForm(order_id);
}

function fetchRemoteStatus() {
  $('#loading-mask').show();
  $.ajax({
    url: SITE_SITE_URL + "/index.php?act=order_tp&op=fetchRemoteStatus",
    data: {status: getIdValue('tab_id')},
    type: 'post',
    dataType: 'json',
    success: function (res) {
      $('#loading-mask').hide();
      layer.alert(res.msg, {icon:1}, function (index) {
        layer.close(index);
        initData(1);
      });
    }
  });
}




// layer.open({
//   content: '测试回调',
//   success: function(layero, index){
//     console.log(layero, index);
//   }
// });



window.current_pay_order_id = '';
window.current_func = '';
function pay(order_id) {
  window.current_pay_order_id = order_id;
  layer.open({
    type: 2,
    //skin: 'layui-layer-molv',
    title: '付款',
    fix: false,
    maxmin: false,
    //shift: 4, //动画
    area: ['560px', '500px'],
    shadeClose: false, //点击遮罩关闭
    end:function(){
      switch (window.current_func) {
        case 'detail':
          detail(window.current_pay_order_id, true);
          break;
        //case 'initData':
        //  initData (1);
        //  break;
        case 'pay':
          pay(window.current_pay_order_id);
          break;
        default:
          window.location.reload(true);
      }
      window.current_func = '';
    },
    content: SITE_SITE_URL + '/index.php?act=order_tp&op=payment&pay_for=order_shipping_fee&order_id=' + order_id
    //content: SITE_SITE_URL + '/index.php?act=order_tp&op=payStatusPage&status=fail&order_id=' + order_id
  });
}

function paytax(order_id) {
  window.current_pay_order_id = order_id;
  layer.open({
    type: 2,
    title: '缴税',
    fix: false,
    maxmin: false,
    area: ['560px', '500px'],
    shadeClose: false, //点击遮罩关闭
    end:function(){
      window.location.reload(true);
    },
    content: SITE_SITE_URL + '/index.php?act=order_tp&op=payment&pay_for=tax&order_id=' + order_id
  });
}