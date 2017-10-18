<?php defined('InOmniWL') or exit('Access Invalid!'); ?>

<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/attrpage.css" rel="stylesheet" type="text/css" />

<div class="btn-main">	
    <ul>
        <li><input type="submit" onclick="psubmit()" style="cursor: pointer;" value="提交 " class="button"></li>
        <li><input type="reset" onclick="javascript:history.go(-1)" style="cursor: pointer;" value="返回" class="button"></li>
    </ul>	
</div>

<div class="center">
    <div class="goods-category-box">
        <div class="setting base">
            <form id="base_form"  method="post" action="">
                <input type="hidden" name="form_submit" value="ok" />
                <div class="details-wrap">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="tab1">	
                        <tr>
                            <th scope="row" style="width:200px"><?php echo $lang['cache_cls_seting']; ?>：</th>
                            <td><input type="checkbox" name="cache[]" value="setting" ></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo $lang['cache_cls_table']; ?>：</th>
                            <td><input type="checkbox" name="cache[]" value="table" ></td>
                        </tr>

                    </table>
                </div>
            </form>
        </div>

    </div>
</div>

<script type="text/javascript">
    function psubmit() {
        $('#base_form').submit();
    }


</script>


