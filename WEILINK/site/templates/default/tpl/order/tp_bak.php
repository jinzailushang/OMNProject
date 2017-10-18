
<!--<link href="templates/default/css/addOrder.css" rel="stylesheet" type="text/css">-->
<style>
    .box-wrap.order-boxWrap.in {
        display:none;
    }
</style>
<div class="btn-main" style="height: auto;">
    <input type="hidden" id="tab_id" name="tab_id" value="20"/>
    <label>订单号</label>

    <input type="text" id="search-form-order_sn" class="search_input" value="<?php echo $_GET['order_sn'] ?>"
           onkeydown="search(event);"/>
    <label>中转仓名称</label>
    <select name="transit" id="search-form-tc_code" onchange="">
        <option value="">请选择</option>
        <?php
        foreach ($output['trans_list'] as $th) {
            echo '<option value="'.$th['tc_code'].'"'.($_GET['tc_code'] == $th['tc_code']? ' selected':'').'>'. $th['tc_name']. "</option>";
        }
        ?>
    </select>
    <label>收件人名称</label>
    <input type="text" id="search-form-customer_code" class="search_input" value="<?php echo $_GET['customer_code'] ?>"
           onkeydown="search(event);"/>
    <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" tab-group="1" id="" onclick="changeTabs(20, this)" class="choose">待发出</a></li>
            <li><a href="javascript:void(0);" tab-group="2" id="" onclick="changeTabs(25, this)">审核中</a></li>
            <li><a href="javascript:void(0);" tab-group="3" id="" onclick="changeTabs(30, this)">待入仓</a></li>
            <li><a href="javascript:void(0);" tab-group="4" id="" onclick="changeTabs(35, this)">待付款</a></li>
            <li><a href="javascript:void(0);" tab-group="5" id="" onclick="changeTabs(40, this)">已发货</a></li>
            <li><a href="javascript:void(0);" tab-group="6" id="" onclick="changeTabs(45, this)">已完成</a></li>
            <li><a href="javascript:void(0);" tab-group="7" id="" onclick="changeTabs(0, this)">全部</a></li>
        </ul>
    </div>
    <div class="operationsbox">
        <ul class="operNav">
            <li><a class="btn-enter" href="javascript:;" href="javascript:void(0);" id="create-div">添加订单</a></li>
            <li><a class="btn-enter" href="<?php echo urlShop('order_tp', 'import', array('type' => 'tp')) ?>"><i
                      class="ico-import-or"></i>导入</a></li>
            <li><a class="btn-enter" href="javascript:;" id="export" targat="_blank"><i class="ico-export-or"></i>导出</a></li>
            <li><a class="btn-enter" href="javascript:;" targat="_blank" onclick="fetchRemoteStatus()">更新订单状态</a></li>
        </ul>
        <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>
    <div class="pro-center-box">
        <!--    待发出-->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">是否上传身份证</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">境外快递公司</th>
                <th scope='col' nowrap="nowrap">境外快递单号</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 审核中 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="2" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">是否上传身份证</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">境外快递公司</th>
                <th scope='col' nowrap="nowrap">境外快递单号</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 待入仓 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="3" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">入仓时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">境外快递公司</th>
                <th scope='col' nowrap="nowrap">境外快递单号</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 待付款 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="4" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">包裹重量</th>
                <th scope='col' nowrap="nowrap">物流费(元)</th>
                <th scope='col' nowrap="nowrap">增值服务费(元)</th>
                <th scope='col' nowrap="nowrap">物流费用合计(元)</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 已发货 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="5" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">包裹重量</th>
                <th scope='col' nowrap="nowrap">物流费(元)</th>
                <th scope='col' nowrap="nowrap">增值服务费(元)</th>
                <th scope='col' nowrap="nowrap">物流费用合计(元)</th>
                <th scope='col' nowrap="nowrap">缴税金额(元)</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 已完成 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="6" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">包裹重量</th>
                <th scope='col' nowrap="nowrap">物流费(元)</th>
                <th scope='col' nowrap="nowrap">增值服务费(元)</th>
                <th scope='col' nowrap="nowrap">物流费用合计(元)</th>
                <th scope='col' nowrap="nowrap">缴税金额(元)</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 全部 -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="7" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">是否上传身份证</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">包裹重量</th>
                <th scope='col' nowrap="nowrap">物流费(元)</th>
                <th scope='col' nowrap="nowrap">增值服务费(元)</th>
                <th scope='col' nowrap="nowrap">物流费用合计(元)</th>
                <th scope='col' nowrap="nowrap">缴税金额(元)</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
    </div>

    <!--    待付款-->
    <div id="pageSpace"></div>
</div>
<div class="pop-tabs test-slide">

    <div class="quick_links_wrap">
        <div class="pop-head-box clearfix">
            <div class="pop-head-title"><h3><i class="ico-increase"></i>新增</h3></div>
            <div class="pop-head-hot">
                <div class="hot-box">
                    <a href="javascript:;" onclick="psubmit()"><i class="ico-submit"></i>提交</a>
                    <a href="javascript:;" onclick="$('.pop-close').trigger('click')"><i class="ico-back"></i>返回</a>
                </div>
                <a href="javascript:void(0)" class="pop-close">×</a>
            </div>
        </div>
        <form id="add_form" method="post"  action="<?php echo urlShop('order_tp', 'save_order') ?>">

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
                                                    <a class="document">转运国</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>转运国家：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select arealist">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="transh" name="transh" class="control-input" value="" placeholder="请选择" onfocus="showAndHide('transh_l', 'show');" onblur="showAndHide('transh_l', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="transh_l" class="dropdown-menu border-dropdown w-90">
                                                            <?php foreach ($output['trans_list'] as $k => $v): ?>
                                                                <li class="j_company" onmousedown="getVal('transh', '<?php echo $v['country'] ?>');showAndHide('transh_l', 'hide');fill_tc(<?php echo $v['tid'] ?>)"><a><?php echo $v['country'] ?></a></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="controls" style="margin-left:0;" id="tcid">
                                                    <input type="hidden" id="tc_code"  value="" />
                                                    <p>收货人（Name）：<span></span></p>
                                                    <p>地址1（Address1）：<span></span></p>
                                                    <p>地址2（Address2）：<span></span></p>
                                                    <p>城市（City）：<span></span></p>
                                                    <p>州/省（Name）：<span></span></p>
                                                    <p>邮编（Zip Code）：<span></span></p>
                                                    <p>电话（Tel）：<span></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="detail-block">
                                        <div class="detail-tab clearfix">
                                            <ul>
                                                <li class="extend-panel-toggle active">
                                                    <a class="document">快递信息</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>快递公司：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select arealist">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="express" name="express" class="control-input" value="" placeholder="请选择" onfocus="showAndHide('express_l', 'show');" onblur="showAndHide('express_l', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="express_l" class="dropdown-menu border-dropdown w-90">
                                                            <li class="j_company" onmousedown="getVal('express', 'UPS');
                                                                        showAndHide('express_l', 'hide');"><a>UPS</a></li>
                                                            <li class="j_company" onmousedown="getVal('express', 'DHL');
                                                                        showAndHide('express_l', 'hide');"><a>DHL</a></li>
                                                            <li class="j_company" onmousedown="getVal('express', 'FEDEX');
                                                                        showAndHide('express_l', 'hide');"><a>FEDEX</a></li>
                                                            <li class="j_company" onmousedown="getVal('express', 'TNT');
                                                                        showAndHide('express_l', 'hide');"><a>TNT</a></li>
                                                            <li class="j_company" onmousedown="getVal('express', '其他物流公司');
                                                                        showAndHide('express_l', 'hide');"><a>其他物流公司</a></li>
                                                        </ul>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label"><em class="col-red">*</em>快递单号：</div>
                                                <div class="controls">
                                                    <input type="disabled" class="textinput" name="express_no" id="express_no" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">备注：</div>
                                                <div class="controls">
                                                    <input type="disabled" class="textinput" name="remark" id="remark" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="detail-block">
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">加固类型：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="force_type1" name="force_type" type="radio" value="0" checked=""><label class="cur-label" for="force_type1">不加固</label>
                                                        <input id="force_type2" name="force_type" type="radio" value="1" ><label class="cur-label"  for="force_type2">基础加固</label>
                                                        <input id="force_type3" name="force_type" type="radio" value="2" ><label class="cur-label"  for="force_type3">特殊加固</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否投保：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_cover1" name="is_cover" type="radio" value="是"><label class="cur-label" for="is_cover1">是</label>
                                                        <input id="is_cover2" name="is_cover" type="radio" value="否" checked=""><label class="cur-label"  for="is_cover2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否取出发票：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_invoice1" name="is_invoice" type="radio" value="是"><label class="cur-label" for="is_invoice1">是</label>
                                                        <input id="is_invoice2" name="is_invoice" type="radio" value="否"  checked=""><label class="cur-label"  for="is_invoice2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否外箱替换：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_box_ch1" name="is_box_ch" type="radio" value="是"><label class="cur-label" for="is_box_ch1">是</label>
                                                        <input id="is_box_ch2" name="is_box_ch" type="radio" value="否"  checked=""><label class="cur-label"  for="is_box_ch2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否开箱清点：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_open1" name="is_open" type="radio" value="是"><label class="cur-label" for="is_open1">是</label>
                                                        <input id="is_open2" name="is_open" type="radio" value="否" checked=""><label class="cur-label"  for="is_open2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                                <div class="control-label">是否智能换箱：</div>
                                                <div class="controls">
                                                    <div class="goodsType">
                                                        <input id="is_auto_ch1" name="is_auto_ch" type="radio" value="是"><label class="cur-label" for="is_auto_ch1">是</label>
                                                        <input id="is_auto_ch2" name="is_auto_ch" type="radio" value="否" checked=""><label class="cur-label"  for="is_auto_ch2">否</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="detail-block">
                                        <div class="detail-tab clearfix">
                                            <ul>
                                                <li class="extend-panel-toggle active">
                                                    <a class="document">收货信息</a>
                                                </li>
                                            </ul>
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

                                    </div>
                                    <div class="detail-block goods_block">
                                        <div class="detail-tab clearfix">
                                            <ul>
                                                <li class="extend-panel-toggle active">
                                                    <a class="document" onclick="mulgoods()">+ 添加商品</a>
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
                                                <div class="control-label">品牌：</div>
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
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/order/order_tp.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        initData(1);
        $('#create-div').click(function () {
            $('.test-slide').addClass('in');
            $(".order-boxWrap").addClass('in').css("height", "100%");
        });
        
        $('.pop-close').click(function () {
            $('.test-slide').removeClass('in');
            $('.order-boxWrap').removeClass('in');
        });
        $('.dropdown').mouseenter(function () {
            $(this).addClass('open');
        }).mouseleave(function () {
            $(this).removeClass('open');
        });
    });
</script>
<div class="box-wrap  order-boxWrap"></div>