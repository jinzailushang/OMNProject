<link href="templates/default/jqueryui-editable-1.5.1/jquery-ui-1.9.2.custom/css/base/jquery-ui-1.9.2.custom.min.css"
      rel="stylesheet">
<script
  src="templates/default/jqueryui-editable-1.5.1/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="templates/default/jqueryui-editable-1.5.1/jqueryui-editable/css/jqueryui-editable.css" rel="stylesheet">
<script src="templates/default/jqueryui-editable-1.5.1/jqueryui-editable/js/jqueryui-editable.js"></script>
<style>
  .editable-text.editable.editable-click.editable-empty {
    color: #ccc;
    font-weight: normal;
  }
</style>
<div class="operationsbox">
  <ul class="operNav">
    <li><a class="btn-enter" href="javascript:add();"><i class="ico-export-or"></i>添加文章</a></li>
  </ul>
</div>
<table id="testing1" style="width:1200px;margin-left:7px;margin-top:5px" border="0" cellspacing="0" cellpadding="0" tab-group="1" class="order-table-box">
  <tbody>
  <tr class="order-th s_oushuhang">
    <th scope="col" nowrap="nowrap" style="width:60px">#</th>
    <th scope="col" nowrap="nowrap">标题</th>
    <th scope="col" nowrap="nowrap">链接</th>
    <th scope="col" nowrap="nowrap" style="width:150px">添加时间</th>
    <th scope="col" nowrap="nowrap" style="width:70px">操作</th>
  </tr>
  <?php if($output['article_list']) foreach ($output['article_list'] as $article) {?>
  <tr height="30" class="hover s_jishuhang" data-has-identity="1">
    <td scope="row" align="center"><?php echo $article['article_id']?></td>
    <td scope="row" align="center"><?php
      $title = '';
      //  抓取h1/h2/h3/h4/h5/strong
      if (FALSE !== strpos($article['content'],'<h1>')) {
        $title = preg_replace('/.*?<h1[^>]*>(.*?)<\/h1>.*/is', '$1', $article['content']);
      } elseif (FALSE !== strpos($article['content'],'<h2')) {
        $title = preg_replace('/.*?<h2[^>]*>(.*?)<\/h2>.*/is', '$1', $article['content']);
      } elseif (FALSE !== strpos($article['content'],'<h3')) {
        $title = preg_replace('/.*?<h3[^>]*>(.*?)<\/h3>.*/is', '$1', $article['content']);
      } elseif (FALSE !== strpos($article['content'],'<h4')) {
        $title = preg_replace('/.*?<h4[^>]*>(.*?)<\/h4>.*/is', '$1', $article['content']);
      } elseif (FALSE !== strpos($article['content'],'<h5')) {
        $title = preg_replace('/.*?<h5[^>]*>(.*?)<\/h5>.*/is', '$1', $article['content']);
      } elseif (FALSE !== strpos($article['content'],'<strong')) {
        $title = preg_replace('/.*?<strong[^>]*>(.*?)<\/strong>.*/is', '$1', $article['content']);
      } elseif (FALSE !== strpos($article['content'],'<p')) {
        $title = preg_replace('/.*?<p[^>]*>(.*?)<\/p>.*/is', '$1', $article['content']);
      }
      $title = preg_replace('/(<[^>]+>|&nbsp;|\s)+/is', '', $title);

      if (!$title) {
        $title = mb_substr(preg_replace('/(<[^>]+>|&nbsp;|\s)+/is', '', $article['content']),0,15);
      }
      echo $title;
      ?></td>
    <td scope="row" align="center">http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?act=weixin/index&op=article_detail&article_id='.$article['article_id']?>
      <a href="/site/index.php?act=weixin/index&op=article_detail&article_id=<?php echo $article['article_id']?>" target="_blank">[预览]</a></td>
    <td scope="row" align="center"><?php echo date('Y-m-d H:i', $article['post_time'])?></td>
    <td scope="row" align="center"><button onclick="edit(<?php echo $article['article_id']?>)">编辑</button> <button onclick="del(<?php echo $article['article_id']?>)">删除</button></td>
  </tr>
  <?php }?>
  </tbody>
</table>

<script>
  $('.editable-text').editable({
    emptytext: '无内容'
  });
  function edit(article_id) {
    layer.open({
      type: 2,
      title: '编辑文章',
      area: ['1000px', '780px'],
      content: '<?php echo SITE_TEMPLATES_URL ?>/tpl/weixin/index.html#'+article_id
    })
  }
  function add() {
    layer.open({
      type: 2,
      title: '添加文章',
      area: ['1000px', '780px'],
      content: '<?php echo SITE_TEMPLATES_URL ?>/tpl/weixin/index.html'
    })
  }
  function del(article_id) {
    layer.confirm('此操作不可恢复，确定要执行吗？', function (index) {
      $.post('/site/index.php?act=weixin/backend&op=del_article', {article_id: article_id}, function () {
        window.location.reload();
      });
    });
  }
</script>