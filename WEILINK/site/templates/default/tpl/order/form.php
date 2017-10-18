
<link href="<?php echo SITE_TEMPLATES_URL; ?>/js/select2/select2.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/select2/select2.min.js"></script>
<form id="add_form" method="post" action="<?php echo urlShop('order_tp', 'save_order') ?>">
  <input type="hidden" id="order-form-order-id" value="<?php echo $_GET['order_id']?>">
  <div class="detail-content scrollwrapper mCustomScrollbar">
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
                    <div class="control-label"><em class="col-red">*</em>中转仓：</div>
                    <div class="controls">
                      <div class="dropdown shop-select arealist">
                        <a class="selectui-result dropdown-toggle">
                          <input type="text" id="transh" name="transh" class="control-input" value="" autocomplete="off" placeholder="请选择" onfocus="showAndHide('transh_l', 'show');" onblur="showAndHide('transh_l', 'hide');">
                          <i class="selectIcon"></i>
                        </a>
                        <ul id="transh_l" class="dropdown-menu border-dropdown w-90">
                          <?php foreach($output['trans_list'] as $th) {?>
                            <li class="j_company" data-code="<?php echo $th['tc_code']?>" onmousedown="getVal('transh', '<?php echo $th['tc_name']?>');showAndHide('transh_l', 'hide');selectTransCountry(<?php echo $th['tid']?>,'')">
                              <a><?php echo $th['tc_name']?></a>
                            </li>
                          <?php }?>
                          <!-- li class="j_company"
                              onmousedown="getVal('transh', 'JP');showAndHide('transh_l', 'hide');fill_tc(1)"><a>JP</a>
                          </li -->
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="controls" style="margin-left:0;" id="tcid">
                      <input type="hidden" id="tid" value="">
                      <input type="hidden" id="tc_code" value="">
                      <p>收货人（Name）：<span></span></p>
                      <p>地址（Address1）：<span></span></p>
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
                      <a class="document">运输方式</a>
                    </li>
                  </ul>
                </div>
                  <?php //print_r($output['channel'])?>
                <div class="control-group">
                  <div id="participants-component">
                      <div class="control-label"><em class="col-red">*</em>运输方式：</div><input type="hidden" id="ship_method" name="ship_method" value="" />
                    <div class="controls">
                      <div class="dropdown shop-select arealist">
                        <a class="selectui-result dropdown-toggle">
                            <input type="text" id="ship_method_name" autocomplete="off" name="ship_method_name" class="control-input" value="" placeholder="请选择" onfocus="showAndHide('ship_method_l', 'show');" onblur="showAndHide('ship_method_l', 'hide');"><i class="selectIcon"></i></a>
                        <ul id="ship_method_l" class="dropdown-menu border-dropdown w-90">
                          <?php foreach($output['channels'] as $row) {?>
                          <li class="j_company" onmousedown="getVal('ship_method_name', '<?php echo $row['channel_name']?>');showAndHide('ship_method_l', 'hide');selectTransCountry($('#tid').val(),'<?php echo $row['channel_code']?>');"><a><?php echo $row['channel_name']?></a></li>
                          <?php }?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                  <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label" id="fee_type">标准服务收费：</div>
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
                        <a class="selectui-result dropdown-toggle"><input type="text" id="express" autocomplete="off" name="express"
                                                                          class="control-input" value=""
                                                                          placeholder="请选择"
                                                                          onfocus="showAndHide('express_l', 'show');"
                                                                          onblur="showAndHide('express_l', 'hide');"><i
                              class="selectIcon"></i></a>
                        <ul id="express_l" class="dropdown-menu border-dropdown w-90">
                          <li class="j_company" onmousedown="getVal('express', 'UPS');
                                                                        showAndHide('express_l', 'hide');"><a>UPS</a>
                          </li>
                          <li class="j_company" onmousedown="getVal('express', 'DHL');
                                                                        showAndHide('express_l', 'hide');"><a>DHL</a>
                          </li>
                          <li class="j_company" onmousedown="getVal('express', 'FEDEX');
                                                                        showAndHide('express_l', 'hide');"><a>FEDEX</a>
                          </li>
                          <li class="j_company" onmousedown="getVal('express', 'TNT');
                                                                        showAndHide('express_l', 'hide');"><a>TNT</a>
                          </li>
                          <li class="j_company" onmousedown="getVal('express', '其他物流公司');
                                                                        showAndHide('express_l', 'hide');"><a>其他物流公司</a>
                          </li>
                        </ul>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red">*</em>快递单号：</div>
                    <div class="controls">
                      <input type="text" class="textinput" autocomplete="off" name="express_no" id="express_no" value="<?php echo
                      $output['order_info']['pre_track_no']?>" placeholder="">
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label">备注：</div>
                    <div class="controls">
                      <input type="text" class="textinput" name="remark" autocomplete="off" id="remark" value="<?php echo $output['order_info']['remark']?>" placeholder="">
                    </div>
                  </div>
                </div>
              </div>
              <!--增值服务-->
              <div class="detail-block">
                <div class="detail-tab clearfix">
                  <ul>
                    <li class="extend-panel-toggle active">
                      <a class="document">增值服务</a>
                    </li>
                  </ul>
                </div>
                <div class="addSecvi-container">
                  <div class="addSecvi">
                    <ul class="addSecvi-w">
                      <?php $currentKey = '';
                      $index = 0;
                      foreach ($output['extra_service_list'] as $esk => $esv) {
                      list($key, $val) = explode(':', $esk);
                      $addContainer = false;
                      if ($currentKey != $key && $index) {
                      ?>
                    </ul>
                  </div>
                  <div class="addSecvi">
                    <ul class="addSecvi-w">
                      <?php } ?>
                      <li data-key="<?php echo $key?>" data-val="<?php echo $val?>" class="addSecvi-z addSecvi-li <?php if ($output['order_info'][$key]==$val) echo 'addSecvi-select'?>" data-value="<?php echo $esv['fee'] ?>"
                          onclick="$(this).parent().children().not($(this)).removeClass('addSecvi-select').addClass('addSecvi-li');$(this).toggleClass('addSecvi-select')"><?php echo $esv['text'] ?>
                        <span class="addSecvi-S"><font><?php echo $esv['fee'] ?></font>
                          元<?php echo ($esv['unit'] ? '/' : '') . $esv['unit'] ?></span></li>
                      <?php $index++;
                      $currentKey = $key;
                      } ?>
                    </ul>
                  </div>

                </div>
                <div class="addUp">
                  合计增值费：<span id="addServiceSum"></span>
                </div>
              </div>
              <!--收货信息-->
              <div class="detail-block">
                <div class="detail-tab clearfix">
                  <ul>
                    <li class="extend-panel-toggle active">
                      <a class="document">收货信息</a>
                      <input type="hidden" id="order-form-consignee-id" value="<?php echo
                      $output['order_info']['consignee_id']?>">
                    </li>
                    <li class="select-prod-fr">
                      <a class="add-rec" id="btn-select" onclick="">选择收件人</a>
                    </li>
                  </ul>
                </div>
<!--                <div class="control-group">-->
<!--                  <span class="add-rec" id="btn-select" onclick="">选择收件人</span>-->
<!--                </div>-->
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red">*</em>收件人名称：</div>
                    <div class="controls">
                      <input type="text" name="reciver_name" id="reciver_name" autocomplete="off" class="textinput" value="<?php
                      echo $output['order_info']['reciver_name']?>"
                             placeholder="">
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red">*</em>收件人地区：</div>
                    <div class="controls">
                      <div class="dropdown shop-select arealist">
                        <a class="selectui-result dropdown-toggle"><input type="text" id="provincer" autocomplete="off"
                                                                          data-origin="<?php echo
                                                                          $output['order_info']['reciver_state'].','.$output['order_info']['reciver_city'].','.$output['order_info']['reciver_area']?>"
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
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red">*</em>收件人地址：</div>
                    <div class="controls">
                      <input type="text" name="reciver_address" autocomplete="off" id="reciver_address" class="textinput" value="<?php
                      echo $output['order_info']['reciver_address']?>" placeholder="">
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red">*</em>收件人邮编：</div>
                    <div class="controls">
                      <input type="text" name="reciver_zipcode" autocomplete="off" id="reciver_zipcode" class="textinput" value="<?php
                      echo $output['order_info']['reciver_zipcode']?>" placeholder="">
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red">*</em>收件人电话：</div>
                    <div class="controls">
                      <input type="text" name="reciver_phone" autocomplete="off" id="reciver_phone" class="textinput" value="<?php
                      echo $output['order_info']['reciver_phone']?>"
                             placeholder="">
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red" id="idcardp1">*</em>身份证号码：</div>
                    <div class="controls">
                      <input type="text" name="id_card_number" id="id_card_number" autocomplete="off" class="textinput" value="<?php
                      echo $output['order_info']['identity_code']?>"
                             placeholder="">
                    </div>
                  </div>
                </div>
                <div class="control-group">
                  <div id="participants-component">
                    <div class="control-label"><em class="col-red" id="idcardp2">*</em>身份证照：</div>
                    <div style="overflow: hidden">
                      <div class="control-l">

                        <div class="sf">
                          <img src="<?php
                          echo $output['order_info']['identity_code']? $output['order_info']['id_card_front']:'templates/default/images/sfz.png'?>" id="id_card_front_img" onerror="this.src='templates/default/images/sfz.png'">
                        </div>
                        <p>上传身份证正面照片</p>
                        <input type="file" value="" name="id_card_front" id="id_card_front" class="rowcol sf-fi"
                               onchange="showPic('id_card_front')">
                      </div>
                      <div class="control-l">
                        <div class="sf">
                          <img src="<?php
                          echo $output['order_info']['identity_code']? $output['order_info']['id_card_back']:'templates/default/images/sff.png'?>" id="id_card_back_img" onerror="this.src='templates/default/images/sff.png'">
                        </div>
                        <p>上传身份证反面照片</p>
                        <input type="file" value="" name="id_card_back" id="id_card_back" class="rowcol sf-fi"
                               onchange="showPic('id_card_back')">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php if (!$output['user_info'] || !$output['user_info']['phone']) {?>
                <div class="detail-block">
                  <div class="detail-tab clearfix">
                    <ul>
                      <li class="extend-panel-toggle active">
                        <a class="document">发货人信息</a>
                      </li>
                    </ul>
                  </div>
                  <div class="control-group">
                    <div id="participants-component">
                      <div class="control-label"><em class="col-red">*</em>发货人名称：</div>
                      <div class="controls">
                        <input type="text" autocomplete="off" name="sender_name" id="sender_name" class="textinput" value=""
                               placeholder="">
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <div id="participants-component">
                      <div class="control-label"><em class="col-red">*</em>发货人地区：</div>
                      <div class="controls">
                        <div class="dropdown shop-select arealist">
                          <a class="selectui-result dropdown-toggle"><input type="text" id="provinces" name="provinces"
                                                                            class="control-input" value="" autocomplete="off"
                                                                            placeholder="请选择"
                                                                            onfocus="showAndHide('List7', 'show');"
                                                                            onblur="showAndHide('List7', 'hide');"><i
                                class="selectIcon"></i></a>
                          <ul id="List7" class="dropdown-menu border-dropdown w-90" style="display: none;">
                            <li class="j_company" onmousedown="getVal('provinces', '北京市');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,1, 0, 's')"><a>北京市</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '天津市');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,2, 0, 's')"><a>天津市</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '河北省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,3, 0, 's')"><a>河北省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '山西省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,4, 0, 's')"><a>山西省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '内蒙古自治区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,5, 0, 's')"><a>内蒙古自治区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '辽宁省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,6, 0, 's')"><a>辽宁省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '吉林省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,7, 0, 's')"><a>吉林省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '黑龙江省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,8, 0, 's')"><a>黑龙江省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '上海市');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,9, 0, 's')"><a>上海市</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '江苏省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,10, 0, 's')"><a>江苏省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '浙江省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,11, 0, 's')"><a>浙江省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '安徽省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,12, 0, 's')"><a>安徽省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '福建省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,13, 0, 's')"><a>福建省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '江西省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,14, 0, 's')"><a>江西省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '山东省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,15, 0, 's')"><a>山东省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '河南省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,16, 0, 's')"><a>河南省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '湖北省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,17, 0, 's')"><a>湖北省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '湖南省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,18, 0, 's')"><a>湖南省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '广东省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,19, 0, 's')"><a>广东省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '广西壮族自治区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,20, 0, 's')"><a>广西壮族自治区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '海南省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,21, 0, 's')"><a>海南省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '重庆市');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,22, 0, 's')"><a>重庆市</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '四川省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,23, 0, 's')"><a>四川省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '贵州省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,24, 0, 's')"><a>贵州省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '云南省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,25, 0, 's')"><a>云南省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '西藏自治区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,26, 0, 's')"><a>西藏自治区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '陕西省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,27, 0, 's')"><a>陕西省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '甘肃省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,28, 0, 's')"><a>甘肃省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '青海省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,29, 0, 's')"><a>青海省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '宁夏回族自治区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,30, 0, 's')"><a>宁夏回族自治区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '新疆维吾尔自治区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,31, 0, 's')"><a>新疆维吾尔自治区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '台湾省');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,32, 0, 's')"><a>台湾省</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '香港特别行政区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,33, 0, 's')"><a>香港特别行政区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '澳门特别行政区');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,34, 0, 's')"><a>澳门特别行政区</a></li>
                            <li class="j_company" onmousedown="getVal('provinces', '海外');
                                                                            showAndHide('List7', 'hide');
                                                                            sub(this,35, 0, 's')"><a>海外</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <div id="participants-component">
                      <div class="control-label"><em class="col-red">*</em>发货人地址：</div>
                      <div class="controls">
                        <input type="text" name="sender_address" autocomplete="off" id="sender_address" class="textinput" value=""
                               placeholder="">
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <div id="participants-component">
                      <div class="control-label"><em class="col-red">*</em>发货人邮编：</div>
                      <div class="controls">
                        <input type="text" name="sender_zipcode" autocomplete="off" id="sender_zipcode" class="textinput" value=""
                               placeholder="">
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <div id="participants-component">
                      <div class="control-label"><em class="col-red">*</em>发货人电话：</div>
                      <div class="controls">
                        <input type="text" name="sender_phone" autocomplete="off" id="sender_phone" class="textinput" value=""
                               placeholder="">
                      </div>
                    </div>
                  </div>

                </div>
              <?php }?>
              <div class="detail-block goods_block">
                <div class="detail-tab clearfix">
                  <ul>
                    <li class="extend-panel-toggle active">
                      <!--                      <a class="document" onclick="mulgoods()"> 添加商品</a>-->
                      <a class="document" onclick="return false;"> 添加商品</a>
                    </li>
                    <li class="select-prod-fr">
                      <a class="select-btn" id="button-sel" onclick="showProductList()">选择商品</a>
                    </li>
                  </ul>
                </div>
                <!--                <div class="select-product">-->
                <!--                  <span class="select-btn" id="button-sel" onclick="showProductList()">选择商品</span>-->
                <!--                </div>-->
                <?php if ($output['goods_list']) {
  echo <<<EOF
<table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-bo consignee-box" style="width:auto;border-left:0;border-right:0;border-bottom:0">
      <tbody>
      <tr class="order-th s_oushuhang">
      <th style="width:19%" scope="col" nowrap="nowrap">商品名称</th>
      <th style="width:19%" scope="col" nowrap="nowrap">分类</th>
      <th style="width:19%" scope="col" nowrap="nowrap">单位</th>
      <th style="width:19%" scope="col" nowrap="nowrap">品牌</th>
      <th style="width:19%" scope="col" nowrap="nowrap">单价</th>
      <th style="width:19%" scope="col" nowrap="nowrap">数量</th>
      </tr>
EOF;

                  foreach ($output['goods_list'] as $g) {
                    echo <<<EOF
      <tr>
      <td scope="col" nowrap="nowrap">{$g['goods_name']}</td>
      <td scope="col" nowrap="nowrap">{$g['cat_name']}</td>
      <td scope="col" nowrap="nowrap">{$g['measure_name_cn']}</td>
      <td scope="col" nowrap="nowrap">{$g['bland']}</td>
      <td scope="col" nowrap="nowrap">{$g['goods_price']}</td>
      <td scope="col" nowrap="nowrap">{$g['goods_num']}</td>
      </tr>
EOF;

                  }
                  echo '</tbody></table>';
                } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>


<!--选择商品-->
<script type="text/js-tmpl" class="select-produce">
  <div class="consignee-wrap">
    <div class="consignee">
      <div>
        <div class="consignee-produce-name fl">
          <span>商品名称</span>
          <input type="text" id="form-search-goods-word" autocomplete="off" class="consignee-search" style="height:20px;">
        </div>
        <div class="consignee-cha-btn fl">
          <input type="button" class="button" autocomplete="off" value=" 查询" onclick="getGoodsList(1)">
        </div>
        <div class="consignee-new-add fl" >
          <a  class="add-nbutton" id="add_nproduce"  >
            新增商品
<!--            <i class="triangle_down_black"></i>-->
            <i class="ico ico-arrow"></i>
            <div class="dd-spacer"></div>
          </a>
        
     
          <!--        新增商品框-->
          <div class="item-add" id="produceNdd" style="display: none;" >
            <div class="item-addw">
              <div class="item-add1" style="margin-top: 10px;">
                <label class="tal-r"><span>*</span>分类:</label>
                <select class="js-example-basic-single" id="form-cat-id">
                   <option value="">请选择</option>
                  <?php foreach ($output['cate_list'] as $k => $v): ?>
                    <option value="<?php echo $v['cat_id'] ?>"><?php echo $v['cat_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="item-add1">
                <label class="tal-r"><span>*</span>商品单位:</label>
                <select class="js-example-basic-single item-add-fl-2" id="form-unit-id">
                  <option value="">请选择</option>
                  <?php foreach ($output['unit_list'] as $k => $v): ?>
                    <option value="<?php echo $v['id'] ?>"><?php echo $v['measure_name_cn'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
                           
              <div class="item-add1">
                <label class="tal-r"><span>*</span>商品名称:</label>
                <input type="text" class="item-add-fl" autocomplete="off" id="form-goods-name" value="">
              </div>
              <div class="item-add1">
                <label class="tal-r"><span>*</span>品牌:</label>
                <input type="text" class="item-add-fl" autocomplete="off" id="form-goods-brand" value="">
              </div>
              <div class="item-add1">
                <label class="tal-r"><span>*</span>单价:</label>
                <input type="text" class="item-add-fl" autocomplete="off" id="form-goods-price" value="">
              </div>
            </div>
            <div class="item-add-footer">
              <div class="item-add-qx">
                <input type="button" class="item-add-con" autocomplete="off" value="确定" onclick="addGoods()">
                <input type="button" class="item-add-cancel" autocomplete="off" value="取消">
              </div>
            </div>
          </div>
          <!--        end新增商品-->
        </div>
      </div>
    </div>
    <div class="goods-list">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-bo consignee-box">
        <tbody>
        <tr class="order-th s_oushuhang" id="select-goods-tr">
          <th style="width:5%" scope="col" nowrap="nowrap"><input type="checkbox" style="width:20px;" onclick="switchCheck(this)"></th>
          <th style="width:19%" scope="col" nowrap="nowrap">商品名称</th>
          <th style="width:19%" scope="col" nowrap="nowrap">分类</th>
          <th style="width:19%" scope="col" nowrap="nowrap">单位</th>
          <th style="width:19%" scope="col" nowrap="nowrap">品牌</th>
          <th style="width:19%" scope="col" nowrap="nowrap">单价</th>
          <th style="width:19%" scope="col" nowrap="nowrap">数量</th>
        </tr>
        <!-- tr>
          <td scope="col" nowrap="nowrap"><input type="checkbox" style="width:20px;"></td>
          <td scope="col" nowrap="nowrap">ff</td>
          <td scope="col" nowrap="nowrap">ee</td>
          <td scope="col" nowrap="nowrap">ss</td>
          <td scope="col" nowrap="nowrap">hh</td>
          <td scope="col" nowrap="nowrap">eee</td>
          <td scope="col" nowrap="nowrap"></td>
        </tr -->
        </tbody>
      </table>
    </div>
    <div id="goods-list-page">
      <!-- ul class="ajax_page consignee_page" style="overflow: hidden;">
        <li><span>首页</span></li>
        <li><span>上一页</span></li>
        <li><span class="currentpage">1</span></li>
        <li><span>下一页</span></li>
        <li><span>末页</span></li></ul -->
    </div>

  </div>
  <div class="produce-footer">
    <div class="produce-qx">
      <input type="button" class="produce-con" value="确定" onclick="checkedGoods();">
      <input type="button" class="produce-cancel" value="取消" onclick="$('.layui-layer-close').trigger('click');">
    </div>
  </div>

</script>

<!--选择收件人-->
<script type="text/js-tmpl" class="select-contact">
  <div class="consignee-wrap" >
    <div class="consignee">
      <span>收件人名称</span>
      <input type="text" autocomplete="off" id="form-search-consignee-word" class="consignee-search" value="" >
      <input type="button" class="button" value="查询" onclick="getContactList(1)">
    </div>
    <div class="consignee-details contact-list">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-box consignee-box">
        <tbody>
        <tr class="order-th s_oushuhang" style="">
          <th style="width:5%" scope="col" nowrap="nowrap"></th>
          <th style="width:19%" scope="col" nowrap="nowrap">收件人名称</th>
          <th style="width:19%" scope="col" nowrap="nowrap">地区</th>
          <th style="width:19%" scope="col" nowrap="nowrap">详细地址</th>
          <th style="width:19%" scope="col" nowrap="nowrap">邮编</th>
          <th style="width:19%" scope="col" nowrap="nowrap">手机号码</th>
        </tr>
        <!-- tr>
          <td scope="col" nowrap="nowrap"><input type="checkbox"></td>
          <td scope="col" nowrap="nowrap">ff</td>
          <td scope="col" nowrap="nowrap">ee</td>
          <td scope="col" nowrap="nowrap">ss</td>
          <td scope="col" nowrap="nowrap">hh</td>
          <td scope="col" nowrap="nowrap">eee</td>
        </tr -->
        </tbody>
      </table>
    </div>
    <div id="contact-list-page">
      <!-- ul class="ajax_page consignee_page">
        <li><span>首页</span></li>
        <li><span>上一页</span></li>
        <li><span class="currentpage">1</span></li>
        <li><span>下一页</span></li>
        <li><span>末页</span></li></ul -->
    </div>

  </div>
  <div class="produce-footer">
    <div class="produce-qx">
      <input type="button" class="produce-con" value="确定" onclick="checkedConsignee();">
      <input type="button" class="produce-cancel" value="取消" onclick="$('.layui-layer-close').trigger('click');">
    </div>
  </div>
</script>

<script src="templates/default/js/order/IDCard.js"></script>
<script>
  // 增值服务
  var $tab = $(".detail-block").find("li");
  // 初始化变量
  $tab.on("click", function () {
    var serviceSum = 0;
    $tab.filter('.addSecvi-select').each(function () {
      serviceSum += parseFloat($(this).data('value'));
    });
    $("#addServiceSum").text(serviceSum.toString() + "元");
  });
  //选择收件人弹窗
  $("#btn-select").on("click", function () {
    layer.open({
      type: 1,
      //skin: 'layui-layer-molv',
      title: '选择收件人',
      fix: false,
      maxmin: false,
      //shift: 4, //动画
      area: ['700px', '540px'],
      shadeClose: true, //点击遮罩关闭
      content: $(".select-contact").html()
    });
    window.setTimeout(function () {
      $('#form-search-consignee-word').bind('keydown', function (e) {
        if (e.which == 13) {
          getContactList(1);
        }
      });
      getContactList(1);
    }, 10);
  });

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

  function showProductList() {
    layer.open({
      type: 1,
      //skin: 'layui-layer-molv',
      title: '选择商品',
      fix: false,
      maxmin: false,
      //shift: 4, //动画
      area: ['700px', '520px'],
      shadeClose: false, //点击遮罩关闭
      end: function () {
      },
      content: $('.select-produce').html()
      //content: SITE_SITE_URL + '/index.php?act=order_tp&op=payStatusPage&status=fail&order_id=' + order_id
    });
    window.setTimeout(function () {
      // 新增商品框
      $(".add-nbutton").on("click", function () {
            $(".dd-spacer").show();
            $(".item-add").show();

          }
      );

      $(".item-add-cancel").on("click", function () {
        $(".item-add").hide();
        $(".dd-spacer").hide();
      });

      $('#form-search-goods-word').bind('keydown', function (e) {
        if (e.which == 13) {
          getGoodsList(1);
        }
      });
      
//      新增商品的分类和单位支持输入搜索
      $(document).ready(function() {
        $(".js-example-basic-single").select2();
      });
//      console.log($(".js-example-basic-single"));
      getGoodsList(1);
    }, 10);
  }
  function addGoods() {
    var cat_id = getIdValue('form-cat-id'),
        unit_id = getIdValue('form-unit-id'),
        name = getIdValue('form-goods-name'),
        brand = getIdValue('form-goods-brand'),
        price = getIdValue('form-goods-price');
    if (!cat_id) {
      alert('请选择分类');
      return false;
    }
    if (!unit_id) {
      alert('请选择单位');
      return false;
    }
    if (!name) {
      alert('请输入商品名称');
      return false;
    }
    if (!brand) {
      alert('请输入品牌');
      return false;
    }
    if (!price || !/^[\d\.]+$/.test(price)) {
      alert('请输入价格');
      return false;
    }

    $.ajax({
      url: SITE_SITE_URL + '/index.php?act=order_tp&op=addGoods',
      type: 'post',
      data: {cat_id: cat_id, unit_id: unit_id, name: name, brand: brand, price: price},
      dataType: 'json',
      success: function (res) {
        if (res.status) {
          $('#form-cat-id').val('');
          $('#form-unit-id').val('');
          $('#form-goods-name').val('');
          $('#form-goods-brand').val('');
          $('#form-goods-price').val('');
          getGoodsList(1, res.data);
          $(".item-add-cancel").trigger('click');
        } else {
          alert(res.msg);
        }
      }
    });
  }

  var goodsListSelected = {};
  var findSelectedGoods = function() {
    // current page
    $('.goods-list tr:gt(0) input:checkbox').each(function(){
      var val = $(this).val(), exists = goodsListSelected[val] != undefined;
      if ($(this).is(':checked')) {
        if (!exists) {
          var s = JSON.parse($(this).data('src').split('__--__').join('"'));
          s.number = Math.max($(this).closest('tr').find('[type=number]').val(), 1);
          goodsListSelected[val] = s;
        }
      } else if (exists) {
        delete goodsListSelected[val];
      }
    });
  };
  function getGoodsList(page, checkedId) {
    findSelectedGoods();
    $.ajax({
      url: SITE_SITE_URL + '/index.php?act=order_tp&op=getGoodsList',
      type: 'get',
      data: {q: $('#form-search-goods-word').val(), curpage: page},
      dataType: 'json',
      success: function ($res) {
        if ($res.status) {
          $('.goods-list tbody>tr:gt(0)').remove();
          //点击下一页时，清除checkbox里勾勾
          $('#select-goods-tr input:checkbox').prop('checked', false);
          if ($res.count > 0 && $res.data.length > 0) {
            for (var i = 0; i < $res.data.length; i++) {
              var d = $res.data[i], tr = ' <tr>\
              <td scope="col" nowrap="nowrap"><input type="checkbox" style="width:20px;" name="goods_checked_id" '+(goodsListSelected[d.id]!==undefined&&' checked'||'')+' data-src="'+JSON.stringify(d).split('"').join('__--__')+'" value="' + d.id + '" ' + (d.id == checkedId && 'checked' || '') + '></td>\
              <td scope="col" nowrap="nowrap">' + d.name + '</td>\
              <td scope="col" nowrap="nowrap">' + d.cat_name + '</td>\
              <td scope="col" nowrap="nowrap">' + d.unit_name + '</td>\
              <td scope="col" nowrap="nowrap">' + d.brand + '</td>\
              <td scope="col" nowrap="nowrap">' + d.price + '</td>\
              <td scope="col" nowrap="nowrap"><input type=number maxlength="5" style="width:80px;text-align: center;" placeholder="1" value="'+(goodsListSelected[d.id]!==undefined&&goodsListSelected[d.id].number||'')+'"></td>\
              </tr>';
              $('.goods-list tbody').append(tr);
            }
            $('#goods-list-page').show().html($res.page);
            $('[name=goods_checked_id]').bind('click', function () {
              var cbx = $('.goods-list tbody [type=checkbox]:gt(0)'), len = cbx.length;
              $('.goods-list tbody [type=checkbox]:eq(0)').prop('checked', cbx.filter(':checked').length == len);
            });
          } else {
            $('.goods-list tbody').append('<tr><td colspan="20">暂无数据</td></tr>');
            $('#goods-list-page').hide().empty();
          }
        } else {
          $('.goods-list tbody>tr:gt(0)').remove();
          $('.goods-list tbody').append('<tr><td colspan="20">暂无数据</td></tr>');
          $('#goods-list-page').hide().empty();
        }
      }
    });
  }

  function getContactList(page) {
    $.ajax({
      url: SITE_SITE_URL + '/index.php?act=order_tp&op=getConsigneeList',
      type: 'get',
      data: {q: $('#form-search-consignee-word').val(), curpage: page},
      dataType: 'json',
      success: function ($res) {
        if ($res.status) {
          $('.contact-list tbody>tr:gt(0)').remove();
          if ($res.count > 0 && $res.data.length > 0) {
            for (var i = 0; i < $res.data.length; i++) {
              var d = $res.data[i], tr = '<tr data-source="'+JSON.stringify(d).split('"').join('__-__')+'"> \
                <td scope="col" nowrap="nowrap"><input type="radio" value="'+d.cid+'" name="consignee_list_item"></td> \
                <td scope="col" nowrap="nowrap">'+ d.name+'</td> \
                <td scope="col" nowrap="nowrap">'+ d.province+','+ d.city+','+d.area+'</td> \
                <td scope="col" nowrap="nowrap">'+ d.address+'</td> \
                <td scope="col" nowrap="nowrap">'+ d.zipcode+'</td> \
                <td scope="col" nowrap="nowrap">'+ d.phone+'</td> \
                </tr>';
              $('.contact-list tbody').append(tr);
            }
            $('#contact-list-page').show().html($res.page);
          } else {
            $('.contact-list tbody').append('<tr><td colspan="20">暂无数据</td></tr>');
            $('#contact-list-page').hide().empty();
          }
        } else {
          $('.contact-list tbody>tr:gt(0)').remove();
          $('.contact-list tbody').append('<tr><td colspan="20">暂无数据</td></tr>');
          $('#contact-list-page').hide().empty();
        }
      }
    });
  }

  function switchCheck(o) {
    $('[name=goods_checked_id]').prop('checked', $(o).is(':checked'));
  }
  function selectTransCountry(tid,ship_method){
      $('#ship_method').val(ship_method);
      if(!ship_method){
          $('#ship_method_name').val('');
      }
      if(ship_method == 'CH0002'){
          $('#idcardp1,#idcardp2').hide();
      }else{
          $('#idcardp1,#idcardp2').show();
      }
      fill_tc(tid,ship_method);
  }

  function checkedGoods() {
    findSelectedGoods();
    var goodsListHtml = '<table width="100%" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-bo consignee-box" style="width:auto;border-left:0;border-right:0;border-bottom:0"> \
      <tbody> \
      <tr class="order-th s_oushuhang"> \
      <th style="width:19%" scope="col" nowrap="nowrap">商品名称</th> \
      <th style="width:19%" scope="col" nowrap="nowrap">分类</th> \
      <th style="width:19%" scope="col" nowrap="nowrap">单位</th> \
      <th style="width:19%" scope="col" nowrap="nowrap">品牌</th> \
      <th style="width:19%" scope="col" nowrap="nowrap">单价</th> \
      <th style="width:19%" scope="col" nowrap="nowrap">数量</th> \
      </tr>', goodsCount = 0;
    //$('[name=goods_checked_id]:checked').each(function () {
    $.each(goodsListSelected,function(i, d){
      /*var tr = $(this).closest('tr').clone(), goods_id = $(this).val(), number = tr.find('[type=number]').val();
      if (number < 1) number = 1;
      $('[type=checkbox]', tr).parent().remove();
      $('[type=number]', tr).parent().text(number);
      goodsListHtml += '<tr data-gid=' + goods_id + '>' + tr.html() + '</tr>';*/
      goodsListHtml += '<tr data-gid=' + d.goods_id + '> \
            <td scope="col" nowrap="nowrap">' + d.name + '</td>\
            <td scope="col" nowrap="nowrap">' + d.cat_name + '</td>\
            <td scope="col" nowrap="nowrap">' + d.unit_name + '</td>\
            <td scope="col" nowrap="nowrap">' + d.brand + '</td>\
            <td scope="col" nowrap="nowrap">' + d.price + '</td>\
            <td scope="col" nowrap="nowrap">' + d.number + '</td>\
        </tr>';
      goodsCount++;
    });
    if (goodsCount < 1) {
      alert('请选择商品并设置数量');
      return;
    }
    goodsListHtml += '</tbody></table>';
    $('.goods_block table').remove();
    $('.goods_block').append(goodsListHtml);
    $('.layui-layer-close').trigger('click');

    goodsListSelected = {};
  }

  function checkedConsignee() {
    var tr = $('[name=consignee_list_item]:checked').closest('tr');
    if (tr.length < 1) {
      alert('请选择收件人');
      return false;
    }
    var d = JSON.parse(tr.data('source').split('__-__').join('"'));
    $('#reciver_name').val(d.name);
    $('#provincer').data('origin', d.province+','+ d.city+','+ d.area);
    $('#reciver_address').val(d.address);
    $('#reciver_zipcode').val(d.zipcode);
    $('#reciver_phone').val(d.phone);
//    if (d.ID) {
      $('#id_card_number').val(d.ID);
      $('#id_card_front_img').attr('src', d.ID_front);
      $('#id_card_back_img').attr('src', d.ID_back);
//    }
    initReciverRegion();
    $('.layui-layer-close').trigger('click');
  }

  function initReciverRegion() {
    var receiver_origin = $('#provincer').data('origin');
    if (receiver_origin!=',,') {
      $("#provincer").parent().next('ul').find("li:contains('"+receiver_origin.split(',')[0]+"')").trigger('mousedown');
    }
  }

  $(function(){
    $('#transh_l li[data-code="<?php echo $output['latest_tccode']?>"]').trigger('mousedown');
    initReciverRegion();
    $('#express_l li:contains("<?php echo $output['order_info']['company']?>")').trigger('mousedown');  //编辑时选定快递信息
    var chn_name = "<?php echo $output['channel']['name']?>";
    var chn_code = "<?php echo $output['channel']['code']?>";
    $('#ship_method').val(chn_code);
    $('#ship_method_name').val(chn_name);
    //$('#ship_method_l li:contains("<?php echo $output['order_info']['ship_method']?>")').trigger('mousedown'); //编辑时选定运输方式
    var serviceSum = 0;
    $tab.filter('.addSecvi-select').each(function () {
      serviceSum += parseFloat($(this).data('value'));
    });
    $("#addServiceSum").text(serviceSum.toString() + "元");
    $(".pop-head-title>h3").html("<?php echo $_GET['order_id']?"编辑运单":"<i class='ico-increase'></i>新增运单"?>");
  });
</script>
