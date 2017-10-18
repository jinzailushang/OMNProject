<?php
/**
 * @version 纵腾转运配置文件
 * @copyright (c) 2016-04-14, coolzbw
 */
defined('IN_TRANSPORT_QU') or exit('Access Invalid!');

////测试环境
//$api_config = array(
//    'api_status'=> true,//接口是否通，不通用测试数据
//    'location'=> 'http://202.104.134.94:8021/api/',
//    'appToken'=> '65421499c3874bd3bc7ecf9be153fc74',
//    'apKey'=> '65421499c3874bd3bc7ecf9be153fc74',
//    'key'=> '12345678',
//    'CustomerIdentity'=>'TMDXKC',//'客户标识'
//);

//真实环境
$api_config = array(
    'api_status'=> true,//接口是否通，不通用测试数据
    'location'=> 'http://58.96.183.96:8018/api/',
    'appToken'=> '873e7b4dfff64acbace1863a6b355eaf',
    'apKey'=> '13e0c2fe62354f59a09a26ed272c521b',
    'key'=> 'f5cxOhaT',//f5cxOhaTTlJQTo4PDVO/BsrskVU=
    'CustomerIdentity'=>'RRLNWZ',//'客户标识'
);


