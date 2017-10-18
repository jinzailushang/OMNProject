<div class="btn-main" style="height: auto;">
    <input type="hidden" id="tab_id" name="tab_id" value="20" />
    <label>订单号</label>
    <input type="text" id="customer_code" class="search_input"  value="<?php echo $_GET['customer_code'] ?>" onkeydown="search(event);" />
    <input type="button" class="button" value="查询" onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(20, this)" class="choose">草稿</a></li>
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(30, this)">发货中</a></li>
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(40, this)">已完成</a></li>
        </ul>
    </div>
    <div class="operationsbox">
        <ul class="operNav">
            <li><a class="btn-enter"  href="javascript:;" href="javascript:void(0);" id="create-div">添加订单</a></li>
            <li><a class="btn-enter"  href="<?php echo urlShop('order', 'import', array('type' => 'dm')) ?>"><i class="ico-import-or"></i>导入</a></li>
            <li><a class="btn-enter"  href="javascript:;" id="export" target="_blank"><i class="ico-export-or"></i>导出</a></li>
        </ul>
        <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>
    <div class="pro-center-box" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">SO单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">内件品名</th>
                <th scope='col' nowrap="nowrap">申报价值</th>
                <th scope='col' nowrap="nowrap">收件人</th>
                <th scope='col' nowrap="nowrap">发件人</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
    </div>
    <div id="pageSpace"></div>
</div>
<div class="pop-tabs test-slide">

    <div class="quick_links_wrap">
        <div class="pop-head-box clearfix">
            <div class="pop-head-title"><h3><i class="ico-increase"></i>新增</h3></div>
            <div class="pop-head-hot">
                <div class="hot-box">
                    <a href="javascript:;" onclick="psubmit()"><i class="ico-submit"></i>提交</a>
                    <a href="" onclick="javascript:history.go(-1)"><i class="ico-back"></i>返回</a>
                </div>
                <a href="javascript:void(0)" class="pop-close">×</a>
            </div>
        </div>
        <form id="add_form" method="post"  action="<?php echo urlShop('order', 'save_order') ?>">
            <div class="detail-content scrollwrapper mCustomScrollbar" style="height:777px;">
                <div class="mCustomScrollBox">
                    <div class="mCSB_container">
                        <div class="goods-category-box">
                            <div class="goods-details">
                                <div class="details-wrap">
                                    <div class="detail-block">                            	
                                        <div class="detail-tab clearfix">
                                            <ul>
                                                <li class="extend-panel-toggle active">
                                                    <a class="document">运单信息</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>客户单号：</div>
                                                <div class="controls">
                                                    <input type="disabled" class="textinput" name="customer_sn" id="customer_sn" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>寄件人：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="sender" id="sender" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>寄件人地区：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select arealist">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="provinces" name="provinces" class="control-input" value="" placeholder="请选择" onfocus="showAndHide('sist6', 'show');" onblur="showAndHide('sist6', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="sist6" class="dropdown-menu border-dropdown w-90">
                                                            <?php foreach ($output['pro_list'] as $k => $v) { ?>
                                                                <li class="j_company" onmousedown="getVal('provinces', '<?php echo $v['area_name'] ?>');
                                                                            showAndHide('sist6', 'hide');
                                                                            sub(this,<?php echo $v['area_id'] ?>, 0, 's')"><a><?php echo $v['area_name'] ?></a></li>
                                                                <?php } ?>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>寄件人电话：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="sender_phone"  id="sender_phone" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>寄件人邮编：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="sender_zipcode"  id="sender_zipcode" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>寄件人地址：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="sender_address" id="sender_address" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>收件人名称：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="reciver_name" id="reciver_name" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>收件人地区：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select arealist">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="provincer" name="provincer" class="control-input" value="" placeholder="请选择" onfocus="showAndHide('List6', 'show');" onblur="showAndHide('List6', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="List6" class="dropdown-menu border-dropdown w-90">
                                                            <?php foreach ($output['pro_list'] as $k => $v) { ?>
                                                                <li class="j_company" onmousedown="getVal('provincer', '<?php echo $v['area_name'] ?>');
                                                                            showAndHide('List6', 'hide');
                                                                            sub(this,<?php echo $v['area_id'] ?>, 0, 'r')"><a><?php echo $v['area_name'] ?></a></li>
                                                                <?php } ?>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>收件人地址：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="reciver_address" id="reciver_address" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>收件人邮编：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="reciver_zipcode" id="reciver_zipcode" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>收件人电话：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="reciver_phone" id="reciver_phone" class="textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否代传身份证：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="bonded" name="has_identity" type="radio" value="是"><label class="cur-label" for="bonded">是</label>
                                                        <input id="Directmail" name="has_identity" type="radio" value="否" ><label class="cur-label"  for="Directmail">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">身份证号：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="identity_code" id="identity_code" class="textinput" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>实际重量( kg)：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="order_weight" id="order_weight" class="textinput" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>申报价值：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="order_amount" id="order_amount" class="textinput" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>原产地：</div>
                                                <div class="controls">
                                                    <input type="disabled" name="origin" id="origin" class="textinput" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否代缴关税：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_tariff1" name="is_tariff" type="radio" value="是"><label class="cur-label" for="is_tariff1">是</label>
                                                        <input id="is_tariff2" name="is_tariff" type="radio" value="否" ><label class="cur-label"  for="is_tariff2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否代缴投保：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_cover1" name="is_cover" type="radio" value="是"><label class="cur-label" for="is_cover1">是</label>
                                                        <input id="is_cover2" name="is_cover" type="radio" value="否" ><label class="cur-label"  for="is_cover2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="detail-block goods_block">
                                        <div class="detail-tab clearfix">
                                            <ul>
                                                <li class="extend-panel-toggle active">
                                                    <a class="document" onclick="test()">+ 添加商品</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>品类：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="category"  class="control-input goodslist" value="" placeholder="请选择" onfocus="showAndHide('List7', 'show');" onblur="showAndHide('List7', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="List7" class="dropdown-menu border-dropdown w-90">
                                                            <?php foreach ($output['cate_list'] as $k => $v): ?>
                                                                <li class="j_company" onmousedown="getVal('category', '<?php echo $v['cat_name'] ?>');
                                                                            showAndHide('List7', 'hide');"><a><?php echo $v['cat_name'] ?></a></li>
                                                                <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>商品单位：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="goods_unit"  class="control-input goodslist" value="" placeholder="请选择" onfocus="showAndHide('List8', 'show');" onblur="showAndHide('List8', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="List8" class="dropdown-menu border-dropdown w-90">
                                                            <?php foreach ($output['unit_list'] as $k => $v): ?>
                                                                <li class="j_company" onmousedown="getVal('goods_unit', '<?php echo $v['measure_name_cn'] ?>');
                                                                            showAndHide('List8', 'hide');"><a><?php echo $v['measure_name_cn'] ?></a></li>
                                                                <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>物品名称：</div>
                                                <div class="controls">
                                                    <input type="disabled"  class="textinput goodslist" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>品牌：</div>
                                                <div class="controls">
                                                    <input type="disabled"  class="textinput goodslist" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>单价：</div>
                                                <div class="controls">
                                                    <input type="disabled"  class="textinput goodslist" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>数量：</div>
                                                <div class="controls">
                                                    <input type="disabled"  class="textinput goodslist" value="" />
                                                </div>
                                            </div>
                                        </div>                                                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        initData(1);
        $('#create-div').click(function () {
            $('.test-slide').addClass('in');
        });
        $('.pop-close').click(function () {
            $('.test-slide').removeClass('in');
        });
        $('.dropdown').mouseenter(function () {
            $(this).addClass('open');
        }).mouseleave(function () {
            $(this).removeClass('open');
        });
    });
    /**
    function initData(page) {
        $('#loading-mask').show();
        var url = '<?php echo SITE_SITE_URL ?>/index.php?act=order&op=get_data';
        var status = getIdValue('tab_id'), customer_code = getIdValue('customer_code');
        var param = '&type=1&customer_code=' + customer_code + '&status=' + status;
        $('#export').attr('href', '<?php echo urlShop('order', 'export') ?>' + param);
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
            content: '<?php echo SITE_SITE_URL ?>/index.php?act=order&op=detail&order_id=' + order_id
        });
    }
    function drop(obj,order_id){
        layer.confirm('确定要执行此操作吗？',function(index){
            var url = '<?php echo SITE_SITE_URL ?>/index.php?act=order&op=drop';
            $.getJSON(url,{order_id:order_id},function(res){
                layer.alert(res.msg,function(){
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
            content: '<?php echo SITE_SITE_URL ?>/index.php?act=order&op=card&order_id=' + order_id
        });
    }
    //同步身份证到纵腾
    function sync_idcard(order_id) {
        $('#loading-mask').show();
        $.ajax({
            url: "<?php echo SITE_SITE_URL ?>/index.php?act=order&op=sync_idcard",
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
            content: '<?php echo SITE_SITE_URL ?>/index.php?act=order&op=track&order_id=' + order_id
        });
    }
    //手动创建订单
    function psubmit() {
        var customer_code = getIdValue('customer_sn'), sender = getIdValue('sender'), sender_phone = getIdValue('sender_phone'), sender_zipcode = getIdValue('sender_zipcode'),
                sender_address = getIdValue('sender_address'), reciver_name = getIdValue('reciver_name'), province = getIdValue('province'), city = getIdValue('city'),
                reciver_address = getIdValue('reciver_address'), reciver_zipcode = getIdValue('reciver_zipcode'), reciver_phone = getIdValue('reciver_phone'), has_identity = $('input[name="has_identity"]:checked').val(),
                identity_code = getIdValue('identity_code'), order_weight = getIdValue('order_weight'), order_amount = getIdValue('order_amount'), origin = getIdValue('origin'),
                is_tariff = $('input[name="is_tariff"]:checked').val(), is_cover = $('input[name="is_cover"]:checked').val();
        var goods = [];
        $('.goodslist').each(function () {
            goods.push($(this).val());
        });
        var url = "<?php echo urlShop('order', 'save_order') ?>", err = '';
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
            layer.alert(err);
            return false;
        }
        var data = {
            customer_code: customer_code,
            sender: sender,
            sender_phone: sender_phone,
            sender_zipcode: sender_zipcode,
            sender_address: sender_address,
            reciver_name: reciver_name,
            province: province,
            city: city,
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
        var url = "<?php echo urlShop('user', 'get_area') ?>", t = t + 1;
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
    */
    function test() {
        var newDom = '<div class="detail-block goods_block">'+$('.goods_block:first').html()+'</div>';
        newDom = newDom.split('List7').join('List88');
        $('.goods_block').after(newDom);
    }


</script>
