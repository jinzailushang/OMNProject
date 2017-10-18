<?php

/**
 * 微信
 */
require_once __DIR__.'/Weixin.php';
class backendControl extends Weixin
{
  public function __construct(){
    parent::__construct();
    Tpl::setDir('tpl/weixin');
  }

  private function error($msg) {
    header('http/1.1 500');
    echo $msg;
    die();
  }

  /**
   * 菜单列表
   */
  public function menu_listOp() {
    $first_level_menus = Model()->query("SELECT * FROM wx_menu WHERE parent <=> 0 ORDER BY sort DESC, menu_id ASC");
    if ($first_level_menus) {
      foreach ($first_level_menus as $fk=>$fv) {
        $second_level_menus = Model()->query("SELECT * FROM wx_menu WHERE parent <=> {$fv['menu_id']} ORDER BY sort DESC, menu_id ASC");
        if ($second_level_menus) {
          $first_level_menus[$fk]['sub_menu'] = $second_level_menus;
        }
      }
    }

    Tpl::output('menu_list', $first_level_menus);
    Tpl::output('position', '微信公众号 &gt; 自定义菜单');
    Tpl::showpage('menu_list', 'index_layout');
  }

  /**
   * 更新菜单
   */
  public function update_menuOp() {
    isset($_GET['menu_id']) && is_numeric($_GET['menu_id']) && $_GET['menu_id'] > 0 || $this->error('Invalid menu_id');
    isset($_GET['field']) && in_array($_GET['field'], array('name','type','key','url','sort')) || $this->error('Invalid field');
    isset($_POST['value']) && ($_GET['field']!='url'&&preg_match('/^[\w\-\.]*$/', $_POST['value']) || $_POST['value'] == '' || filter_var($_POST['value'], FILTER_VALIDATE_URL)) || $this->error('Invalid value');

    Model()->execute("UPDATE wx_menu SET `{$_GET['field']}` = '{$_POST['value']}' WHERE menu_id = '{$_GET['menu_id']}'");
  }

  /**
   * 删除菜单
   */
  public function del_menuOp() {
    isset($_POST['menu_id']) && is_numeric($_POST['menu_id']) && $_POST['menu_id'] > 0 || $this->error('Invalid menu_id');

    Model()->execute("DELETE FROM wx_menu WHERE menu_id = '{$_POST['menu_id']}' OR parent = '{$_POST['menu_id']}'");
  }

  /**
   * 添加菜单
   */
  public function add_menuOp() {
    isset($_POST['name']) || $this->error('Invalid name');
    isset($_POST['type']) && in_array($_POST['type'], array('','view','click')) || $this->error('Invalid type');
    isset($_POST['key']) && ($_POST['key'] && preg_match('/^[\w\-\.]*$/', $_POST['key']) || $_POST['key'] == '') || $this->error('Invalid key');
    isset($_POST['url']) && ($_POST['url'] && filter_var($_POST['url'], FILTER_VALIDATE_URL) || $_POST['url'] == '') || $this->error('Invalid url');
    isset($_POST['parent']) && (is_numeric($_POST['parent']) || !$_POST['parent']) || $this->error('Invalid parent');
    isset($_POST['sort']) && (is_numeric($_POST['sort']) || !$_POST['sort']) || $this->error('Invalid sort');
    if (!$_POST['parent']) {
      $_POST['parent'] = 0;
    }
    if (!$_POST['sort']) {
      $_POST['sort'] = 0;
    }

    Model()->execute("INSERT INTO wx_menu SET
      `name` = '{$_POST['name']}',
      `type` = '{$_POST['type']}',
      `key` = '{$_POST['key']}',
      `url` = '{$_POST['url']}',
      `parent` = '{$_POST['parent']}',
      `sort` = '{$_POST['sort']}'");
  }

  /**
   * 自动回复列表
   */
  public function reply_listOp() {
    $replies = Model()->query("SELECT * FROM wx_reply ORDER BY reply_id ASC");
    Tpl::output('reply_list', $replies);
    Tpl::output('position', '微信公众号 &gt; 自动回复');
    Tpl::showpage('reply_list', 'index_layout');
  }

  /**
   * 添加回复
   */
  public function add_replyOp() {
    isset($_POST['words']) || $this->error('Invalid name');
    isset($_POST['reply_type']) && in_array($_POST['reply_type'], array('text','news')) || $this->error('Invalid reply_type');
    isset($_POST['is_default']) && in_array($_POST['is_default'], array('N','Y')) || $this->error('Invalid is_default');
    isset($_POST['reply_content']) || $_POST['reply_type'] == 'news' || $this->error('Invalid reply_content');

    Model()->execute("INSERT INTO wx_reply SET
      `words` = '{$_POST['words']}',
      `reply_type` = '{$_POST['reply_type']}',
      `is_default` = '{$_POST['is_default']}',
      `reply_content` = '{$_POST['reply_content']}'");

    if ($_POST['is_default'] == 'Y') {
      $row = Model()->query("SELECT reply_id FROM wx_reply ORDER BY reply_id DESC LIMIT 1");
      Model()->execute("UPDATE wx_reply SET `is_default` = 'N' WHERE reply_id != '{$row[0]['reply_id']}'");
    }
  }

  /**
   * 更新回复
   */
  public function update_replyOp() {
    isset($_GET['reply_id']) && is_numeric($_GET['reply_id']) && $_GET['reply_id'] > 0 || $this->error('Invalid reply_id');
    isset($_GET['field']) && in_array($_GET['field'], array('words','reply_type','reply_content','is_default')) || $this->error('Invalid field');
    isset($_POST['value']) && $_POST['value'] &&
    ($_GET['field'] == 'reply_type' && in_array($_POST['value'], array('text','news'))
    || $_GET['field'] == 'is_default' && in_array($_POST['value'], array('N','Y')) || TRUE) || $this->error('Invalid value');

    // 如果修改类型，则情况内容

    Model()->execute("UPDATE wx_reply SET `{$_GET['field']}` = '{$_POST['value']}'".($_GET['field'] == 'reply_type'? ", reply_content = ''":'')." WHERE reply_id = '{$_GET['reply_id']}'");
    if ($_GET['field'] == 'is_default') {
      Model()->execute("UPDATE wx_reply SET `is_default` = 'N' WHERE reply_id != '{$_GET['reply_id']}'");
    }
  }

  /**
   * 删除回复
   */
  public function del_replyOp() {
    isset($_POST['reply_id']) && is_numeric($_POST['reply_id']) && $_POST['reply_id'] > 0 || $this->error('Invalid reply_id');

    Model()->execute("DELETE FROM wx_reply WHERE reply_id = '{$_POST['reply_id']}'");
  }

  /**
   * 回复记录
   */
  public function reply_logOp() {
    Tpl::showpage('reply_log', 'index_layout');
  }

  /**
   * 文章内容
   */
  public function  article_listOp() {
    $articles = Model()->query("SELECT * FROM wx_article ORDER BY article_id DESC");
    Tpl::output('position', '微信公众号 &gt; 文章内容');
    Tpl::output('article_list', $articles);
    Tpl::showpage('article_list', 'index_layout');
  }

  /**
   * 文章详情
   */
  public function article_detailOp() {
    isset($_GET['article_id']) && is_numeric($_GET['article_id']) && $_GET['article_id'] > 0 || $this->error('Invalid article_id');
    $row = Model()->query("SELECT content FROM wx_article WHERE article_id = '{$_GET['article_id']}' LIMIT 1");
    echo $row[0]['content'];
  }

  /**
   * 编辑文章
   */
  public function set_articleOp() {
    isset($_GET['article_id']) && is_numeric($_GET['article_id']) || !$_GET['article_id'] || $this->error('Invalid article_id');
    isset($_POST['content']) && $_POST['content'] || $this->error('Invalid content');

    $sql = '';
    if ($_GET['article_id']) {
      $sql = "UPDATE wx_article SET content = '{$_POST['content']}' WHERE article_id = '{$_GET['article_id']}'";
    } else {
      $sql = "INSERT INTO wx_article SET content = '{$_POST['content']}', post_time = ".time();
    }
    Model()->execute($sql);
  }

  /**
   * 删除文章
   */
  public function del_articleOp() {
    isset($_POST['article_id']) && is_numeric($_POST['article_id']) && $_POST['article_id'] > 0 || $this->error('Invalid article_id');

    Model()->execute("DELETE FROM wx_article WHERE article_id = '{$_POST['article_id']}'");
  }
}
