<?php defined('InOmniWL') or exit('Access Invalid!'); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="<?php echo CHARSET; ?>">
        <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/login.css" rel="stylesheet" type="text/css">
		<link href="<?php echo SITE_TEMPLATES_URL; ?>/css/style_new.css" rel="stylesheet" type="text/css">
        <title><?php echo $output['html_title']; ?></title>
		<script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/jquery.1_9_1_min.js"></script>
    </head>

    <body>
		<header>
			<div class="top">
				<div class="w1000 por">
					<div class="logo poa">
						<a href="#">
							<img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/logo.png" />
						</a>
					</div>
					<div class="topimg poa">
						<img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/top-nav.png" />
					</div>
				</div>
			</div>
		</header>
		<div class="containerbox">
			<div class="successfully">
				
					<h4 class="emhead">
						<div class="<?php if($output['msg_type'] == 'succ'){ ?> success-img <?php }else{?> error-img <?php }?>"><?php echo $output['msg']; ?></div> 
						<!--<p>若不选择将自动跳转<a href="<?php echo $output['url'] ?>">返回上一页</a></p>-->
					</h4>
					<script type="text/javascript"> window.setTimeout("javascript:location.href='<?php echo $output['url']; ?>'", '<?php echo $time; ?>');</script>
			</div>
		</div>
		<footer>
			<div class="foot-box">
				<div class="w1000 por">
					<div class="foot-logo poa"><a href="index.html"><img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/foot_logo.png"></a></div>
					<div class="foot-text poa">
						<p><span>友情链接</span></p>
						<div class="foot-nav clear">
							<a target="_blank" href="http://xianlife.com">鲜LIFE</a>
							<span class="sep">|</span>
							<a target="_blank" href="http://elly.xianlife.com">爱莉颜芝</a>
							<span class="sep">|</span>
							<a target="_blank" href="http://www.my-worldstore.com">世界商店</a>
						</div>
						<p>Copyright © 2016 welinkexpress.com Inc</p>
					</div>
					<div class="foot-code poa"><img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/foot_code.png"></div>
				</div>
			</div>
		</footer>
    </body>
</html>