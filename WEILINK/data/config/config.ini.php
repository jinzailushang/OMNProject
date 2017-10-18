<?php
/**
 * copyright 2016-06-23, jack
 **/
defined('InOmniWL') or exit('Access Invalid!');

$config = array();
$config['site_site_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/site';
$config['upload_site_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/data/upload';
$config['resource_site_url']	= 'http://'.$_SERVER['SERVER_NAME'].'/data/resource';
$config['version'] = '201605042490';
$config['setup_date'] = '2016-05-04 20:34:08';
$config['gip'] = 0;

$config['session_expire'] = 3600;
$config['lang_type'] = 'zh_cn';
$config['cookie_pre'] = 'FEF9_';
$config['tpl_name'] = 'default';
$config['thumb']['cut_type'] = 'gd';
$config['thumb']['impath'] = '';
$config['cache']['type'] = 'file';

$config = array_merge($config, require __DIR__. '/static.ini.php');