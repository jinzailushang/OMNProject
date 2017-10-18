
<link href="templates/default/css/addOrder.css" rel="stylesheet" type="text/css">

<div class="btn-main" style="height: auto;">
    <input type="hidden" id="tab_id" name="tab_id" value="20"/>
    <label>订单号</label>
    <input type="text" id="search-form-order_sn" class="search_input" value="<?php echo $_GET['order_sn'] ?>" onkeydown="search(event);"/>
    <label>订单类型</label>
    <select id="search-form-order_type" onchange="initData(1)">
        <option value="">请选择</option>
        <option value="3">备货</option>
        <option value="2">转运</option>
    </select>
    <!--    <label>运输方式</label>
        <select id="search-form-ship_method" onchange="initData(1)">
            <option value="">请选择</option>
            <option value="标准服务">标准服务</option>
            <option value="台-中邮件">台-中邮件</option>
        </select>-->
    <label>中转仓名称</label>
    <select name="transit" id="search-form-tc_code" onchange="initData(1)">
        <option value="">请选择</option>
        <?php
        foreach ($output['trans_list'] as $th) {
            echo '<option value="' . $th['tc_code'] . '"' . ($_GET['tc_code'] == $th['tc_code'] ? ' selected' : '') . '>' . $th['tc_name'] . "</option>";
        }
        ?>
    </select>
    <label>收件人名称</label>
    <input type="text" id="search-form-reciver_name" class="search_input" value="<?php echo $_GET['reciver_name'] ?>" onkeydown="search(event);"/>
    <label>境外快递单号</label>
    <input type="text" id="search-form-pre_track_no" class="search_input" value="<?php echo $_GET['pre_track_no'] ?>" onkeydown="search(event);"/>
    <label>物流单号</label>
    <input type="text" id="search-form-shipping_code" class="search_input" value="<?php echo $_GET['shipping_code'] ?>" onkeydown="search(event);"/>
    <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" tab-group="1"  onclick="changeTabs(20, this)" class="choose">待发出(<span id="tab20"><?php echo $output['state_counts'][20] ? $output['state_counts'][20] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="2"  onclick="changeTabs(25, this)">审核中(<span id="tab25"><?php echo $output['state_counts'][25] ? $output['state_counts'][25] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="3" onclick="changeTabs(30, this)">待入仓(<span id="tab30"><?php echo $output['state_counts'][30] ? $output['state_counts'][30] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="4" onclick="changeTabs(35, this)">待付款(<span id="tab35"><?php echo $output['state_counts'][35] ? $output['state_counts'][35] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="5" onclick="changeTabs(40, this)">已发货(<span id="tab40"><?php echo $output['state_counts'][40] ? $output['state_counts'][40] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="6" onclick="changeTabs(45, this)">已完成(<span id="tab45"><?php echo $output['state_counts'][45] ? $output['state_counts'][45] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="7" onclick="changeTabs(24, this)">审核失败(<span id="tab24"><?php echo $output['state_counts'][24] ? $output['state_counts'][24] : 0 ?></span>)</a></li>
            <li><a href="javascript:void(0);" tab-group="8" onclick="changeTabs(0, this)">全部</a></li>
        </ul>
    </div>

    <div class="operationsbox">
        <ul class="operNav">
            <li><a class="btn-enter" href="javascript:;" href="javascript:void(0);" id="create-div">添加订单<i class="ico-warehouse"></i></a></li>
            <li><a class="btn-enter" href="<?php echo urlShop('order_tp', 'import', array('type' => 'tp')) ?>"><i
                        class="ico-import-or"></i>导入</a></li>
            <li><a class="btn-enter" href="javascript:;" id="export" targat="_blank"><i class="ico-export-or"></i>导出</a></li>
            <li><a class="btn-enter" href="javascript:;" targat="_blank" onclick="fetchRemoteStatus()">更新订单状态</a></li>
            <li><a class="btn-enter" href="javascript:;" onclick="batch_sync()">批量同步</a></li>
        </ul>
        <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>

    <div class="pro-center-box">
        <!--    待发出-->
        <table id="testing1" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">是否上传身份证</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">境外快递公司</th>
                <th scope='col' nowrap="nowrap">境外快递单号</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!--    审核中-->
        <table id="testing2" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="2" class="order-table-box" style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
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
        <table id="testing3" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="3" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">境外快递公司</th>
                <th scope='col' nowrap="nowrap">境外快递单号</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 待付款 -->
        <table id="testing4"width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="4" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
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
        <table id="testing5" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="5" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
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
        <table id="testing6" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="6" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
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
        <!--    审核失败-->
        <table id="testing7" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="7" class="order-table-box" style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
                <th scope='col' nowrap="nowrap">收件人名称</th>
                <th scope='col' nowrap="nowrap">是否上传身份证</th>
                <th scope='col' nowrap="nowrap">申报价值(元)</th>
                <th scope='col' nowrap="nowrap">境外快递公司</th>
                <th scope='col' nowrap="nowrap">境外快递单号</th>
                <th scope='col' nowrap="nowrap">订单状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
        <!-- 全部 -->
        <table id="testing8" width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="8" class="order-table-box"
               style="display: none">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">物流单号</th>
                <th scope='col' nowrap="nowrap">客户订单号</th>
                <th scope='col' nowrap="nowrap">创建时间</th>
                <th scope='col' nowrap="nowrap">中转仓名称</th>
                <th scope='col' nowrap="nowrap">订单类型</th>
                <th scope='col' nowrap="nowrap">运输方式</th>
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

    <div id="pageSpace"></div>
</div>
<div class="pop-tabs test-slide">
    <div class="quick_links_wrap">
        <div class="pop-head-box clearfix">
            <div class="pop-head-title"><h3><i class="ico-increase"></i>新增</h3></div>
            <div class="pop-head-hot">
                <div class="hot-box">
                    <a href="javascript:;" onclick="psubmit()"><i class="ico-submit"></i>提交</a>
                    <a href="javascript:;" onclick="closeForm()"><i class="ico-back"></i>返回</a>
                </div>
                <a href="javascript:void(0)" class="pop-close">×</a>
            </div>
        </div>

    </div>
</div>
<div id="pageSpace"></div>
<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/order/order_tp.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        initData(1);
        $('#create-div').click(function () {
            showForm();
        });
        $('.pop-close').click(function () {
            closeForm();
        });
        $('.dropdown').mouseenter(function () {
            $(this).addClass('open');
        }).mouseleave(function () {
            $(this).removeClass('open');
        });
    });

    function closeForm() {
        $('.test-slide').removeClass('in');
        $('.order-boxWrap').removeClass('in');
    }

    function showForm(order_id) {
        //$('#loading-mask').show();
        $.ajax({
            url: SITE_SITE_URL + '/index.php?act=order_tp&op=getForm' + (order_id != undefined && '&order_id=' +
                    order_id || ''),
            type: 'get',
            dataType: 'html',
            success: function (res) {
                //$('#loading-mask').hide();
                $('.quick_links_wrap').children().not('.pop-head-box').remove();
                $('.pop-head-box').after(res);
                $('.test-slide').addClass('in');
                $(".order-boxWrap").addClass('in').css("height", "100%");
            },
            error: function () {
                //$('#loading-mask').hide();
                layer.alert('服务器繁忙,请稍后重试', {icon: 2});
            }
        });
    }
    function batch_sync() {
        layer.confirm('确定要执行此操作?', function (index) {
            var url = "<?php echo urlShop('order_tp','batch_sync')?>";
            var str = '';
            var index = layer.load();
            $.getJSON(url,function(res){
                layer.close(index);
                $('#loading-mask').hide();
                if(res.status){
                    str += res.msg+'，成功'+res.s_num+'条，失败'+res.f_num+'条！';
                    layer.alert(str, {icon: 1},function(index){
                        location.reload();
                        layer.close(index);
                    });
                }else{
                    layer.alert(res.msg,{icon:2});
                }
            });
        });
    }

</script>

<div class="box-wrap  order-boxWrap"></div>

<script src="templates/default/js/Popup/popup.js"></script>
