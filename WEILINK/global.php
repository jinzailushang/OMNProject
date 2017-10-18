<?php
/**
 * 统一入口，进行初始化信息
 * copyright 2015-06-02, jack
 */
error_reporting(E_ALL & ~E_NOTICE);
define('BASE_ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
define('BASE_CORE_PATH', BASE_ROOT_PATH . '/core');
define('BASE_DATA_PATH', BASE_ROOT_PATH . '/data');
define('DS', '/');
define('InOmniWL', true);
define('StartTime', microtime(true));
define('TIMESTAMP', time());
define('DIR_CIRCLE', 'circle');
define('DIR_API', 'api');
define('DIR_MOBILE', 'mobile');
define('DIR_UPLOAD', 'data/upload');
define('XLSX_TPL', 'data/upload/xlsx_tpl');
define('XLSX_TEMP', 'data/upload/xlsx_temp');
define('TPL_SITE_NAME', 'default');
//转移到后台的配置文件
define('ATTACH_MOBILE', 'mobile');
define('ATTACH_CIRCLE', 'circle');
//页码设置
define('CURPAGE', 1);
define('PAGE_SIZE', 20);
//密钥
define('SECRET_KEY', 'welink_v1.0');
define('COMMON_PWD', '123456');
define('EMPTY_STR', '--');
