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
            <li>
                <a class="btn-enter"  href="javascript:;" href="javascript:void(0);" id="create-div" style="width: 48px;">添加订单</a>
                <i class="ico-warehouse"></i>
            </li>
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
                    <a href="javascript:;" onclick="$('.test-slide').removeClass('in');"><i class="ico-back"></i>返回</a>
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
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/order/order.js"></script>
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
</script>
