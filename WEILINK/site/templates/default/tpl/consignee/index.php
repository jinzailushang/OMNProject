<div class="btn-main" style="height: auto;">
    <input type="hidden" id="tab_id" name="tab_id" value="10" />
    <label>收件人名称</label>
    <input type="text" id="name" class="search_input"  value="<?php echo $_GET['name'] ?>" onkeydown="search(event);" />
    <label>收件人电话</label>
    <input type="text" id="phone" class="search_input"  value="<?php echo $_GET['phone'] ?>" onkeydown="search(event);" />
    <label>地区</label>
    <select name="province" id="province" onchange="show_sub(this,0)">
        <option value="">请选择</option>
        <?php foreach ($output['pro_list'] as $v):?>
            <option value="<?php echo $v['area_id']?>" <?php if($_GET['province'] == $v['area_id']) echo 'selected'?>><?php echo $v['area_name']?></option>
        <?php endforeach;?>
    </select>
    <input type="button" class="button" value=" 查询  " onclick="initData(1)"/>
</div>
<div class="clear"></div>

<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="" onclick="changeTabs(10, this)" class="choose">收件人列表</a></li>
        </ul>
    </div>
    <div class="operationsbox">
      <ul class="operNav">
        <li><a class="btn-enter"  href="javascript:addConsignee();" id="create-div">添加 <i class="ico-warehouse"></i></a></li>
        <li><a class="btn-enter" href="<?php echo urlShop('consignee', 'import') ?>"><i
              class="ico-import-or"></i>导入</a></li>
        <li><a class="btn-enter" href="javascript:exportConsignee();" id="export" targat="_blank"><i class="ico-export-or"></i>导出</a></li>
      </ul>
        <a href="#" class="toPageNum">共<span id="count_data"></span>条记录</a>
    </div>
    <div class="pro-center-box" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="order-table-box">
            <tr class="order-th">
                <th scope='col' nowrap="nowrap">收件人姓名</th>
                <th scope='col' nowrap="nowrap">手机号码</th>

                <th scope='col' nowrap="nowrap">身份证</th>
                <th scope='col' nowrap="nowrap">是否上传身份证</th>
                <th scope='col' nowrap="nowrap">所在地区</th>

<!--                <th scope='col' nowrap="nowrap">省份</th>-->
<!--                <th scope='col' nowrap="nowrap">城市</th>-->
                <th scope='col' nowrap="nowrap">地址</th>
                <th scope='col' nowrap="nowrap">邮编</th>

                <th scope='col' nowrap="nowrap">状态</th>
                <th scope='col' nowrap="nowrap">操作</th>
            </tr>
        </table>
    </div>
    
    <div id="pageSpace"></div>
</div>
<script type="text/js-tmpl" id="consignee-detail-tmpl">
    <div class="infor-table">
    <div style="padding-left: 20px; height: 30px;
    line-height: 30px;
    border: 1px solid #ededed;">运单明细</div>
        <div style="line-height:300%; padding-left: 20px;">

            <div>
                <label>收件人姓名:</label>
    <span>%name%</label>
            </div>
            <div>
                <label>收件人电话:</label>
    <span>%phone%</label>
            </div>
            <div>
                <label>收件人身份证号:</label>
    <span>%ID%</label>
            </div>
            <div>
                <label>收件人身份证照片:</label>
    <span><img src="%ID_front%" width="250"> <img src="%ID_back%" width="250" style="margin-left:20px"></label>
            </div>
            <div>
                <label>收件人所属地区:</label>
    <span>%province%%city%%area%</label>
            </div>
            <div>
                <label>收件人详细地址:</label>
    <span>%address%</label>
            </div>
            <div>
                <label>收件人邮编:</label>
    <span>%zipcode%</label>
            </div>
            <div>
                <label>状态:</label>
    <span>%is_default%</label>
            </div>
        </div>
    </div>
</script>
<script type="text/js-tmpl" id="consignee-edit-tmpl">
<link href="templates/default/css/addOrder.css" rel="stylesheet" type="text/css">
<style>
#consignee-edit-tmpl-container {
    line-height: 300%;
    padding: 20px；;
    width: 600px;
    margin:20px auto;
}
#consignee-edit-tmpl-container>div>label{
    width:110px;
    display:inline-block;
    float:left;
    text-align: right;
}
#consignee-edit-tmpl-container>div>div{
    float:left:
}
#consignee-edit-tmpl-container>div:after{
    clear:both;
    float:none;
    display:block;
    content:"";
}

#consignee-edit-tmpl-container>div .textinput.width-250{
        width: 398px;
}
#consignee-edit-tmpl-container .shop-select {
  width: 25%;
}
#edit-receiver-con {
    width: 58px;
    height: 30px;
    background-color: #004188;
    color: #f4faff;
    border-radius: 4px;
    font-size: 14px;
    margin-right: 10px;
    cursor: pointer;
    margin: 0 auto;
    display: block;
}
#ed-recipients .control-input {
    height: 24px;
    line-height: 24px;
    padding: 2px 8px;
    border: 1px solid #dfdfdf;
}
</style>
  <input type="hidden" id="form-consignee-id" value="%cid%">
  <div style="line-height:300%; padding:20px；" id="consignee-edit-tmpl-container">
  <div>
    <label>收件人姓名:</label>
    <span><input type="text" class="textinput width-250" id="form-consignee-name" value="%name%"></span>
  </div>
  <div>
    <label>收件人电话:</label>
    <span><input type="text" class="textinput width-250" id="form-consignee-phone" value="%phone%"></span>
  </div>
  <div>
    <label>收件人身份证号:</label>
    <span><input type="text" class="textinput width-250" id="form-consignee-ID" value="%ID%"></span>
  </div>
  <div>
    <label>收件人身份证照片:</label>
    <div class="control-l" style="margin-left:0;max-height: 180px;">
      <div class="sf">
        <img src="%ID_front%" onerror='this.src="templates/default/images/sfz.png"' id="id_card_front_img">
      </div>
      <p style="margin-top:0; margin-bottom:34px;">上传身份证正面照片</p>
      <input type="file" value="" name="id_card_front" id="id_card_front" class="rowcol sf-fi"
             onchange="showPic('id_card_front')">
    </div>
    <div class="control-l" style="margin-left:5px;max-height: 180px;">
      <div class="sf">
        <img src="%ID_back%" onerror='src="templates/default/images/sff.png"' id="id_card_back_img">
      </div>
         <p style="margin-top:0; margin-bottom:34px;">上传身份证反面照片</p>
      <input type="file" value="" name="id_card_back" id="id_card_back" class="rowcol sf-fi"
             onchange="showPic('id_card_back')">
    </div>
  </div>
  <div id="ed-recipients">
    <label>收件人所属地区:</label>
    <div class="controls">
      <div class="dropdown shop-select arealist">
        <a class="selectui-result dropdown-toggle"><input type="text" class="textinput" id="provincer"
                                                          name="provincer"
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
  <div>
    <label>收件人详细地址:</label>
    <span><input type="text" class="textinput width-250" id="form-consignee-address" value="%address%"></span>
  </div>
  <div>
    <label>收件人邮编:</label>
    <span><input type="text" class="textinput width-250" id="form-consignee-zipcode" value="%zipcode%"></span>
  </div>
  <div>
    <label>状态:</label>
    <label><input type="checkbox" value="Y" id="form-consignee-is_default"%is_default%> 设为默认收件人</label>
  </div>
  <div>
    <input type="button" value="确定" id="edit-receiver-con" onclick="saveConsignee()">
   </div>
  </div>
</script>
<script src="templates/default/js/order/IDCard.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        initData(1);
    });
    function initData(page) {
        $('#loading-mask').show();
        var url = '<?php echo SITE_SITE_URL ?>/index.php?act=consignee&op=get_data';
        var name = getIdValue('name'),phone=getIdValue('phone'),province=$('#province option:selected').text(),city=$('#city option:selected').text();

        $.ajax({
            url: url,
            data: {curpage: page,name:name,phone:phone,province:province,city:city},
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
                        html += '<td scope="row" align="center">' + r.phone + '</td>';
                        html += '<td scope="row" align="center">' + r.ID + '</td>';
                        html += '<td scope="row" align="center">' + (r.ID&&'已上传'||'<font color=orange>未上传</font>') + '</td>';
                        html += '<td scope="row" align="center">' + r.province+ r.city+ r.area + '</td>';
                        html += '<td scope="row" align="center">' + r.address + '</td>';
                        html += '<td scope="row" align="center">' + r.zipcode + '</td>';
                        html += '<td scope="row" align="center">' + (r.is_default=='Y'&&'默认收件人'||'普通') + '</td>';
                        html += '<td scope="row" align="center"><div class="jt_select_div">' +
                            ' <div class="jt_select_show_val">操作</div>' +
                            '   <span class="jt_select_icon ico"></span>' +
                            '   <div class="jt_select_option_div">' +
                            '     <ul class="jt_select_option_ul">' +
                            '       <li><a href="javascript:;" onclick="showDetail(\''+JSON.stringify(r).split('"').join(
                                '__-__')+'\')' +
                            '">查看详情</a></li>' +
                            '       <li><a href="javascript:;" onclick="setDefault('+ r.cid+',\''+(r.is_default=='Y'&&'N'||'Y')+'\')">'+(r.is_default=='Y'&&'取消'||'设为')+'默认</a></li>' +
                            '       <li><a href="javascript:;" onclick="editConsignee(\''+JSON.stringify(r).split('"').join(
                                '__-__')+'\')">编辑</a></li>' +
                            '       <li><a href="javascript:;" onclick="deleteConsignee('+ r.cid+')">删除</a></li>' +
                            '     </ul>' +
                            '   </div>' +
                            ' </div></td>';
                          /*' <div class="jt_select_show_val">操作</div>' +
                          '   <span class="jt_select_icon ico"></span>' +
                          '   <div class="jt_select_option_div">' +
                          '     <ul class="jt_select_option_ul">' +
                          '       <li><a href="javascript:;" onclick="showDetail(\''+JSON.stringify(r).split('"').join(
                            '__-__')+'\')' +
                        '">查看详情</a></li>' +
                          '       <li><a href="javascript:;" onclick="setDefault('+ r.cid+',\''+(r.is_default=='Y'&&'N'||'Y')+')"\'>'+(r.is_default=='Y'&&'取消'||'设为')+'默认</a></li>' +
                          '       <li><a href="javascript:;" onclick="editConsignee(\''+JSON.stringify(r).split('"').join(
                            '__-__')+'\')">编辑</a></li>' +
                          '       <li><a href="javascript:;" onclick="deleteConsignee('+ r.cid+')">删除</a></li>' +
                          '     </ul>' +
                          '   </div>' +
                          ' </div></td>';*/
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
    function showDetail(data) {
        data = JSON.parse(data.split('__-__').join('"'));
        layer.open({
            type: 1,
            //skin: 'layui-layer-molv',
            title: '查看详情',
            fix: false,
            maxmin: false,
            //shift: 4, //动画
            area: ['710px', '550px'],
            shadeClose: true, //点击遮罩关闭
            content: (function(html){
                for(var i in data) {
                    var d = data[i];
                    if (i == 'is_default') {
                        d = d=='Y'&&'默认收件人'||'普通';
                    }
                    html = html.split("%"+i+"%").join(d);
                }
                return html;
            })($('#consignee-detail-tmpl').html())
        });
    }
    function setDefault(cid, flag) {
        $('#loading-mask').show();
        $.ajax({
            url: '<?php echo SITE_SITE_URL ?>/index.php?act=consignee&op=setDefault',
            type: 'post',
            data:{cid:cid,flag:flag},
            dataType: 'json',
            success: function(res) {
                $('#loading-mask').hide();
                if (res.status) {
                    layer.alert(res.msg, {icon: 1}, function () {
                        layer.closeAll();
                        initData(1);
                    });
                } else {
                    layer.alert(res.msg, {icon: 2}, function () {
                        layer.closeAll();
                    });
                }
            },
            error: function() {
                $('#loading-mask').hide();
            }
        });
    }
    function editConsignee(data) {
        data = JSON.parse(data.split('__-__').join('"'));
        layer.open({
            type: 1,
            //skin: 'layui-layer-molv',
            title: '编辑收件人',
            fix: false,
            maxmin: false,
            //shift: 4, //动画
            area: ['680px', '575px'],
            shadeClose: true, //点击遮罩关闭
            content: (function(html){
                for(var i in data) {
                    var d = data[i];
                    if (i == 'is_default') {
                        d = d=='Y'&&' checked'||'';
                    }
                    html = html.split("%"+i+"%").join(d);
                }
                return html;
            })($('#consignee-edit-tmpl').html())
        });
        window.setTimeout(function(){
            $('#provincer').data('origin',data.province+','+data.city+','+data.area);
            initReciverRegion();
        },10);
    }
    function addConsignee() {
      layer.open({
        type: 1,
        //skin: 'layui-layer-molv',
        title: '添加收件人',
        fix: false,
        maxmin: false,
        //shift: 4, //动画
        area: ['680px', '575px'],
        shadeClose: false, //点击遮罩关闭
        content: (function(html){
          return html.replace(/%[a-z_]+%/ig,'');
        })($('#consignee-edit-tmpl').html())
      });
      window.setTimeout(function(){
      },10);
      //   支持输入匹配
      $(".js-example-basic-single ").select2();
    }

    function initReciverRegion() {
        var receiver_origin = $('#provincer').data('origin');
        if (receiver_origin!=',,') {
            $("#provincer").parent().next('ul').find("li:contains('"+receiver_origin.split(',')[0]+"')").trigger('mousedown');
        }
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


    function saveConsignee() {
        var errors = [],data = {
            cid:getIdValue('form-consignee-id'),
            name:getIdValue('form-consignee-name'),
            province: getIdValue('provincer'),
            city: getIdValue('cityr'),
            area: getIdValue('arear'),
            address: getIdValue('form-consignee-address'),
            zipcode: getIdValue('form-consignee-zipcode'),
            phone: getIdValue('form-consignee-phone'),
            ID: getIdValue('form-consignee-ID'),
            ID_front: $('#id_card_front_img').attr('src') != 'templates/default/images/sfz.png' && $('#id_card_front_img').attr('src') || '',
            ID_back: $('#id_card_back_img').attr('src') != 'templates/default/images/sff.png' && $('#id_card_back_img').attr('src') || '',
            is_default: $('#form-consignee-is_default:checked').val()?'Y':'N'
        };

        if (!data.name) {
            errors.push('收件人姓名不能为空');
        }
        if (!data.province) {
            errors.push('收件人省份不能为空');
        }
        if (!data.city) {
            errors.push('收件人城市不能为空');
        }
        if (!data.area) {
            errors.push('收件人所属地区不能为空');
        }
        if (!data.address) {
            errors.push('收件人详细地址不能为空');
        }
        if (!data.zipcode) {
            errors.push('收件人邮编不能为空');
        }
        if (!data.phone) {
            errors.push('收件人电话不能为空');
        }
        if (!data.ID) {
            errors.push('收件人身份证号不能为空');
        }
        if (!data.ID_front) {
            errors.push('身份证正面照片不能为空');
        }
        if (!data.ID_back) {
            errors.push('身份证背面照片不能为空');
        }
        if (!/^\d{17}[\dx]$/i.test(data.ID) || !(new IDValidator()).isValid(data.ID)) {
            errors.push('收件人身份证号错误，请重新输入');
        }
        if (errors.length>0) {
            if (errors) {
                layer.alert(errors.join('<br>'), {icon:2});
                return false;
            }
        }

    $('#loading-mask').show();
    $.ajax({
      url: SITE_SITE_URL + '/index.php?act=consignee&op=saveConsignee',
      data: data,
      type: 'post',
      dataType: 'json',
      success:function(res) {
        $('#loading-mask').hide();
        if (res.status) {
          layer.alert(res.msg, {icon:1}, function (index) {
            layer.closeAll();
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
    function exportConsignee() {
      window.location.href=SITE_SITE_URL+"/index.php?act=consignee&op=export";
    }

    function deleteConsignee(cid) {
      layer.confirm('此操作不可恢复,确定要删除吗?', {icon: 3, title:'提示'}, function(index){
        $('#loading-mask').show();
        $.ajax({
          url: SITE_SITE_URL + '/index.php?act=consignee&op=deleteConsignee',
          data: {cid:cid},
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
