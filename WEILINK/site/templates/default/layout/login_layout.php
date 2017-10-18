<?php defined('InOmniWL') or exit('Access Invalid!'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
            <title><?php echo $output['html_title']; ?></title>
            <script type="text/javascript" SRC="<?php echo SITE_TEMPLATES_URL; ?>/js/jquery.1_9_1_min.js"></script>
			<script>
				$(function(){
					$(".login").click(function(){
						$("#login").show();
						$("#resiger").hide();
						$(this).removeClass("on");
						$(this).siblings().addClass("on")
					})
					$(".resiger").click(function(){
						$("#login").hide();
						$("#resiger").show();
						$(this).removeClass("on");
						$(this).siblings().addClass("on")
					})	
				})
			</script>
            <link href="<?php echo SITE_TEMPLATES_URL; ?>/css/login.css" rel="stylesheet" type="text/css">
    </head>
	<body>
		<header>
			<div class="top">
				<div class="w1000 por">
					<div class="logo poa">
						<a href="<?php echo SITE_SITE_URL?>">
							<img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/logo.png" />
						</a>
					</div>
					<div class="topimg poa">
						<img src="<?php echo SITE_TEMPLATES_URL; ?>/images/login/top-nav.png" />
					</div>
				</div>
			</div>
		</header>
		<?php
		require_once($tpl_file);
		?>
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
