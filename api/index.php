<?php


/**
 * temp初始化文件
 * copyright 2016-03-03, jack
 * */

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));

if (!@include(dirname(dirname(__FILE__)) . '/global.php'))
    exit('global.php isn\'t exists!');
if (!@include(BASE_CORE_PATH . '/xoms.php'))
    exit('xoms.php isn\'t exists!');
if (!@include(BASE_PATH . '/control/control.php'))
    exit('control.php isn\'t exists!');     

define('TPL_NAME', TPL_SITE_NAME);
define('SITE_TEMPLATES_URL', SITE_SITE_URL . '/templates/' . TPL_NAME);
define('BASE_TPL_PATH', BASE_PATH . '/templates/' . TPL_NAME);

Base::run();
?>
