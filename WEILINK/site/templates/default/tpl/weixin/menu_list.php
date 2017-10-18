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
</style>
<div class="operationsbox">
  <ul class="operNav">
    <li><a class="btn-enter" href="javascript:build_menu();"><i class="ico-export-or"></i>生成菜单</a></li>
  </ul>
</div>
<table id="testing1" style="width:1200px;margin-left:7px" border="0" cellspacing="0" cellpadding="0" tab-group="1"
       class="order-table-box">
  <tbody>
  <tr class="order-th s_oushuhang">
    <th scope="col" nowrap="nowrap" style="width:30px">#</th>
    <th scope="col" nowrap="nowrap">名称</th>
    <th scope="col" nowrap="nowrap" style="width:70px">类型</th>
    <th scope="col" nowrap="nowrap">关键词(key)</th>
    <th scope="col" nowrap="nowrap">链接(url)</th>
    <th scope="col" nowrap="nowrap">排序</th>
    <th scope="col" nowrap="nowrap" style="width:60px">操作</th>
  </tr>
  <?php if ($output['menu_list']) {
    foreach ($output['menu_list'] as $menu) { ?>
      <tr height="30" class="hover s_jishuhang" data-list-id="<?php echo $menu['menu_id'] ?>"
          style="font-weight: bold;background-color:rgba(255, 255, 0, 0.13)">
        <td scope="row" align="center"> <?php echo $menu['menu_id'] ?></td>
        <td scope="row" style="padding-left:10px"><a href="#" class="editable-text" data-type="text"
                                                     data-pk="menu_id_<?php echo $menu['menu_id'] ?>_name"
                                                     data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $menu
                                                     ['menu_id'] ?>&field=name"
                                                     data-title="输入名称"><?php echo $menu['name'] ?></a></td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="select"
                                          data-pk="menu_id_<?php echo $menu['menu_id'] ?>_type"
                                          data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $menu
                                          ['menu_id'] ?>&field=type" data-title="选择类型"
                                          data-source="[{text:'view',value:'view'},{text:'click',value:'click'}]"
                                          data-value="<?php echo $menu['type'] ?>"><?php echo $menu['type'] ?></a></td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                          data-pk="menu_id_<?php echo $menu['menu_id'] ?>_key"
                                          data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $menu
                                          ['menu_id'] ?>&field=key" data-title="输入关键词"><?php echo $menu['key'] ?></a>
        </td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                          data-pk="menu_id_<?php echo $menu['menu_id'] ?>_url"
                                          data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $menu
                                          ['menu_id'] ?>&field=url" data-title="输入链接"><?php echo $menu['url'] ?></a>
        </td>
        <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                          data-pk="menu_id_<?php echo $menu['menu_id'] ?>_sort"
                                          data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $menu
                                          ['menu_id'] ?>&field=sort" data-title="输入序号"><?php echo $menu['sort'] ?></a>
        </td>
        <td scope="row" align="center">
          <button onclick="del(<?php echo $menu['menu_id'] ?>)">删除</button>
        </td>
      </tr>
      <?php if ($menu['sub_menu']) {
        foreach ($menu['sub_menu'] as $smenu) { ?>
          <tr height="30" class="hover s_jishuhang" data-list-id="<?php echo $smenu['menu_id'] ?>">
            <td scope="row" align="center"><?php echo $smenu['menu_id'] ?></td>
            <td scope="row" style="padding-left:10px">&gt; <a href="#" class="editable-text" data-type="text"
                                                              data-pk="menu_id_<?php echo $smenu['menu_id'] ?>_name"
                                                              data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $smenu
                                                              ['menu_id'] ?>&field=name"
                                                              data-title="输入名称"><?php echo $smenu['name'] ?></a></td>
            <td scope="row" align="center"><a href="#" class="editable-text" data-type="select"
                                              data-pk="menu_id_<?php echo $smenu['menu_id'] ?>_type"
                                              data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $smenu
                                              ['menu_id'] ?>&field=type" data-title="选择类型"
                                              data-source="[{text:'view',value:'view'},{text:'click',value:'click'}]"
                                              data-value="<?php echo $smenu['type'] ?>"><?php echo $smenu['type'] ?></a>
            </td>
            <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                              data-pk="menu_id_<?php echo $smenu['menu_id'] ?>_key"
                                              data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $smenu
                                              ['menu_id'] ?>&field=key"
                                              data-title="输入关键词"><?php echo $smenu['key'] ?></a></td>
            <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                              data-pk="menu_id_<?php echo $smenu['menu_id'] ?>_url"
                                              data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $smenu
                                              ['menu_id'] ?>&field=url"
                                              data-title="输入链接"><?php echo $smenu['url'] ?></a></td>
            <td scope="row" align="center"><a href="#" class="editable-text" data-type="text"
                                              data-pk="menu_id_<?php echo $smenu['menu_id'] ?>_sort"
                                              data-url="/site/index.php?act=weixin/backend&op=update_menu&menu_id=<?php echo $smenu
                                              ['menu_id'] ?>&field=sort"
                                              data-title="输入序号"><?php echo $smenu['sort'] ?></a></td>
            <td scope="row" align="center">
              <button onclick="del(<?php echo $smenu['menu_id'] ?>)">删除</button>
            </td>
          </tr>
        <?php }
      } ?>
      <tr height="30" class="hover s_jishuhang">
        <td style="border-right:0"></td>
        <td colspan="6" style="padding-left:10px">
          <button onclick="add(this,<?php echo $menu['menu_id'] ?>)">添加子菜单</button>
        </td>
      </tr>
    <?php }
  } ?>
</table>

<div style="width:1200px;margin:20px 0;text-align:center">
  <button onclick="add()">添加主菜单</button>
</div>

<div style="width:1176px;margin:20px 10px;padding:10px;border:1px solid #eee;background:#fcfcfc;border-radius: 2px;">
  <p>
    <strong>请注意：</strong>
  <ol class="notice-list">
    <li>
      排序规则：由大到小。
    </li>
    <li>
      自定义菜单最多包括3个一级菜单，每个一级菜单最多包含5个二级菜单。
    </li>
    <li>
      一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。
    </li>
    <li>
      创建自定义菜单后，菜单的刷新策略是，在用户进入公众号会话页或公众号profile页时，如果发现上一次拉取菜单的请求在5分钟以前，就会拉取一下菜单，如果菜单有更新，就会刷新客户端的菜单。测试时可以尝试取消关注公众账号后再次关注，则可以看到创建后的效果。
    </li>
    <li style="color:red">自定义菜单(click类型)如需实现自动回复，请将关键词(key)设置为以“REPLY_”开头(去掉双引号)。</li>
    <li style="color:red">自定义菜单(view类型)的链接(url)可从文章内容板块复制链接过来。</li>
  </ol>
  </p>
  <br>
  <p>
    <strong>自定义菜单接口可实现多种类型按钮，如下：</strong>
  <ol class="notice-list">
    <li>
      click：点击推事件用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
    </li>
    <li>
      view：跳转URL用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。
    </li>
  </ol>
  </p>
</div>


<script>
  $('.editable-text').editable({
    emptytext: '无内容',
    success: function (response, newValue) {
      // if field is sort, reload
      if ($(this).data('pk').indexOf('sort') > 0) {
        window.location.reload();
      }
    }
  });
  function del(menu_id) {
    layer.confirm('此操作不可恢复，确定要执行吗？', function (index) {
      $.post('/site/index.php?act=weixin/backend&op=del_menu', {menu_id: menu_id}, function () {
        window.location.reload();
      });
    });
  }
  function add(o, p) {
    var html = $('<tr height="30" class="hover s_jishuhang">\
      <td scope="row" align="center"><a href="javascript:" style="color:red" onclick="$(this).closest(\'tr\').remove()" title="删除">×</a></td>\
      <td scope="row" style="padding-left:10px">' + (p && '&gt; ' || '') + '<input type="text"></td>\
      <td scope="row" align="center"><select><option value="view">view</option><option value="click">click</option></select></td>\
      <td scope="row" align="center"><input type="text"></td>\
      <td scope="row" align="center"><input type="text"></td>\
      <td scope="row" align="center"><input type="text"></td>\
      <td scope="row" align="center"><button onclick="save(this,' + p + ')">保存</button></td>\
      </tr>');
    if (o) {
      html.insertBefore(o.closest('tr'));
    } else {
      html.appendTo($('#testing1'));
    }

    setTimeout(function(){
      $('input:eq(0)', html).focus();
    },50);
  }
  function save(o, p) {
    var t = o.closest('tr'), data = {
      parent: p != undefined && p || 0,
      name: $('input:eq(0)', t).val(),
      type: $('select', t).val(),
      key: $('input:eq(1)').val(),
      url: $('input:eq(2)').val(),
      sort: $('input:eq(3)').val()
    };

    $.post('/site/index.php?act=weixin/backend&op=add_menu', data, function () {
      window.location.reload();
    });
  }
  function build_menu() {
    layer.confirm('生成菜单失败可能会影响现有的用户，确定要生成吗？', function (index) {
      $.getJSON('/site/index.php?act=weixin/index&op=setMenu', function (res) {
        layer.alert(res && res.errmsg == 'ok' && '菜单生成成功！24小时内生效。' || '菜单生成失败，请通过控制台查看具体信息。', function () {
          layer.closeAll();
        });
      });
    });
  }
</script>