//手动创建订单
function add_th() {
  var tid = getIdValue('form-tid'), tc_code = getIdValue('form-tc_code'), tc_name = getIdValue('form-tc_name'), tc_type = getIdValue('form-tc_type'),country = getIdValue('form-country'), province = getIdValue('form-province'), city = getIdValue('form-city'),
    address = getIdValue('form-address'), zipcode = getIdValue('form-zipcode'), phone = getIdValue('form-phone'), receiver = getIdValue('form-receiver'),tc_type1 = getIdValue('form-tc_type1'),
     currency = getIdValue('form-currency'), countrys_img = $('#form-countrys_img').attr('src');
  var url = $('#add_form').attr('action'), err = '', extra_service_list = {};

  if(!tc_type1 || !tc_type){
    err += "请选择货站类型！<br>";
  }
  if (!tc_code)
    err += "请填写货站编码！<br>";
  if (!tc_name)
    err += "请填写货站名称！<br>";
  if (!country)
    err += "请填写国家！<br>";
  if (!province)
    err += "请填写省份！<br>";
  if (!city)
    err += "请填写城市！<br>";
  if (!address)
    err += "请填写详细地址！<br>";
  if (!zipcode)
    err += "请填写邮编！<br>";
  if (!phone)
    err += "请填写收货人电话！<br>";
  if (!receiver)
    err += "请填写收货人姓名！<br>";
  if (!currency)
    err += "请填写货币！<br>";

  // extra_service
  (function(doms){
    doms.each(function(){
      if (!$(this).val()) {
        err += "请输入"+$(this).closest('.control-group').find('.control-label').text().replace("：",'').replace('*','')
          +($(this).attr('placeholder')&&'('+$(this).attr('placeholder')+')'||'')
          +"！ <br>";
      } else {
        var tmp = $(this).attr('id').replace('form-','').split(':');
        if (extra_service_list[tmp[0]] == undefined) {
          extra_service_list[tmp[0]] = {};
        }
        extra_service_list[tmp[0]][tmp[1]] = $(this).val();
      }
    });
  })($('#trans-house-form [id*=\\:]'));
  
  var channel = {},cn = '',cc='',fz=0,xz=0,fzf=0,xzf=0,fzfh=0,xzfh=0,flag=true,flag1=true;
  $('.channel_d').each(function(i){
      channel[i] = [];
      cn = $(this).find('input:eq(0)').val();
      cc = $(this).find('input:eq(1)').val();
      fz = $(this).find('input:eq(2)').val();
      xz = $(this).find('input:eq(3)').val();
      fzf = $(this).find('input:eq(4)').val();
      xzf = $(this).find('input:eq(5)').val();
      fzfh = $(this).find('input:eq(6)').val();
      xzfh = $(this).find('input:eq(7)').val();
      
      if(!cn || !cc || !fz || !xz || !fzf || !xzf || !fzfh || !xzfh){
          flag = false;
      }
      if(!ispositive(fz) && !positivefloat(fz)){
          flag1 = false;
      }
      if(!ispositive(xz) && !positivefloat(xz)){
          flag1 = false;
      }
      if(!ispositive(fzf) && !positivefloat(fzf)){
          flag1 = false;
      }
      if(!ispositive(xzf) && !positivefloat(xzf)){
          flag1 = false;
      }
      if(!ispositive(fzfh) && !positivefloat(fzfh)){
          flag1 = false;
      }
      if(!ispositive(xzfh) && !positivefloat(xzfh)){
          flag1 = false;
      }
      channel[i].push(cn);
      channel[i].push(cc);
      channel[i].push(fz);
      channel[i].push(xz);
      channel[i].push(fzf);
      channel[i].push(xzf);
      channel[i].push(fzfh);
      channel[i].push(xzfh);
  });
  if(!flag){
      err += "渠道信息必须填写完整！<br>";
  }
  if(!flag1){
      err += "渠道费用格式错误，必须是正数！<br>";
  }
  if (err) {
    layer.alert(err);
    return false;
  }
  var data = {
      tid: tid, tc_code: tc_code, tc_name: tc_name, tc_type:tc_type,country: country, province: province, city: city,
    address: address, zipcode: zipcode, phone: phone, receiver: receiver,
    currency: currency,channel: channel,countrys_img:countrys_img
  };

  $('#loading-mask').show();
  $.ajax({
    url: url,
    data: $.extend(data, extra_service_list),
    type: 'post',
    dataType: 'json',
    success: function (res) {
      $('#loading-mask').hide();
      
      if (res.status) {
        layer.alert(res.msg, {icon:1},function (index) {
          layer.close(index);
          $('.test-slide').removeClass('in');
          initData(1);
        });
      } else {
        layer.alert(res.msg,{icon:2});
      }
    }
  });
}

function edit_th(t) {
  t = JSON.parse(t.split('__-__').join('"').split('"{"').join('{"').split('"}"').join('"}'));
  var fields = 'tid,tc_code,tc_name,tc_type,country,province,city,address,zipcode,phone,receiver,,currency,channel,country_img'.split(',');
  for(i = 0; i < fields.length; i++) {
    if(fields[i] == 'country_img'){
        if(t[fields[i]]){
            $('#form-countrys_img').attr('src',t[fields[i]]);
        }else{
            $('#form-countrys_img').attr('src','templates/default/images/sfz.png');
        }
    }else{
        $('#form-'+fields[i]).val(t[fields[i]]);
    }
  }
  // extra_service
  for(var i in trans_house_list) {
    if (i.indexOf(':')) {
      var keys = i.split(':'),
      obj = t[keys[0]];
      $('#form-'+i.replace(':','\\:')).val(obj[keys[1]]);
    }
  }
  $('.channel_t').remove();
  var rr = '',aa = '';
  var html = '';
  for(var j in t.channels){
      rr = t.channels[j];
      if(j == 0){
          aa = '<br><span id="chn"><a href="javascript:;" style="padding:10px" onclick="add(this)">+</a> </span>';
      }else{
          aa = '<br><a href="javascript:;" style="padding:10px" onclick="sub(this)">-</a> ';
      }
      html += '<div class="control-group channel_t"><div><div class="control-label"><em class="col-red">*</em>渠道：'+aa+'</div><div class="controls channel_d">';
      html += '<input type="text" value="'+rr.channel_name+'" placeholder="渠道名称" style="width:90px"/>';
      html += '<input type="text" value="'+rr.channel_code+'" placeholder="渠道编码" style="width:90px"/>';
      html += '<input type="text" value="'+rr.first_weight+'" placeholder="首重" style="width:90px"/>';
      html += '<input type="text" value="'+rr.continue_weight+'" placeholder="续重" style="width:90px"/>';
      html += '<input type="text" value="'+rr.first_weight_fee+'" placeholder="首重费" style="width:90px"/>';
      html += '<input type="text" value="'+rr.continue_weight_fee+'" placeholder="续重费" style="width:90px"/>';
      html += '<input type="text" value="'+rr.first_weight_fee_h+'" placeholder="首重费(国内段)" style="width:90px"/>';
      html += '<input type="text" value="'+rr.continue_weight_fee_h+'" placeholder="续重费(国内段)" style="width:90px"/>';
      html += '</div></div></div>';
  }
  
  $('.control-group:last').after(html);
  //var str = $('#form-tc_type').val() == 'zt' ? '纵腾仓' : '威廉仓';
  $('#form-tc_type1').val(t.tc_type);
  $('#form-tc_type').val(t.tc_type == '纵腾仓' ? 'zt' : 'wms');
  $('.pop-head-title span').text('编辑');
  $('.test-slide').addClass('in');
}

function del_th(tid) {
  if (confirm('此操作不可恢复，确定要删除吗？')) {
    $('#loading-mask').show();
    $.ajax({
      url: SITE_SITE_URL + "/index.php?act=trans_house&op=del",
      data: {tid: tid},
      type: 'post',
      dataType: 'json',
      success: function (res) {
        $('#loading-mask').hide();
        if (res.status) {
          layer.alert(res.msg, function (index) {
            layer.close(index);
            initData(1);
          });
        } else {
          layer.alert(res.msg);
        }
      }
    });
  }
}
function detail(tid){
    layer.open({
        type: 2,
        id: 'sp_detail',
        title: '收费明细',
        fix: false,
        maxmin: false,
        area: ['800px', '450px'],
        shadeClose: true, //点击遮罩关闭
        content:SITE_SITE_URL +  '/index.php?act=trans_house&op=detail&tid=' + tid
    });
}