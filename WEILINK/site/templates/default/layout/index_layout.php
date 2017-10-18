<?php defined('InOmniWL') or exit('Access Invalid!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html;" charset="<?php echo CHARSET; ?>">
            <title>威廉管理系统</title>
            <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/global_new.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/style_new.css" rel="stylesheet" type="text/css" />
            
            <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/jquery.1_9_1_min.js"></script>
            <script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/layer.js"></script>
            <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/laydate/laydate.js"></script>
            <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!--[if lt IE 9]>
                  <script src="<?php echo RESOURCE_SITE_URL; ?>/js/html5shiv.js"></script>
                  <script src="<?php echo RESOURCE_SITE_URL; ?>/js/respond.min.js"></script>
            <![endif]-->
            <script language="javascript">
                $(document).ready(function () {
                    var h = document.body.clientHeight - 175;
                    $('.center').css('height', h);
                    $('.pro-center-box').css('height', h - 105);
                    //$('.pro-center-con').css('height', h);

                    /*VERTICAL MENU*/
                    $(".left-main li ul").each(function () {
                        $(this).parent().addClass("parent");
                    });

                    $(".left-main li ul li.active").each(function () {
                        $(this).parent().show().parent().addClass("open");
                    });

                    $(".left-main").delegate(".parent > a", "click", function (e) {
                        $(".left-main .parent.open > ul").not($(this).parent().find("ul")).slideUp(300, 'swing', function () {
                            $(this).parent().removeClass("open");
                        });

                        var ul = $(this).parent().find("ul");
                        ul.slideToggle(300, 'swing', function () {
                            var p = $(this).parent();
                            if (p.hasClass("open")) {
                                p.removeClass("open");
                            } else {
                                p.addClass("open");
                            }
                        });
                        e.preventDefault();
                    });
                    var sitebar = "<?php echo $_GET['act'].'--'.$_GET['op'] ?>", sitebar_e = $('#'+sitebar.replace('/','\\/'));
                    if (sitebar_e.length>0) {
                       sitebar_e.closest('ul').show();
                       sitebar_e.children('a').addClass('active');
                    }
                });
                var SITE_SITE_URL = '<?php echo SITE_SITE_URL; ?>';
                function showAndHide(obj,types){
                        var Layer=window.document.getElementById(obj);
                        switch(types){
                  case "show":
                        Layer.style.display="block";
                  break;
                  case "hide":
                        Layer.style.display="none";
                  break;
                }
                  }
                function getVal(obj,str){
                    $('#'+obj).val(str);
                }
            </script>
            <script type="text/javascript" src="<?php echo SITE_TEMPLATES_URL; ?>/js/function.js"></script>
    </head>
    <?php
    $menu = require(BASE_PATH . '/include/menu.php');
    $permission = '';
    if ($output['admin_info']['sp'] == 0) {
        $permission = $output['permission'];
    }
    ?>

    <body>

        <div id="header">
            <div class="top-box">
                <div class="logo">
                    <h1><a href=""><img src="<?php echo SITE_TEMPLATES_URL; ?>/images/logo.png" /><?php echo $output['html_title']; ?></a></h1>
                </div>
                <div class="information">
                    <!--<div class="searchBar">
                        <a class="st_camera_on" href="#"></a>
                        <input type="text" id="searchTxt" value="Search" />
                    </div>-->
                    <ul>
                        <li><?php echo date('Y-m-d') ?> <?php $weekarray = array("日", "一", "二", "三", "四", "五", "六");
    echo '星期' . $weekarray[date('w')]
    ?></li>

                        <li><span>|</span><a href="index.php?act=index&op=logout">退出</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="content-box">

            <div class="content-left">
                <div class="left-title">
                    <div class="side-user">
                        <div class="avatar"><img src="<?php echo SITE_TEMPLATES_URL; ?>/images/avatar1_50.jpg" alt="Avatar" /></div>
                        <div class="info">
                            <?php
//                            $user_info = Model('user')->getUserInfo(array('user.u_id'=>$output['admin_info']['id']));
//                            $uname = $user_info['first_name'] || $user_info['last_name'] ? $user_info['first_name'] . $user_info['last_name'] : '游客';
                            $user_info = Model('consignor')->getConsignorInfo(array
                            ('u_id'=>$output['admin_info']['id']));
                            $uname = $user_info['name'] ? $user_info['name'] : '游客';

                            ?>
                            <a href="#"><?php echo $uname; ?></a>
                            <a href="<?php echo urlShop('user','modify_pwd')?>"><span>修改密码</span></a>
                        </div>
                    </div>
                </div>
                <div class="left-main-box nscroller">
                    <div class="left-main" id="oauth_mun">
                        <ul id="navWrap">

                            <li class="parent"><a href="#"><span class="ico-product"></span>用户管理 <i class="ico ico-arrow"></i></a>
                                <ul class="lower sub-menu">
                                    <li id="user--index"><a href="index.php?act=user&amp;op=index">个人中心</a></li>
                                    <li id="consignee--index"><a href="index.php?act=consignee&amp;op=index">收件人管理</a></li>
                                    <li id="trans_house--index"><a href="index.php?act=trans_house&amp;op=index">发货中转仓</a></li>
                                    <li id="goods--index"><a href="index.php?act=goods&amp;op=index">商品管理</a></li>
                                </ul>

                            </li>


                            <li class="parent"><a href="#"><span class="ico-trad"></span>转运服务 <i class="ico ico-arrow"></i></a>
                                <ul class="lower sub-menu">
                                    <li id="order_tp--index"><a href="index.php?act=order_tp&amp;op=index">转运运单</a></li>
                                </ul>

                            </li>


                            <li class="parent"><a href="#"><span class="ico-question-help"></span>问题与帮助 <i class="ico ico-arrow"></i></a>
                                <ul class="lower sub-menu">
                                    <li id="faq--index"><a href="index.php?act=faq&amp;op=index">常见问题</a></li>
                                    <li id="guide--index"><a href="index.php?act=guide&amp;op=index">下单指引</a></li>
                                </ul>

                            </li>


                            <li class="parent"><a href="#"><span class="ico-system"></span>系统管理 <i class="ico ico-arrow"></i></a>
                                <ul class="lower sub-menu">
                                    <li id="shipment_code--index"><a href="index.php?act=shipment_code&amp;op=index">物流单号管理</a></li>
                                    <li id="member--index"><a href="index.php?act=member&amp;op=index">会员管理</a></li>
                                    <li id="trans_house--setting"><a href="index.php?act=trans_house&amp;op=setting">中转仓设置</a></li>
                                </ul>

                            </li>


                            <li class="parent"><a href="#"><span class="ico-official-accounts"></span>微信公众号 <i class="ico ico-arrow"></i></a>
                                <ul class="lower sub-menu">
                                    <li id="weixin/backend--reply_list"><a href="index.php?act=weixin/backend&amp;op=reply_list">自动回复</a></li>
                                    <li id="weixin/backend--article_list"><a href="index.php?act=weixin/backend&amp;op=article_list">文章内容</a></li>
                                    <li id="weixin/backend--menu_list"><a href="index.php?act=weixin/backend&amp;op=menu_list">自定义菜单</a></li>
                                </ul>

                            </li>
                        </ul>
                    </div><div class="clear"></div>
                </div>
            </div>
            <div class="content-right">
                <div class="top-last">
                    <div class="top-lastl"><strong style="color:#f25d00;">您所在的位置：</strong><?php echo $output['position'] ?></div>
                </div>
                <?php
                require_once($tpl_file);
                ?>
            </div>
            <div class="clear"></div>
        </div>

        <div class="footer">© Copyright 2016 威廉速递 Inc. </div>

        <div style="left: -2px; top: 0px; width: 100%; height: 100%; display: none;" id="loading-mask">
            <p id="loading_mask_loader" class="loader"><img alt="Loading..." src="<?php echo SITE_TEMPLATES_URL; ?>/images/ajax-loader-tr.gif" /><br /> Please wait...</p>
        </div>
    </body>
</html>

