<style>
  .tips{max-height: 400px;overflow: auto}
</style>
<div class="row-file">
  <div class="file-form">
    <div class="file-form-list">
      <form id="add_form" method="post" enctype="multipart/form-data" action="<?php echo urlShop('goods','uploadImport')?>" target="formsme">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="type" value="<?php echo $_GET['type']?>" />
        <p class="file-p">请按照数据模板的格式准备导入数据，模板中的表头名称不可更改，表头行不能删除。</p>
        <div class="fileBtn"><a href="<?php echo urlShop('goods', 'upload_eg');?>" target="_blank" class="downloadBtn"><i class="ico-but"></i>下载模板</a></div>
        <div class="file-input-box">
          <p>请选择需要导入的Excel文件</p>
          <div class="file1">
            <span class="input-box"><input id='file_xls' name="file_xls" type="file"  class="input-file" /> </span><button class="uploadBtn" id="btn_add">上传</button>
          </div>
        </div>
      </form>
      <IFRAME  height=0 marginHeight=0 marginWidth=0 scrolling=no width=0 name="formsme"  style="display:none"></IFRAME>
      <div class="instructions tips">
      </div>
      <div class="instructions">
        <P>导入规则说明：</P>
        <P>1.文件后缀名必须为：xls或xlsx（即Excel格式），文件大小不得大于2M</P>
        <P>2.*号为必填项</P>
        <P></p>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function(){
    //添加按钮的单击事件
    $("#btn_add").click(function(){
      $('#loading-mask').show();
      $("#add_form").submit();
    });
  });
  function callback(res) {
    $('#loading-mask').hide();
    if (res.status == 1) {
      var html = '<p style="color:green">成功导入'+res.snum+'条！</p><p style="color:red">失败'+res.fnum+'条！</p><p>'+(res.error!=undefined&&res.error||'')+'</p>';
      $('.tips').html(html);
    } else {
      layer.alert(res.msg,{icon:2});
    }
  }
</script>
