
<style>
  #trans-house-form .control-group {
    padding-left:5px;
    padding-right:5px;
  }
  #trans-house-form .control-group .control-label {
    width:108px;
  }
   .controls .trans-textinput {
       width:399px;
       height:20px;
   }

  .sf {
      position: relative;
      width: 200px;
      height: 114px;
      background: #ececec;
      border-radius: 4px;
  }
  .control-l p {
      color: #999;
      margin: 4px 4px 4px 60px;
      font-size: 14px;
  }
  .sf img {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      max-width: 100%;
      max-height: 100%;
  }
  </style>
<div class="pop-tabs test-slide" id="trans-house-form">

    <div class="quick_links_wrap">
        <div class="pop-head-box clearfix">
            <div class="pop-head-title"><h3><i class="ico-increase"></i><span>新增</span>中转仓</h3></div>
            <div class="pop-head-hot">
                <div class="hot-box">
                    <a href="javascript:;" onclick="add_th()"><i class="ico-submit"></i>提交</a>
                    <a href="javascript:;" onclick="$('.test-slide').removeClass('in');"><i class="ico-back"></i>返回</a>
                </div>
                <a href="javascript:void(0)" class="pop-close">×</a>
            </div>
        </div>
        <form id="add_form" method="post"  action="<?php echo urlShop('trans_house', 'save') ?>">
          <input type="hidden" id="form-tid" name="form-tid" />
            <div class="detail-content scrollwrapper mCustomScrollbar" style="width:606px;height:777px;">
                <div class="mCustomScrollBox">
                    <div class="mCSB_container">
                        <div class="goods-category-box">
                            <div class="goods-details">
                                <div class="details-wrap">
                                    <div class="detail-block">
                                        
                                        <div class="control-group">
                                            <div>
                                                <input type="hidden" id="form-tc_type" value="" placeholder="" />
                                                <div class="control-label"><em class="col-red">*</em>货站类型：</div>
                                                <div class="controls">
                                                    <div class="dropdown shop-select">
                                                        <a class="selectui-result dropdown-toggle"><input type="text" id="form-tc_type1" class="control-input" value="" placeholder="请选择" onfocus="showAndHide('List2', 'show');" onblur="showAndHide('List2', 'hide');"/><i class="selectIcon"></i></a>
                                                        <ul id="List2" class="dropdown-menu border-dropdown w-90">
<!--                                                            <li class="j_company" onmousedown="getVal('tc_type1', '纵腾仓');showAndHide('List2', 'hide');$('#w_id').val('zt')"><a>纵腾仓</a></li>-->
                                                            <?php foreach ($output['tc_type_list'] as $k => $v): ?>
                                                                <li class="j_company" onmousedown="getVal('form-tc_type1', '<?php echo $v ?>');showAndHide('List2', 'hide');$('#form-tc_type').val('<?php echo $k ?>')"><a><?php echo $v ?></a></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="control-group">
                                            <div>
                                                <div class="control-label"><em class="col-red">*</em>货站编码：</div>
                                                <div class="controls">
                                                  <input type="text" name="form-tc_code" id="form-tc_code" class="trans-textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div>
                                                <div class="control-label"><em class="col-red">*</em>货站名称：</div>
                                                <div class="controls">
                                                  <input type="text" class="trans-textinput" name="form-tc_name" id="form-tc_name" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div>
                                                <div class="control-label"><em class="col-red">*</em>国家：</div>
                                                <div class="controls">
                                                    <input type="text" name="form-country"  id="form-country" class="trans-textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                      <div class="control-group">
                                        <div>
                                          <div class="control-label"><em class="col-red">*</em>洲/省：</div>
                                          <div class="controls">
                                            <input type="text" name="form-province"  id="form-province" class="trans-textinput" value="" placeholder="" />
                                          </div>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <div>
                                          <div class="control-label"><em class="col-red">*</em>城市：</div>
                                          <div class="controls">
                                            <input type="text" name="form-city"  id="form-city" class="trans-textinput" value="" placeholder="" />
                                          </div>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <div>
                                          <div class="control-label"><em class="col-red">*</em>详细地址：</div>
                                          <div class="controls">
                                            <input type="text" name="form-address"  id="form-address" class="trans-textinput" value="" placeholder="" />
                                          </div>
                                        </div>
                                      </div>
                                      <div class="control-group">
                                        <div>
                                          <div class="control-label"><em class="col-red">*</em>邮编：</div>
                                          <div class="controls">
                                            <input type="text" name="form-zipcode"  id="form-zipcode" class="trans-textinput" value="" placeholder="" />
                                          </div>
                                        </div>
                                      </div>
                                        <div class="control-group">
                                            <div>
                                                <div class="control-label"><em class="col-red">*</em>电话：</div>
                                                <div class="controls">
                                                    <input type="text" name="form-phone"  id="form-phone" class="trans-textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div>
                                                <div class="control-label"><em class="col-red">*</em>收件人姓名：</div>
                                                <div class="controls">
                                                    <input type="text" name="form-receiver" id="form-receiver" class="trans-textinput" value="" placeholder="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div id="participants-component">
                                              <div class="control-label"><em class="col-red">*</em>国家照片：</div>
                                              <div style="overflow: hidden">
                                                <div class="control-l">

                                                  <div class="sf">
                                                    <img src="templates/default/images/sff.png" id="form-countrys_img" onerror="this.src='templates/default/images/sfz.png'" >
                                                  </div>
                                                  <p>上传国家照片</p>
                                                  <input type="file" value="" name="form-countrys" id="form-countrys" class="rowcol sf-fi" onchange="showPic('form-countrys')" style="width:190px;">
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                      <?php
                                      $last_key = ''; $index = 0;
                                      foreach ($output['extra_service_list'] as $skey=>$esl) {
                                      $temp = explode('(', $esl['text']);
                                        list($key,$val) = explode(':', $skey);
                                        if ($last_key != $key) {
                                      if ($index) {
                                        ?>
                                    </div>
                                </div>
                            </div>
                          <?php }?>
                                        <div class="control-group">
                                          <div>
                                            <div class="control-label"><em class="col-red">*</em><?php
                                              echo $temp[0];
                                              ?>费：</div>
                                            <div class="controls">
                                              <?php } ?>
                                              <input type="text" name="form-continue_weight_fee" id="form-<?php echo $skey?>" class="trans-textinput" value="" placeholder="<?php echo !empty($temp[1])? substr($temp[1],0,-1):''?>" <?php
                                              echo !empty($temp[1])? 'style="width:41.5%"':''?> />
                                      <?php
                                      $last_key = $key;
                                      $index++;
                                      } ?>
                                            </div>
                                          </div>
                                        </div>
                                      <div class="control-group">
                                        <div>
                                          <div class="control-label"><em class="col-red">*</em>货币：</div>
                                          <div class="controls">
                                            <input type="text" name="form-currency" id="form-currency" class="trans-textinput" value="" placeholder="" />
                                          </div>
                                        </div>
                                      </div>
                                      <div class="control-group channel_t0">
                                        <div>
                                          <div class="control-label"><em class="col-red">*</em>渠道：<br><span id="chn"><a href="javascript:;" style="padding:10px" onclick="add(this)">+</a></span></div>
                                          <div class="controls channel_d">
                                              <input type="text" placeholder="渠道名称" style="width:90px"/>
                                              <input type="text" placeholder="渠道编码" style="width:90px"/> 
                                              <input type="text" placeholder="首重" style="width:90px"/>
                                              <input type="text" placeholder="续重" style="width:90px"/>
                                              <input type="text" placeholder="首重费" style="width:90px"/>
                                              <input type="text" placeholder="续重费" style="width:90px"/>
                                              <input type="text" placeholder="首重费(国内段)" style="width:90px"/>
                                              <input type="text" placeholder="首重费(国内段)" style="width:90px"/>
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
<script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/trans_house/trans_house.js"></script>
<script type="text/javascript">
  var trans_house_list = <?php echo json_encode($output['extra_service_list']);?>;
    $(document).ready(function () {
        initData(1);
        
        $('#create-div').click(function () {
          document.getElementById('add_form').reset();
          $('.pop-head-title span').text('新增');
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
    function add(obj){
        var oo = $(obj).closest('.channel_t0');
        var html = oo.clone();
        html.find('#chn').html('<a href="javascript:;" style="padding:10px" onclick="sub(this)">-</a>');
        html.find('input').val('');
        oo.after(html);
    }
    function sub(obj){
        $(obj).closest('.channel_t0').remove();
    }
    function showPic(id) {
        var reader = new FileReader();
        reader.addEventListener("load", function () {
          $.ajax({
            url: SITE_SITE_URL + '/index.php?act=trans_house&op=uploadCountryPic',
            data: {file: reader.result},
            type: 'post',
            dataType: 'text',
            success: function (res) {
              document.getElementById(id + '_img').src = res;
            }
          });
        }, false);
        reader.readAsDataURL(document.getElementById(id).files[0]);
  }

</script>
