<?php defined('InOmniWL') or exit('Access Invalid!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1">
            <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
                <title><?php echo $output['html_title']; ?></title>
                <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.js"></script>
                <script type="text/javascript">
                    RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL; ?>';
                    SITE_TEMPLATES_URL = '<?php echo SITE_TEMPLATES_URL; ?>';
                    LOADING_IMAGE = "<?php echo SITE_TEMPLATES_URL . DS . 'images/loading.gif'; ?>";
                </script>
                </head>
                <body>
                    <div id="append_parent"></div>
                    <div id="ajaxwaitid"></div>
                    <?php
                    require_once($tpl_file);
                    ?>
                    <?php if ($GLOBALS['setting_config']['debug'] == 1) { ?>
                        <div id="think_page_trace" class="trace">
                            <fieldset id="querybox">
                                <legend><?php echo $lang['nc_debug_trace_title']; ?></legend>
                                <div>
                                    <?php print_r(Tpl::showTrace()); ?>
                                </div>
                            </fieldset>
                        </div>
                    <?php } ?>
                </body>
                </html>