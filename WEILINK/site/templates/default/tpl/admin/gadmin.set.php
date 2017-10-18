<?php defined('InOmniWL') or exit('Access Invalid!');?>

<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/attrpage.css" rel="stylesheet" type="text/css" />
<div class="btn-main">	
    <ul>
        <li><input type="submit" onclick="psubmit()" style="cursor: pointer;" value="提交 " class="button"></li>
        <li><input type="reset" onclick="location.reload()" style="cursor: pointer;" value="重置" class="button"></li>
        <li><input type="reset" onclick="javascript:history.go(-1)" style="cursor: pointer;" value="返回" class="button"></li>
    </ul>	
</div>
<div class="center">
    <div class="navTwo_cent">
        <ul class="navTwo_menu">
            <li><a href="javascript:void(0);" id="tabs_all" onclick="changeTabs(1, this)">管理员<font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_noApp" onclick="changeTabs(2, this)"  >添加管理员 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(3, this)" class="choose">权限组 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(4, this)">添加权限组 <font color="red"></font></a></li>
        </ul>
    </div>

    <div class="pro-center-box">
        <div class="main-col">
            <form id="house_form"  method="post" action="">
            <input type="hidden" name="form_submit" value="ok" />
            <div class="entry-edit-head"><input id="limitAll" id="limitAll" value="1" type="checkbox" onclick="selectAll(this)">权限设置 </div>
            <div class="fieldset" id="fieldset1" style="display:block">
                <div class="conWrap" id="div_category">
                    <table border="0" cellspacing="0" cellpadding="0" id="tab1">
                        <?php if($output['gadmin_list']):?>
                            <tr class="noborder">
                                <td class="vatop rowform">所属权限组： <select name="gid" id="gid" style="float: none">
                                    <option value=""><?php echo $lang['nc_please_choose']; ?></option>
                                    <?php foreach($output['gadmin_list'] as $k1=>$v1):?>
                                    <option value="<?php echo $v1['gid']?>" <?php if($output['ginfo']['parent_id'] == $v1['gid']) echo 'selected'?> /><?php echo $v1['gname']?></option>
                                    <?php endforeach;?>
                                </select></td>
                                <td class="vatop tips"></td>
                            </tr>
                        <?php endif;?>
                            
                        <tr><td>权限组名称： <input type="text" id="gname" value="<?php echo $output['ginfo']['gname'];?>" maxlength="40" name="gname" class="txt" style="float:none !important;width:250px !important"></td></tr>
                        <?php foreach((array)$output['limit'] as $k => $v) { ?>
                            <tr class="qxz">
                              <td>
                              <label style="width:100px"><?php echo (!empty($v['nav'])) ? $v['nav'] : '&nbsp;'; ?></label>
                              <input id="limit<?php echo $k;?>" type="checkbox" onclick="selectLimit('limit<?php echo $k;?>')" style="width:20px !important">
                                <label for="limit<?php echo $k;?>"><b><?php echo $v['name'];?></b>&nbsp;&nbsp;</label>
                                <ul style="padding-left:40px">
                                  <?php foreach($v['child'] as $xk => $xv) { ?>
                                    <!--如果是超级管理员，则列出所有权限-->
                                    <?php if($output['gid'] == 1):?>
                                        <li style="width:100%;height:15px;padding:5px 0">
                                        <label style="float:left;width:100px"><input nctype="limit<?php echo $k;?>" class="limit<?php echo $k;?>" type="checkbox" onclick="aa(this)" name="permission[]" value="<?php echo $xv['op'];?>" 
                        <?php if(in_array(substr($xv['op'],0,($t=strpos($xv['op'],'|'))?$t:100),$output['ginfo']['limits'])){ echo "checked=\"checked\""; }?> style="width:20px !important">
                                            <b><?php echo $xv['name'];?></b>&nbsp;</label>
                                            <?php if($xv['sub']):?>
                                                <?php foreach($xv['sub'] as $sk=>$sv):?>
                                                <label style="float:left;width:100px"><input nctype="limit<?php echo $sk;?>" class="limit<?php echo $k;?>" type="checkbox" name="permission[]" value="<?php echo $sk;?>" <?php if(in_array($sk,$output['ginfo']['limits'])){ echo "checked=\"checked\""; }?> style="width:20px !important"><?php echo $sv;?>&nbsp;</label>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </li>
                                    <?php endif;?>
                                    
                                    <!--如果是非超级管理员，则列出其所拥有权限-->
                                    <?php if($output['gid'] != 1 && in_array($xv['op'],$output['hlist'])):?>
                                    <li style="width:100%;height:15px;padding:5px 0">
                                    <label style="float:left;width:100px"><input nctype="limit<?php echo $k;?>" class="limit<?php echo $k;?>" type="checkbox" onclick="aa(this)" name="permission[]" value="<?php echo $xv['op'];?>" 
                                    <?php if(in_array(substr($xv['op'],0,($t=strpos($xv['op'],'|'))?$t:100),$output['ginfo']['limits'])){ echo "checked=\"checked\""; }?> style="width:20px !important">
                                        <b><?php echo $xv['name'];?></b>&nbsp;</label>
                                        <?php if($xv['sub']):?>
                                            <?php foreach($xv['sub'] as $sk=>$sv):?>
                                            <?php if(in_array($sk,$output['hlist'])):?>
                                             <label style="float:left;width:100px"><input nctype="limit<?php echo $sk;?>" class="limit<?php echo $k;?>" type="checkbox" name="permission[]" value="<?php echo $sk;?>" <?php if(in_array($sk,$output['ginfo']['limits'])){ echo "checked=\"checked\""; }?> style="width:20px !important"><?php echo $sv;?>&nbsp;</label>
                                            <?php endif;?>
                                                 <?php endforeach;?>
                                        <?php endif;?>
                                    </li>
                                    <?php endif;?>
                                    
                                  <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>	

<script type="text/javascript">
    $(document).ready(function () {
        $('.qxz').each(function(){
            var li = $(this).find('ul>li').length;
            if(li == 0){
                $(this).remove();
            }
        });
    });
  function psubmit(){
        $('#house_form').submit();
    }
function selectAll(obj){
    if($(obj).is(':checked')){
        $('input[type="checkbox"]').prop('checked',true);
    }else{
        $('input[type="checkbox"]').prop('checked',false);
    }
}
function aa(obj){
    if($(obj).is(':checked')){
        $(obj).closest('li').find('input').prop('checked',true);
    }else{
        $(obj).closest('li').find('input').prop('checked',false);
    }
}
function selectLimit(name){
    if($('#'+name).prop('checked')) {
        $('.'+name).prop('checked',true);
    }else {
       $('.'+name).prop('checked',false);
    }
}
//切换标签
function changeTabs(v, obj) {
    $('.navTwo_menu>li>a').removeClass('choose');
    $(obj).addClass('choose');
    if(v == 1){
        location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin";
    }else if(v == 2){
        location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin_add";
    }else if(v == 3){
        location.href = SITE_SITE_URL + "/index.php?act=admin&op=gadmin";
    }else{
        location.href = SITE_SITE_URL + "/index.php?act=admin&op=gadmin_add";
    }
}    
</script>
