<?php

/**
 * 载入权限
 * @copyright 2016-05-23
 */
defined('InOmniWL') or exit('Access Invalid!');

return array(
  array(
    'power' => 'user',
    'name' => '用户管理',
    'icon' => 'product',
    'child' => array(
      array('power' => 'user', 'name' => '个人中心', 'act' => 'user', 'op' => 'index'),
      array('power' => 'consignee', 'name' => '收件人管理', 'act' => 'consignee', 'op' => 'index'),
      array('power' => 'trans_house', 'name' => '发货中转仓', 'act' => 'trans_house', 'op' => 'index'),
      array('power' => 'goods', 'name' => '商品管理', 'act' => 'goods', 'op' => 'index'),
    ),
  ),
  /*
  array(
          'power' => 'order',
          'name' => '直邮服务',
          'icon' => 'trad',
          'child' => array(
              array('power' => 'order', 'name' => '直邮运单', 'act' => 'order', 'op' => ''),
              array('power' => '', 'name' => '直邮发件记录查询', 'act' => '', 'op' => ''),
          ),
  ),*
   *
   */
  array(
    'power' => 'order_tp',
    'name' => '转运服务',
    'icon' => 'trad',
    'child' => array(
      array('power' => 'order_tp', 'name' => '转运运单', 'act' => 'order_tp', 'op' => 'index'),
//      array('power' => '', 'name' => '添加订单', 'act' => 'order_tp', 'op' => 'index'),

    ),
  ),
  array(
    'power' => 'faq,guide',
    'name' => '问题与帮助',
    'icon' => 'trad',
    'child' => array(
      array('power' => 'faq', 'name' => '常见问题', 'act' => 'faq', 'op' => 'index'),
      array('power' => 'guide', 'name' => '下单指引', 'act' => 'guide', 'op' => 'index'),
    ),
  ),
  array(
    'power' => 'admin,measure,cache,country,setting',
    'name' => '系统管理',
    'icon' => 'system',
    'child' => array(
      //array('power' => 'setting', 'name' => '基本设置', 'act' => 'setting', 'op' => 'system'),
      //array('power' => 'admin', 'name' => '权限管理', 'act' => 'admin', 'op' => 'admin'),
      array('power' => 'shipment_code', 'name' => '物流单号管理', 'act' => 'shipment_code', 'op' => 'index'),
      array('power' => 'member', 'name' => '会员管理', 'act' => 'member', 'op' => 'index'),
      array('power' => 'trans_house', 'name' => '中转仓设置', 'act' => 'trans_house', 'op' => 'setting'),
    ),
  ),
  array(
    'power' => 'weixin',
    'name' => '微信公众号',
    'icon' => 'system',
    'child' => array(
      array('power' => 'weixin', 'name' => '自动回复', 'act' => 'weixin/backend', 'op' => 'reply_list'),
//      array('power' => 'weixin', 'name' => '回复记录', 'act' => 'weixin/backend', 'op' => 'reply_log'),
      array('power' => 'weixin', 'name' => '文章内容', 'act' => 'weixin/backend', 'op' => 'article_list'),
      array('power' => 'weixin', 'name' => '自定义菜单', 'act' => 'weixin/backend', 'op' => 'menu_list'),
    ),
  )
);

