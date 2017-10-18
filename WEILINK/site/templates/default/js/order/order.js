function initData(page) {
    $('#loading-mask').show();
    var url = SITE_SITE_URL + '/index.php?act=order&op=get_data';
    var status = getIdValue('tab_id'), customer_code = getIdValue('customer_code');
    var param = '&type=1&customer_code=' + customer_code + '&status=' + status;
    $('#export').attr('href', SITE_SITE_URL + '/index.php?&act=order&op=export' + param);
    $.ajax({
        url: url,
        data: {curpage: page, type: 1, status: status, customer_code: customer_code},
        type: 'get',
        dataType: 'json',
        success: function (res) {
            $('#loading-mask').hide();
            $('.hover').remove();
            if (res.status == 1) {
                var r = '';
                var html = '';
                var resData = '';

                for (var i in res.data) {
                    r = res.data[i];
                    html += '<tr height="30" class="hover">';
                    if (r.order_state == 20) {
                        html += '<td scope="row" align="center">' + r.shipping_code + '</td>';
                    } else {
                        html += '<td scope="row" align="center"><a href="javascript:;" onclick="track(' + r.order_id + ')">' + r.shipping_code + '</a></td>';
                    }
                    html += '<td scope="row" align="center">' + r.order_sn + '</td>';
                    html += '<td scope="row" align="center">' + r.customer_code + '</td>';
                    html += '<td scope="row" align="center" title="' + r.gname + '">' + r.gname_s + '</td>';
                    html += '<td scope="row" align="center">' + r.order_amount + '</td>';
                    html += '<td scope="row" align="center">' + r.reciver_name + '</td>';
                    html += '<td scope="row" align="center">' + r.sender + '</td>';
                    html += '<td scope="row" align="center"><div class="jt_select_div"><div class="jt_select_show_val">操作</div><span class="jt_select_icon ico"></span><div class="jt_select_option_div"><ul class="jt_select_option_ul">';
                    html += '<li><a href="javascript:;" onclick="detail(' + r.order_id + ')">详情</a></li>';
                    if (r.has_identity == '是') {
                        html += '<li><a href="javascript:;" onclick="card(' + r.order_id + ')">上传身份证</a></li>';
                    }

                    html += '<li><a target="_blank" href="<?php echo SITE_SITE_URL ?>/index.php?act=order&op=print_dm&order_id=' + r.order_id + '" >打印面单</a></li>';
                    if (r.order_state == 30) {
                        html += '<li><a  href="javascript:;" onclick="sync_idcard(\'' + r.order_id + '\')">同步身份证</a></li>';
                    }
                    html += '<li><a href="javascript:;" onclick="drop(this,' + r.order_id + ')">删除订单</a></li>';
                    html += '</ul></div></div></div></td>';
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
            $('.order-table-box').append(html);
            bindTableAction();
            initTable();
        }
    });
}
//详情弹出层
function detail(order_id) {
    layer.open({
        type: 2,
        title: '详细信息',
        fix: false,
        maxmin: false,
        area: ['900px', '600px'],
        shadeClose: true, //点击遮罩关闭
        content: SITE_SITE_URL + '/index.php?act=order&op=detail&order_id=' + order_id
    });
}
function drop(obj, order_id) {
    layer.confirm('确定要执行此操作吗？', function (index) {
        var url = SITE_SITE_URL + '/index.php?act=order&op=drop';
        $.getJSON(url, {order_id: order_id}, function (res) {
            layer.alert(res.msg, function () {
                layer.closeAll();
                $(obj).closest('tr').remove();
            });
        });
    });
}
//上传身份证弹出层
function card(order_id) {
    layer.open({
        type: 2,
        title: '上传身份证',
        fix: false,
        maxmin: false,
        area: ['700px', '450px'],
        shadeClose: true, //点击遮罩关闭
        content: SITE_SITE_URL + '/index.php?act=order&op=card&order_id=' + order_id
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
            layer.alert(res.msg, function (index) {
                layer.close(index);
                initData(1);
            });
        }
    });
}
function track(order_id) {
    layer.open({
        type: 2,
        title: '运单物流',
        fix: false,
        maxmin: false,
        area: ['700px', '450px'],
        shadeClose: true, //点击遮罩关闭
        content: SITE_SITE_URL + '/index.php?act=order&op=track&order_id=' + order_id
    });
}
//手动创建订单
function psubmit() {
    var customer_code = getIdValue('customer_sn'), sender = getIdValue('sender'), provinces = getIdValue('provinces'), citys = getIdValue('citys'), areas = getIdValue('areas'),
            sender_phone = getIdValue('sender_phone'), sender_zipcode = getIdValue('sender_zipcode'),
            sender_address = getIdValue('sender_address'), reciver_name = getIdValue('reciver_name'), provincer = getIdValue('provincer'), cityr = getIdValue('cityr'), arear = getIdValue('arear'),
            reciver_address = getIdValue('reciver_address'), reciver_zipcode = getIdValue('reciver_zipcode'), reciver_phone = getIdValue('reciver_phone'), has_identity = $('input[name="has_identity"]:checked').val(),
            identity_code = getIdValue('identity_code'), order_weight = getIdValue('order_weight'), order_amount = getIdValue('order_amount'), origin = getIdValue('origin'),
            is_tariff = $('input[name="is_tariff"]:checked').val(), is_cover = $('input[name="is_cover"]:checked').val();
    var goods = {};
    $('.goods_block').each(function (i) {
        goods[i] = [];
        $(this).find('input').each(function (t) {
            goods[i].push($(this).val());
        });
    });
    var url = SITE_SITE_URL + '/index.php?act=order&op=save_order', err = '';
    if (!customer_code)
        err += "请填写客户单号！<br>";
    if (!sender)
        err += "请填写寄件人！<br>";
    if (!reciver_name)
        err += "请填写收货人姓名！<br>";
    if (!province)
        err += "请选择收货人省份！<br>";
    if (!city)
        err += "请选择收货人城市！<br>";
    if (!reciver_address)
        err += "请填写收货人详细地址！<br>";
    if (err) {
        //layer.alert(err);
        //return false;
    }
    var data = {
        customer_code: customer_code,
        sender: sender,
        provinces: provinces,
        citys: citys,
        areas: areas,
        sender_phone: sender_phone,
        sender_zipcode: sender_zipcode,
        sender_address: sender_address,
        reciver_name: reciver_name,
        provincer: provincer,
        cityr: cityr,
        arear: arear,
        reciver_address: reciver_address,
        reciver_zipcode: reciver_zipcode,
        reciver_phone: reciver_phone,
        has_identity: has_identity,
        identity_code: identity_code,
        order_weight: order_weight,
        order_amount: order_amount,
        origin: origin,
        is_tariff: is_tariff,
        is_cover: is_cover,
        goods: goods
    };
    $('#loading-mask').show();
    $.ajax({
        url: url,
        data: data,
        type: 'post',
        dataType: 'json',
        success: function (res) {
            $('#loading-mask').hide();
            $('.test-slide').removeClass('in');
            if (res.status) {
                layer.alert(res.msg, function (index) {
                    layer.close(index);
                    $('.test-slide').removeClass('in');
                    initData(1);
                });
            } else {
                layer.alert(res.msg);
            }
        }
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
        } else {
            $(obj).closest('div').nextAll('.' + sclass).remove();
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