
<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/addOrder.css" rel="stylesheet" type="text/css">
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/jquery.1_9_1_min.js"></script>

<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/validate/jquery.validate.min.js"></script>

<div class="btn-main">
  <ul>
    <li><input type="submit" class="edit-button" id="edit-message" value="编辑 " style="cursor: pointer;" onclick=""/></li>
    <!--    <li><input type="reset" class="button" value="返回" style="cursor: pointer;" onclick="javascript:history.go(-1)"/>-->
    </li>
  </ul>
</div>
<div class="clear"></div>

<div class="center">
  <div class="pro-center-con">
    <!-- begin goods-tabs-->

    <div class="wrap">
      <div class="tabmenu">
        <ul class="tab pngFix">
          <li class="active">
            <a href="#">基本信息</a>
          </li>
        </ul>
      </div>
      <div class="ncm-default-form">
        <form id="house_form" method="post" action="" enctype="multipart/form-data">
          <input type="hidden" name="form_submit" value="ok"/>
          <input type="hidden" name="u_id" value="<?php echo $output['info']['u_id'] ?>"/>
          <dl>
            <dt>账户余额：</dt>
            <dd>
              <span class="w400"><font color="red" size="3"><?php echo $output['money']['balance']?$output['money']['balance']:'0.00' ?></font> 人民币&nbsp;&nbsp;&nbsp;&nbsp;<a
                    href="#" id="recharge-btn">充值 </a> | <a href="<?php echo urlShop('user', 'transacte') ?>">交易记录</a> | <a href="<?php echo urlShop('user', 'bill')?>">查询账单</a></span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>
          <dl>
            <dt>账号：</dt>
            <dd>
              <span class="w400"><?php echo $output['info']['u_name'] ?>&nbsp;&nbsp;</span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>
          <dl>
            <dt>发件人姓名：</dt>
            <dd>
			              <span class="w400">
                      <?php echo $output['consignor']['name']?>
<!--			              	<input type="text" value="--><?php //echo $output['info']['first_name'] ?><!--" name="first_name"-->
                            <!--                             id="first_name" class="input-name"/>-->
			              </span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>

          <dl>
            <dt>发件人电话：</dt>
            <dd>
			              <span class="w400">
                      <?php echo $output['consignor']['phone']?>
<!--			              	<input type="text" value="--><?php //echo $output['info']['phone'] ?><!--" name="phone" id="phone"-->
                            <!--                             class="input-name"/>-->
			              </span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>
          <dl>
            <dt>发件人所在地区：</dt>
            <dd>
			              <span class="w400">
                      <?php echo $output['consignor']['province'].','.$output['consignor']['city'].','.$output['consignor']['area']?>
			              </span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>
          <dl>
            <dt>发件人详细地址：</dt>
            <dd>
			              <span class="w400">
<!--			              	<input type="text" value="--><?php //echo $output['info']['address'] ?><!--" name="address" id="bank_name"-->
                            <!--                             class="input-name"/>-->
                      <?php echo $output['consignor']['address']?>

			              </span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>
          <dl>
            <dt>发件人邮政编码：</dt>
            <dd>
			              <span class="w400">
<!--			              	<input type="text" value="--><?php //echo $output['info']['zipcode'] ?><!--" name="zipcode" id="zipcode"-->
                            <!--                             class="input-name"/>-->
                      <?php echo $output['consignor']['zipcode']?>
			              </span>
              <span>&nbsp;&nbsp;</span>
            </dd>
          </dl>
        </form>
      </div>
    </div>
  </div>
</div>
<!--编辑个人信息-->
<script  type="text/js-tmpl" class="edit-person-message">
  <div class="edit-person-message-wrap">
    <form method="post" id="editPerson" class="edit-person-form">
      <div>
        <div>
          <input type="text"  name="full_name" id="form-consignor-name" class="edit-person-input input-name" autocomplete="off" placeholder="姓名" aria-required="true" value="<?php echo $output['consignor']['name']?>">
        </div>
        <div class="input-tip">
          <span id="form-account-error" class="error"></span>
        </div>
      </div>
      <div>
        <div>
          <input type="text" name="edit_ph"  id="form-consignor-phone" class="edit-person-input" autocomplete="off" placeholder="电话" aria-required="true" value="<?php echo $output['consignor']['phone']?>">
        </div>
        <div class="input-tip">
          <span id="form-account-error" class="error"></span>
        </div>
      </div>
      <div>
        <div>
          <div class=" controls" style="margin-left:0;">
      <div class=" dropdown shop-select arealist" >
        <a class="selectui-result dropdown-toggle"><input type="text" class="textinput" id="provincer"
                                                          name="provincer"
                                                          data-origin="<?php echo $output['consignor']['province'].','.$output['consignor']['city'].','.$output['consignor']['area']?>"
                                                          class="control-input" value=""
                                                          placeholder="请选择"
                                                          onfocus="showAndHide('List6', 'show');"
                                                          onblur="showAndHide('List6', 'hide');"><i
            class="selectIcon"></i></a>
        <ul id="List6" class="dropdown-menu border-dropdown w-90">
          <li class="j_company" onmousedown="getVal('provincer', '北京市');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,1, 0, 'r')"><a>北京市</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '天津市');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,2, 0, 'r')"><a>天津市</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '河北省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,3, 0, 'r')"><a>河北省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '山西省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,4, 0, 'r')"><a>山西省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '内蒙古自治区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,5, 0, 'r')"><a>内蒙古自治区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '辽宁省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,6, 0, 'r')"><a>辽宁省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '吉林省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,7, 0, 'r')"><a>吉林省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '黑龙江省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,8, 0, 'r')"><a>黑龙江省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '上海市');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,9, 0, 'r')"><a>上海市</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '江苏省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,10, 0, 'r')"><a>江苏省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '浙江省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,11, 0, 'r')"><a>浙江省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '安徽省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,12, 0, 'r')"><a>安徽省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '福建省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,13, 0, 'r')"><a>福建省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '江西省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,14, 0, 'r')"><a>江西省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '山东省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,15, 0, 'r')"><a>山东省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '河南省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,16, 0, 'r')"><a>河南省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '湖北省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,17, 0, 'r')"><a>湖北省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '湖南省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,18, 0, 'r')"><a>湖南省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '广东省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,19, 0, 'r')"><a>广东省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '广西壮族自治区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,20, 0, 'r')"><a>广西壮族自治区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '海南省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,21, 0, 'r')"><a>海南省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '重庆市');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,22, 0, 'r')"><a>重庆市</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '四川省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,23, 0, 'r')"><a>四川省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '贵州省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,24, 0, 'r')"><a>贵州省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '云南省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,25, 0, 'r')"><a>云南省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '西藏自治区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,26, 0, 'r')"><a>西藏自治区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '陕西省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,27, 0, 'r')"><a>陕西省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '甘肃省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,28, 0, 'r')"><a>甘肃省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '青海省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,29, 0, 'r')"><a>青海省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '宁夏回族自治区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,30, 0, 'r')"><a>宁夏回族自治区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '新疆维吾尔自治区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,31, 0, 'r')"><a>新疆维吾尔自治区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '台湾省');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,32, 0, 'r')"><a>台湾省</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '香港特别行政区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,33, 0, 'r')"><a>香港特别行政区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '澳门特别行政区');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,34, 0, 'r')"><a>澳门特别行政区</a></li>
          <li class="j_company" onmousedown="getVal('provincer', '海外');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,35, 0, 'r')"><a>海外</a></li>
        </ul>
      </div>

    </div>
        </div>
        
        <div class="input-tip">
          <span id="form-account-error" class="error"></span>
        </div>
      </div>
      <div>
        <div>
          <input type="text"  name="edit_address" id="form-consignor-address" class="edit-person-input"
          autocomplete="off"
          placeholder="详细地址" value="<?php echo $output['consignor']['address']?>">
        </div>
        <div class="input-tip">
          <span id="form-account-error" class="error"></span>
        </div>
      </div>
      <div>
        <input type="text" name="edit_pb" id="form-consignor-zipcode" class="edit-person-input" autocomplete="off"
        placeholder="邮编" value="<?php echo $output['consignor']['zipcode']?>">
      </div>
      <div class="edit-person-qx">
        <input type="button" class="edit-con" value="确定" onclick="saveConsignor()">
        <input type="button" class="edit-cancel" value="取消" >
      </div>
    </form>
  </div>
</script>
<script type="text/javascript">
  //  编辑
  $("#edit-message").on("click",function () {
    layer.open({
      type: 1,
      //skin: 'layui-layer-molv',
      title: '编辑个人信息',
      fix: false,
      maxmin: false,
      //shift: 4, //动画
      area: ['550px', '350px'],
      shadeClose: false, //点击遮罩关闭
      content: $(".edit-person-message").html()
    });
    <!--编辑个人信息验证-->
    $("#editPerson")
        .validate({
          errorPlacement: function(error, element) {
            var  inputTip = element.parent().parent().find(".input-tip");
            error.appendTo(inputTip);
          },
          rules:{
            full_name:{
              required: true
            },
            edit_ph:{
              required:true
            },
            area:{
              required:true
            },
            edit_address:{
              required:true
            }
          },
          messages:{
            full_name:{
              required:  "*姓名不能为空"
            },
            edit_ph:{
              required:  "*电话不能为空"
            },
            area:{
              required:"*地区不能为空"
            },
            edit_address:{
              required:  "*详细地址不能为空"
            }
          }
        });
    
//    点击按钮，关闭页面
    $(".edit-cancel").click(function () {
      layer.closeAll();
    });

    window.setTimeout(function(){
      initReciverRegion();
    },10);
  });

  function saveConsignor() {
    var errors = [],data = {
      name:getIdValue('form-consignor-name'),
      phone: getIdValue('form-consignor-phone'),
      province: getIdValue('provincer'),
      city: getIdValue('cityr'),
      area: getIdValue('arear'),
      address: getIdValue('form-consignor-address'),
      zipcode: getIdValue('form-consignor-zipcode')
    };

    if (!data.name) {
      errors.push('姓名不能为空');
    }
    if (!data.phone) {
      errors.push('电话不能为空');
    }
    if (!data.province) {
      errors.push('省份不能为空');
    }
    if (!data.city) {
      errors.push('城市不能为空');
    }
    if (!data.address) {
      errors.push('地址不能为空');
    }
    if (!data.zipcode) {
      errors.push('邮编不能为空');
    }
    if (errors.length>0) {
      if (errors) {
        layer.alert(errors.join('<br>'), {icon:2});
        return false;
      }
    }

    $('#loading-mask').show();
    $.ajax({
      url: SITE_SITE_URL + '/index.php?act=user&op=saveConsignor',
      data: data,
      type: 'post',
      dataType: 'json',
      success:function(res) {
        $('#loading-mask').hide();
        if (res.status) {
          layer.alert(res.msg, {icon:1}, function (index) {
            layer.close(index);
            //$('.test-slide').removeClass('in');
            location.reload();
          });
        } else {
          layer.alert(res.msg, {icon:2});
        }
      },
      error: function(res) {
        $('#loading-mask').hide();
        layer.alert('服务器繁忙', {icon:2});
      }
    });
  }

</script>

<script type="text/javascript">
  function psubmit() {
    var province = getIdValue('province'), city = getIdValue('city'), area = getIdValue('area');
    if (province) {
      if (!city || !area) {
        layer.alert('所在地区请选择完整！', {icon: 2});
        return false;
      }
    }
    $('#house_form').submit();
  }
  function show_sub(obj, t) {
    var url = "<?php echo urlShop('user', 'get_area')?>", area_id = $(obj).val(), t = t + 1;
    if (!area_id) {
      $(obj).nextAll('select').remove();
      return false;
    }
    if (t == 3) {
      return false;
    }
    var name = t == 1 ? 'city' : 'area';
    $.getJSON(url, {area_id: area_id}, function (res) {
      if (res.status) {
        $(obj).nextAll('select').remove();
        var html = '<select name="' + name + '" id="' + name + '" class="areas" onchange="show_sub(this,' + t + ')">';
        html += '<option value="">请选择</option>';
        for (var i in res.data) {
          html += '<option value="' + res.data[i].area_id + '">' + res.data[i].area_name + '</option>';
        }
        html += '</select>';
        t++;
        $(obj).after(html);
      } else {
        $(obj).nextAll('select').remove();
      }
    });
  }

  window.current_func = '';
  $('#recharge-btn').bind('click', function(e){
    e.preventDefault();
    layer.open({
      type: 2,
      //skin: 'layui-layer-molv',
      title: '充值',
      fix: false,
      maxmin: false,
      //shift: 4, //动画
      area: ['560px', '400px'],
      shadeClose: false, //点击遮罩关闭
      end:function(){
        switch (window.current_func) {
          case 'pay':
            $('#recharge-btn').trigger('click');
            break;
          default:
            window.location.reload();
        }
        window.current_func = '';
      },
      content: SITE_SITE_URL + '/index.php?act=order_tp&op=payment&pay_for=recharge'
    });
  });

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

  function show_sub(obj,t){
    var url = "<?php echo urlShop('user','get_area')?>",area_id=$(obj).val(),t=t+1;
    if(!area_id){
      $(obj).nextAll('select').remove();
      return false;
    }
    if(t == 2){
      return false;
    }
    var name = t == 1 ? 'city' : 'area';
    $.getJSON(url,{area_id:area_id},function(res){
      if(res.status){
        $(obj).nextAll('select').remove();
        var html = '<select name="'+name+'" id="'+name+'" class="areas" onchange="show_sub(this,'+t+')">';
        html += '<option value="">请选择</option>';
        for(var i in res.data){
          html += '<option value="'+res.data[i].area_id+'">'+res.data[i].area_name+'</option>';
        }
        html += '</select>';
        t++;
        $(obj).after(html);
      }else {
        $(obj).nextAll('select').remove();
      }
    });
  }

  function initReciverRegion() {
    var receiver_origin = $('#provincer').data('origin');
    if (receiver_origin!=',,') {
      console.log(receiver_origin);
      $("#provincer").parent().next('ul').find("li:contains('"+receiver_origin.split(',')[0]+"')").trigger('mousedown');
    }
  }
</script>