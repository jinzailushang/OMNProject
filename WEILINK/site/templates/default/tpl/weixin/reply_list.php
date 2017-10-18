<link href="templates/default/jqueryui-editable-1.5.1/jquery-ui-1.9.2.custom/css/base/jquery-ui-1.9.2.custom.min.css"
      rel="stylesheet">
<script
  src="templates/default/jqueryui-editable-1.5.1/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="templates/default/jqueryui-editable-1.5.1/jqueryui-editable/css/jqueryui-editable.css" rel="stylesheet">
<script src="templates/default/jqueryui-editable-1.5.1/jqueryui-editable/js/jqueryui-editable.js"></script>
<style>
  .notice-list {
    line-height: 180%;
    list-style-type: decimal;
    padding-left: 20px;
  }

  .notice-list > li {
    padding-left: 10px
  }

  .editable-text.editable.editable-click.editable-empty {
    color: #ccc;
    font-weight: normal;
  }
  .form-group-item {
    border: 1px solid #eee;
    border-radius: 2px;
    width: 330px;
    padding: 10px;
    line-height: 180%;
    margin:10px auto 15px auto;
    background-color:#fcfcfc;
    position:relative;
  }

  .form-group-item:hover {
    border-color: #aaa;
  }

  .form-group {
    margin-bottom: 12px;
  }

  .form-group:after {
    content: '';
    clear: both;
    width: 100%;
    display: block;
  }

  .form-control {
    width: 300px;
    height: auto;
    font-size:12px;
  }

  .help-block {
    color: #aaa;
  }

  .form-group > label {
    font-weight: bold;
  }
</style>
<!-- div class="operationsbox">
  <ul class="operNav">
    <li><a class="btn-enter" href="javascript:build_menu();"><i class="ico-export-or"></i>生成菜单</a></li>
  </ul>
</div -->
<table id="testing1" style="width:1200px;margin-left:7px;margin-top:10px" border="0" cellspacing="0" cellpadding="0"
       tab-group="1"
       class="order-table-box">
  <tbody>
  <tr class="order-th s_oushuhang">
    <th scope="col" nowrap="nowrap" style="width:30px">#</th>
    <th scope="col" nowrap="nowrap" style="width:150px">关键词</th>
    <th scope="col" nowrap="nowrap" style="width:150px">回复类型</th>
    <th scope="col" nowrap="nowrap">回复内容</th>
    <th scope="col" nowrap="nowrap" style="width:60px">默认</th>
    <th scope="col" nowrap="nowrap" style="width:60px">操作</th>
  </tr>
  <?php if ($output['reply_list']) {
    foreach ($output['reply_list'] as $reply) { ?>
      <tr height="30" class="hover s_jishuhang" data-list-id="<?php echo $reply['reply_id'] ?>">
        <td scope="row" align="center"> <?php echo $reply['reply_id'] ?></td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                          data-pk="reply_id_<?php echo $reply['reply_id'] ?>_words"
                                          data-url="/site/index.php?act=weixin/backend&op=update_reply&reply_id=<?php echo $reply
                                          ['reply_id'] ?>&field=words"
                                          data-title="输入关键词"><?php echo $reply['words'] ?></a></td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="select"
                                          data-pk="reply_id_<?php echo $reply['reply_id'] ?>_reply_type"
                                          data-url="/site/index.php?act=weixin/backend&op=update_reply&reply_id=<?php echo $reply
                                          ['reply_id'] ?>&field=reply_type" data-title="选择类型"
                                          data-source="[{text:'文本',value:'text'},{text:'图文',value:'news'}]"
                                          data-value="<?php echo $reply['reply_type'] ?>"><?php echo $reply['reply_type'] == 'news' ? '图文' : '文本' ?></a>
        </td>
        <td scope="row" align="center">
          <?php if($reply['reply_type'] == 'text') {?>
          <a href="#" class="editable-text" data-type="textarea"
                                          data-pk="reply_id_<?php echo $reply['reply_id'] ?>_reply_content"
                                          data-url="/site/index.php?act=weixin/backend&op=update_reply&reply_id=<?php echo $reply
                                          ['reply_id'] ?>&field=reply_content"
                                          data-title="输入回复内容"><?php echo $reply['reply_content'] ?></a>
        <?php } else { ?>
          <a href="javascript:" class="edit-reply-content" onclick="edit_reply_content(<?php echo $reply['reply_id']?>,'<?php echo str_replace('"','__-__',$reply['reply_content'])?>')">【编辑内容】</a>
        <?php }?>
        </td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="select"
                                          data-pk="reply_id_<?php echo $reply['reply_id'] ?>_is_default"
                                          data-url="/site/index.php?act=weixin/backend&op=update_reply&reply_id=<?php echo $reply
                                          ['reply_id'] ?>&field=is_default"
                                          data-source="[{text:'否',value:'N'},{text:'是',value:'Y'}]"
                                          data-title="请选择"><?php echo $reply['is_default'] == 'Y' ? '是' : '否' ?></a>
        </td>
        <td scope="row" align="center">
          <button onclick="del(<?php echo $reply['reply_id'] ?>)">删除</button>
        </td>
      </tr>
    <?php }
  } ?>
</table>

<div style="width:1200px;margin:20px 0;text-align:center">
  <button onclick="add()">添加回复</button>
</div>

<div style="width:1176px;margin:20px 10px;padding:10px;border:1px solid #eee;background:#fcfcfc;border-radius: 2px;">
  <p>
    <strong>请注意：</strong>
  <ol class="notice-list">
    <li>
      回复类型分为两种：文本和图文。
    </li>
    <li>
      文本格式为纯文本，没有任何样式的，点击回复内容可即时编辑。
    </li>
    <li>
      图文格式为图片和文字混合的样式，点击【编辑内容】可编辑消息，图文消息个数，限制为10条以内。
    </li>
    <li>
      多条图文消息信息，默认第一个item为大图,注意，如果图文数超过10，则将会无响应。
    </li>
    <li>图文消息里的图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200。</li>
    <li style="color:red">自定义菜单(click类型)如需实现自动回复，请将关键词(key)设置为以“REPLY_”开头(去掉双引号)。</li>
    <li style="color:red">“SUBSCRIBE”关键词为关注的自动回复，请勿删除！</li>
  </ol>
  </p>
</div>

<script type="text/js-tmpl" id="news-tmpl">
  <div class="form-group-item">
    <div class="form-group">
      <label>文章标题</label>
      <input type="text" class="form-control form-Title" placeholder="请输入文章标题">
    </div>
    <div class="form-group">
      <label>文章描述</label>
      <input type="text" class="form-control form-Description" placeholder="请输入文章描述">
    </div>
    <div class="form-group">
      <label>图片地址</label>
      <input type="text" class="form-control form-PicUrl" placeholder="请输入图片地址">
      <p class="help-block">图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200</p>
    </div>
    <div class="form-group">
      <label for="exampleInputFile">链接地址</label>
      <input type="text" class="form-control form-Url" placeholder="请输入链接地址">
    </div>
    <a href="javascript:" style="color:red;position:absolute;right:10px;top:10px" onclick="$('.form-group-item').length>1&&$(this).closest('.form-group-item').remove()" title="删除">×</a>
  </div>
  <button class="btn btn-default" onclick="var h=$(this).prev('.form-group-item').clone();$('input[type=text]', h).val('');h.insertBefore($(this));" style="margin-left:10px">添加回复</button>
</script>

<script>
  $('.editable-text').editable({
    emptytext: '无内容',
    success: function (response, newValue) {
      var pk = $(this).data('pk');
      if (pk.indexOf('is_default') > 0 || pk.indexOf('reply_type')) {
        window.location.reload();
      }
    }
  });
  function del(reply_id) {
    layer.confirm('此操作不可恢复，确定要执行吗？', function (index) {
      $.post('/site/index.php?act=weixin/backend&op=del_reply', {reply_id: reply_id}, function () {
        window.location.reload();
      });
    });
  }
  function add(o) {
    var html = $('<tr height="30" class="hover s_jishuhang">\
      <td scope="row" align="center"><a href="javascript:" style="color:red" onclick="$(this).closest(\'tr\').remove()" title="删除">×</a></td>\
      <td scope="row" align="center"><input type="text" placeholder="关键词"></td>\
      <td scope="row" align="center"><select><option value="text">文本</option><option value="news">图文</option></select></td>\
      <td scope="row" align="center"><textarea style="width:100%" placeholder="回复内容"></textarea></td>\
      <td scope="row" align="center"><select><option value="N">否</option><option value="Y">是</option></select></td>\
      <td scope="row" align="center"><button onclick="save(this)">保存</button></td>\
      </tr>');
    if (o) {
      html.insertBefore(o.closest('tr'));
    } else {
      html.appendTo($('#testing1'));
    }

    setTimeout(function () {
      $('input:eq(0)', html).focus();
    }, 50);
  }
  function save(o) {
    var t = o.closest('tr'), data = {
      words: $('input:eq(0)', t).val(),
      reply_type: $('select:eq(0)', t).val(),
      is_default: $('select:eq(1)').val(),
      reply_content: $('textarea').val()
    };

    $.post('/site/index.php?act=weixin/backend&op=add_reply', data, function () {
      window.location.reload();
    });
  }
  function edit_reply_content(reply_id, reply_content) {
    var index;

    index = layer.open({
      title: '编辑回复内容',
      type: 1,
      area: ['380px', '470px'],
      btn: ['保存'],
      content: $('#news-tmpl').html(),
      yes: function(index, layero) {
        var content = [];
        var ly = $('#layui-layer'+index), items = $('.form-group-item');
        for (var i = 0; i< items.length; i++) {
          var item = $('.form-group-item:eq('+i+')'),
            Title = $.trim($('.form-Title', item).val()),
            Description = $.trim($('.form-Description', item).val()),
            PicUrl = $.trim($('.form-PicUrl', item).val()),
            Url = $.trim($('.form-Url', item).val());
          if (!Title) {
            alert('无效的标题');
            return false;
          }
          if (!Description) {
            alert('无效的描述');
            return false;
          }
          if (!PicUrl) {
            alert('无效的图片链接');
            return false;
          }
          if (!Url) {
            alert('无效的链接地址');
            return false;
          }
          content.push({
            Title: Title,
            Description: Description,
            PicUrl: PicUrl,
            Url: Url
          });
        }
        content = JSON.stringify(content);
        $.post('/site/index.php?act=weixin/backend&op=update_reply&field=reply_content&reply_id='+reply_id, {value: content}, function(){
          layer.alert('保存成功！', {icon: 1}, function(i){
            $('[data-list-id='+reply_id+'] .edit-reply-content').attr('onclick', "edit_reply_content("+reply_id+",'"+content.split('"').join('__-__')+"')");
            layer.close(i);
          });
        });
      }
    });

    layer.ready(function(){
      if (reply_content) {
        reply_content = JSON.parse(reply_content.split('__-__').join('"'));
        if (reply_content) {
          var ly = $('#layui-layer' + index);
          console.log(ly)
          for (var i = 1; i < reply_content.length; i++) {
            $('.form-group-item:last').after($('#news-tmpl').html());
          }
          $('button:gt(0)', ly).remove();
          for (var i = 0; i < reply_content.length; i++) {
            var item = $('.form-group-item:eq(' + i + ')'), data = reply_content[i];
            $('.form-Title', item).val(data.Title);
            $('.form-Description', item).val(data.Description);
            $('.form-PicUrl', item).val(data.PicUrl);
            $('.form-Url', item).val(data.Url);
          }
        }
      }
    });
  }
</script>