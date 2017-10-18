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
            <li><a href="javascript:void(0);" id="tabs_noApp" onclick="changeTabs(2, this)"  class="choose">添加管理员 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(3, this)">权限组 <font color="red"></font></a></li>
            <li><a href="javascript:void(0);" id="tabs_canApp" onclick="changeTabs(4, this)">添加权限组 <font color="red"></font></a></li>
        </ul>
    </div>
    <div class="operationsbox"></div>
    <div class="pro-center-box">
        <form id="house_form"  method="post" action="">
            <div class="rowForm">

                <input type="hidden" name="form_submit" value="ok" />
                <input type="hidden" name="admin_id" value="<?php echo $output['admininfo']['admin_id']; ?>" />
                <div class="row-content">
                    <fieldset>
                        <legend>基础信息</legend>
                        <div class="conInfor">
                            <div class="j_item">
                                <label>登录名<em>*</em></label>
                                <input type="text"  value="<?php echo $output['admininfo']['admin_name']; ?>" name="admin_name" id="admin_name"/>
                            </div>
                            <div class="j_item">
                                <label>名字</label>
                                <input name="" type="text" />
                            </div>	
                            <div class="j_item">
                                <label>姓氏</label>
                                <input name="" type="text" />
                            </div>
                            <div class="j_item">
                                <label>邮件地址</label>
                                <input name="email" type="text" value=""/>
                            </div>

                            <div class="j_item">
                                <label>权限组<em>*</em></label>
                                <select name="gid" id="gid" onchange="show_sub(this, 1)" style="display:none" class="sa">
                                    <option value=""><?php echo $lang['nc_please_choose']; ?></option>
                                    <?php foreach ((array) $output['gadmin'] as $v) { ?>
                                        <option value="<?php echo $v['gid']; ?>"><?php echo $v['gname']; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="sa3"></span><?php echo $output['admininfo']['gname']; ?> <span><a href="javascript:;"  style=" text-decoration: underline;" onclick="resetgid(this, 1)">编辑</a></span>
                                <input type="hidden" name="qx_flag" id="qx_flag" value="1" />

                            </div>

                        </div>

                    </fieldset>
                </div>
                <div class="row-content">
                    <fieldset>
                        <legend>密码认证</legend>
                        <div class="conInfor">
                            <div class="j_item">
                                <label>密码<em>*</em></label>
                                <input type="password"  value="" name="admin_password" id="admin_password"/>
                                <p class="prom">至少需要8个字符</p>
                            </div>
                            <div class="j_item">
                                <label>确认<em>*</em></label>
                                <input type="password"  value="" name="admin_rpassword" id="admin_rpassword"/>
                            </div>	
                        </div>

                    </fieldset>
                </div>
            </div>

        </form>
    </div>
</div>	
<script type="text/javascript">
    function psubmit() {
        console.log('here');
        var admin_name = $('#admin_name').val(), admin_password = $('#admin_password').val(), admin_rpassword = $('#admin_rpassword').val(), gid = $('#gid').val(), qx_flag = $('#qx_flag').val();
        if (!admin_name) {
            alert('请填写登录名！');
            return false;
        }
        if (qx_flag == 2 && !gid) {
            alert('请选择权限组！');
            return false;
        }

        if (admin_password && admin_rpassword && admin_password != admin_rpassword) {
            alert('两次密码不一致！');
            return false;
        }
        console.log('here2');
        $('#house_form').submit();
    }
    function selectAll(obj, n) {
        if (n == 0) {
            $('.selector').prop('checked', true);
            $(obj).closest('div').html('<label><input class="input" name="" type="checkbox" onclick="selectAll(this,1)" />全选</label>');
        } else {
            $('.selector').prop('checked', false);
            $(obj).closest('div').html('<label><input class="input" name="" type="checkbox" onclick="selectAll(this,0)" />全选</label>');
        }
    }
    //切换标签
    function changeTabs(v, obj) {
        $('.navTwo_menu>li>a').removeClass('choose');
        $(obj).addClass('choose');
        if (v == 1) {
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin";
        } else if (v == 2) {
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin_add";
        } else if (v == 3) {
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=gadmin";
        } else {
            location.href = SITE_SITE_URL + "/index.php?act=admin&op=admin_add";
        }
    }
    function show_sub(obj, m) {
        var url = SITE_SITE_URL + "/index.php?act=admin&op=get_gadmin", gid = $(obj).val();
        var level = parseInt(m) + 1;
        if (gid > 0 && level <= 3) {
            var html = '<label style="width:93px" class="sa1"><em>&nbsp;&nbsp;</em></label><select name="gid' + m + '" onchange="show_sub(this,' + level + ')" class="sa1">';
            $.get(url, {gid: gid}, function (res) {
                res = $.parseJSON(res);
                if (res.status) {
                    $(obj).nextAll('select').remove();
                    html += '<option value=""><?php echo $lang['nc_please_choose']; ?></option>';
                    for (var i in res.data) {
                        html += '<option value="' + res.data[i].gid + '">' + res.data[i].gname + '</option>';
                    }
                    html += '</select>';
                    $(obj).after(html);
                }
            });
        } else {
            $(obj).nextAll('select').remove();
        }
    }
    function resetgid(obj, n) {
        if (n == 1) {
            $('.sa').show();
            $('.sa3').html('<label style="width:93px" class="sa1"><em>&nbsp;&nbsp;</em></label>');
            $(obj).parent().html('<a href="javascript:;"  style=" text-decoration: underline;" onclick="resetgid(this,2)">还原</a>');
            $('#qx_flag').val(2);
        } else {
            $('.sa').hide();
            $('.sa1').remove();
            $('.sa3').empty();
            $('#gid').val('');
            $(obj).parent().html('<a href="javascript:;"  style=" text-decoration: underline;" onclick="resetgid(this,1)">编辑</a>');
            $('#qx_flag').val(1);
        }

    }

</script>