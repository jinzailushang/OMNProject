

<link href="<?php echo SITE_TEMPLATES_URL; ?>/js/select2/select2.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/select2/select2.min.js"></script>
<div class="btn-main" style="height: auto;">
    <input type="hidden" id="tab_id" name="tab_id" value="10" />
    <label>商品名称</label>
    <input type="text" id="search-name" class="search_input"  value="<?php echo $_GET['name'] ?>" onkeydown="search(event);" />
    <label>商品分类</label>
    <select name="cat_id" id="search-cat_id">
        <option value="">请选择</option>
        <?php foreach ($output['cat_list'] as $v):?>
            <option value="<?php echo $v['cat_id']?>" <?php if($_GET['cat_id'] == $v['cat_id']) echo 'selected'?>><?php echo $v['cat_name']?></option>
        <?php endforeach;?>
    </select>
    <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>


<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(10, this)" class="choose">商品列表</a></li>
        </ul>
    </div>
    <div class="operationsbox">
        <ul class="operNav">
            <li><a class="btn-enter"  href="javascript:addGoods();" id="create-div">添加 <i class="ico-warehouse"></i></a></li>
            <li><a class="btn-enter" href="<?php echo urlShop('goods', 'import') ?>"><i class="ico-import-or"></i>导入</a></li>
            <li><a class="btn-enter" href="javascript:exportGoods();" id="export" targat="_blank"><i class="ico-export-or"></i>导出</a></li>
        </ul>
        <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>
    <div class="pro-center-box" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">商品名称</th>
                <th scope='col' nowrap="nowrap">分类</th>
                <th scope='col' nowrap="nowrap">单位</th>
                <th scope='col' nowrap="nowrap">品牌</th>
                <th scope='col' nowrap="nowrap">价格(RMB)</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
    </div>
    <div id="pageSpace"></div>
</div>
<script type="text/js-tmpl" id="goods-edit-tmpl">
<link href="templates/default/css/addOrder.css" rel="stylesheet" type="text/css">
<style>
<!--.select2-container--default .select2-results>.select2-results__options {-->
<!--    max-height: 142px!important;-->
<!--    overflow-y: auto;-->
<!--}-->

#goods-edit-tmpl-container>div>label{
    width:60px;
    display:inline-block;
    float:left;
}
#goods-edit-tmpl-container>div>div{
    float:left:
}
#goods-edit-tmpl-container>div:after{
    clear:both;
    float:none;
    display:block;
    content:"";
}
#goods-edit-tmpl-container>div .textinput.width-250{
    width: 234px;
    border: 1px solid #aaa;
    border-radius: 4px;

}
#goods-edit-tmpl-container .shop-select {
  width: 25%;
}
#goods-edit-tmpl-container .add-qd {
    width: 58px;
    height: 30px;
    background-color: #004188;
    color: #f4faff;
    border-radius: 4px;
    font-size: 14px;
    margin-right: 10px;
    cursor: pointer;
    display: block;
    margin: 10px auto;
}
#goods-edit-tmpl-container .select2-container {
    width: 252px!important;
}
#goods-edit-tmpl-container {
    line-height: 300%;
    padding: 20px;
    width: 330px;
    margin: 0 auto;
}
</style>
  <input type="hidden" id="form-goods-id" value="%id%">
  <div id="goods-edit-tmpl-container">
  <div>
    <label>商品名称:</label>
    <span><input type="text" class="textinput width-250" id="form-goods-name" value="%name%"></span>
  </div>

  <div>
    <label>商品分类:</label>
    <span>
    <select class="js-example-basic-single textinput width-250" id="form-goods-cat_id">
        <option value="">请选择</option>
 <?php foreach ($output['cat_list'] as $c) {?>
          <option value="<?php echo $c['cat_id']?>"><?php echo $c['cat_name']?></option>
        <?php }?>
      </select>
      </span>
  </div>
  <div>
    <label>商品单位:</label>
    <span>
    <select class="js-example-basic-single textinput width-250" id="form-goods-unit_id">
        <option value="">请选择</option>
        <?php foreach ($output['unit_list'] as $u) {?>
          <option value="<?php echo $u['id']?>"><?php echo $u['measure_name_cn']?></option>
        <?php }?>
      </select>
      </span>
  </div>

  <div>
    <label>商品品牌:</label>
    <span><input type="text" class="textinput width-250" id="form-goods-brand" value="%brand%"></span>
  </div>
  <div>
    <label>商品价格:</label>
    <span><input type="text" class="textinput width-250" id="form-goods-price" value="%price%"></span>
  </div>
    <input type="button" value="确定" class="add-qd" onclick="saveGoods()">
  </div>
</script>
<script src="templates/default/js/order/IDCard.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        initData(1);
    });
    function initData(page) {
        $('#loading-mask').show();
        var url = '<?php echo SITE_SITE_URL ?>/index.php?act=goods&op=get_data';
        var name = getIdValue('search-name'),cat_id=$('#search-cat_id').val();

        $.ajax({
            url: url,
            data: {curpage: page,name:name,cat_id:cat_id},
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
                        html += '<td scope="row" align="center">' + r.name + '</td>';
                        html += '<td scope="row" align="center">' + r.cat_name + '</td>';
                        html += '<td scope="row" align="center">' + r.measure_name_cn + '</td>';
                        html += '<td scope="row" align="center">' + r.brand + '</td>';
                        html += '<td scope="row" align="center">' + r.price + '</td>';
                        html += '<td scope="row" align="center"><div class="jt_select_div">' +
                            ' <div class="jt_select_show_val">操作</div>' +
                            '   <span class="jt_select_icon ico"></span>' +
                            '   <div class="jt_select_option_div">' +
                            '     <ul class="jt_select_option_ul">' +
                            '       <li><a href="javascript:;" onclick="editGoods(\''+JSON.stringify(r).split('"').join(
                                '__-__')+'\')">编辑</a></li>' +
                            '       <li><a href="javascript:;" onclick="deleteGoods('+ r.id+')">删除</a></li>' +
                            '     </ul>' +
                            '   </div>' +
                            ' </div></td>';
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
                bindTableAction();
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
    function editGoods(data) {
        data = JSON.parse(data.split('__-__').join('"'));
        layer.open({
            type: 1,
            //skin: 'layui-layer-molv',
            title: '编辑商品',
            fix: false,
            maxmin: false,
            //shift: 4, //动画
            area: ['420px', '320px'],
            shadeClose: true, //点击遮罩关闭
            content: (function(html){
                for(var i in data) {
                    var d = data[i];
                    html = html.split("%"+i+"%").join(d);
                }
                
                return html;
            })($('#goods-edit-tmpl').html())
        });
        window.setTimeout(function(){
            $('#form-goods-cat_id').val(data.cat_id);
            $('#form-goods-unit_id').val(data.unit_id);
        },10);
        //   支持输入匹配
        $(".js-example-basic-single ").select2();
    }
    function addGoods() {
        layer.open({
            type: 1,
            //skin: 'layui-layer-molv',
            title: '添加商品',
            fix: false,
            maxmin: false,
            //shift: 4, //动画
            area: ['420px', '320px'],
            shadeClose: false, //点击遮罩关闭
            content: (function(html){
                return html.replace('%id%','').replace('%name%','').replace('%brand%','').replace('%price%','');
            })($('#goods-edit-tmpl').html())
        });
        window.setTimeout(function(){
        },10);
        //   支持输入匹配
        $(".js-example-basic-single ").select2();
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
    function showPic(id) {
        var reader = new FileReader();
        reader.addEventListener("load", function () {
            $.ajax({
                url: SITE_SITE_URL + '/index.php?act=order_tp&op=uploadIdCard',
                data: {file: reader.result},
                type: 'post',
                dataType: 'text',
                success: function (res) {
                    document.getElementById(id + '_img').src = res;
                }
            });
        }, false);

        reader.readAsDataURL(document.getElementById(id).files[0]);
        //    return;

        //    var posturl = "<?php //echo urlShop('order','load_card')?>//";
        //    $('#'+id).attr("action", posturl).submit();
    }

    function saveGoods() {
        var errors = [],data = {
            id:getIdValue('form-goods-id'),
            name:getIdValue('form-goods-name'),
            cat_id: getIdValue('form-goods-cat_id'),
            unit_id: getIdValue('form-goods-unit_id'),
            brand: getIdValue('form-goods-brand'),
            price: getIdValue('form-goods-price')
        };

        if (!data.name) {
            errors.push('商品名称不能为空');
        }
        if (!data.cat_id) {
            errors.push('分类不能为空');
        }
        if (!data.unit_id) {
            errors.push('单位不能为空');
        }
        if (!data.brand) {
            errors.push('品牌不能为空');
        }
        if (!data.price) {
            errors.push('价格不能为空');
        }
        if (isNaN(data.price) || data.price < 0.01) {
            errors.push('价格格式有误');
        }
        if (errors.length>0) {
            if (errors) {
                layer.alert(errors.join('<br>'), {icon:2});

                return false;
            }
        }
        if (errors.length==0) {
            layer.closeAll()
        }

        $('#loading-mask').show();
        $.ajax({
            url: SITE_SITE_URL + '/index.php?act=goods&op=saveGoods',
            data: data,
            type: 'post',
            dataType: 'json',
            success:function(res) {
                $('#loading-mask').hide();
                if (res.status) {
                    layer.alert(res.msg, {icon:1}, function (index) {
                        layer.close(index);
                        //$('.test-slide').removeClass('in');
                        initData(1);
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
    function exportGoods() {
        window.location.href=SITE_SITE_URL+"/index.php?act=goods&op=export";
    }
    function deleteGoods(id) {
        layer.confirm('此操作不可恢复,确定要删除吗?', {icon: 3, title:'提示'}, function(index){
            $('#loading-mask').show();
            $.ajax({
                url: SITE_SITE_URL + '/index.php?act=goods&op=deleteGoods',
                data: {id:id},
                type: 'post',
                dataType: 'json',
                success:function(res) {
                    $('#loading-mask').hide();
                    if (res.status) {
                        layer.alert(res.msg, {icon:1}, function (index) {
                            layer.close(index);
                            //$('.test-slide').removeClass('in');
                            initData(1);
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
            layer.close(index);
        });
    }
</script>
