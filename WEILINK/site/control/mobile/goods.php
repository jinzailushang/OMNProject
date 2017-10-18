<?php

/**
 * 商品数据
 */
class goodsControl extends SystemControl
{
  public function __construct()
  {
    parent::__construct();
  }

  public function listOp()
  {
    $goodses = Model('goods')->getGoodsList(array('u_id'=>$this->admin_info['id']));
    $list = array();
    foreach ($goodses as $g) {
      $item = array(
        'goodsId' => $g['id'],
        'goodsName' => $g['name'],
        'goodsPrice' => $g['price'],
        'goodsCategoryId'=>$g['cat_id'],
        'goodsBrand'=>$g['brand'],
        'goodsUnitId'=>$g['unit_id']
      );
      $list[] = $item;
    }

    $this->output(1, null, $list);
  }

  public function catlistOp()
  {
    $cats = Model('category')->getCategoryList();
    $list = array();
    foreach ($cats as $c) {
      $item = array(
        'goodsCategoryId' => $c['cat_id'],
        'goodsCategoryName' => $c['cat_name'],
        'goodsCategoryParentId' => '',
        'goodsCategoryParent' => '',
        'goodsCategoryChildId' => '',
        'goodsCategoryChild' => ''
      );
      $list[] = $item;
    }

    $this->output(1, null, $list);
  }

  public function unitlistOp()
  {
    $units = Model('measure')->getMeasureList();;
    $list = array();
    foreach ($units as $u) {
      $item = array(
        'unitId' => $u['id'],
        'unitName' => $u['measure_name_cn']
      );
      $list[] = $item;
    }

    $this->output(1, null, $list);
  }

  public function addGoodsOp()
  {
    if (empty($_POST['goodsName'])) {
      $this->output(0, '商品名称不可为空');
    }
    if (empty($_POST['goodsCategoryId']) || !$this->_checkCatIdValidate($_POST['goodsCategoryId'])) {
      $this->output(0, '无效的分类ID');
    }
    if (empty($_POST['goodsUnitId']) || !$this->_checkUnitIdValidate($_POST['goodsUnitId'])) {
      $this->output(0, '无效的单位ID');
    }
    if (empty($_POST['goodsBrand'])) {
      $this->output(0, '商品品牌不可为空');
    }
    if (empty($_POST['goodsPrice']) || $_POST['goodsPrice'] < 0.01) {
      $this->output(0, '商品价格不可为空且不可小于0.01元');
    }
    if (Model('goods')->getGoodsInfo(array('u_id' => $this->admin_info['id'], 'cat_id' => $_POST['goodsCategoryId'], 'name' => $_POST['goodsName']))) {
      $this->output(0, '该商品已存在');
    }
    $data = array(
      'cat_id' => $_POST['goodsCategoryId'],
      'brand' => $_POST['goodsBrand'],
      'unit_id' => $_POST['goodsUnitId'],
      'name' => $_POST['goodsName'],
      'price' => $_POST['goodsPrice'],
      'u_id' => $this->admin_info['id'],
      'add_time' => time()
    );

    $id = Model('goods')->addGoods($data);
    $this->output($id ? 1 : 0, $id ? null : '添加失败', array('goodsId'=>$id));
  }

  public function updateGoodsOp()
  {
    if (empty($_POST['goodsId'])) {
      $this->output(0, '商品ID不可为空');
    }
    if (empty($_POST['goodsName'])) {
      $this->output(0, '商品名称不可为空');
    }
    if (empty($_POST['goodsCategoryId']) || !$this->_checkCatIdValidate($_POST['goodsCategoryId'])) {
      $this->output(0, '无效的分类ID');
    }
    if (empty($_POST['goodsUnitId']) || !$this->_checkUnitIdValidate($_POST['goodsUnitId'])) {
      $this->output(0, '无效的单位ID');
    }
    if (empty($_POST['goodsBrand'])) {
      $this->output(0, '商品品牌不可为空');
    }
    if (empty($_POST['goodsPrice']) || $_POST['goodsPrice'] < 0.01) {
      $this->output(0, '商品价格不可为空且不可小于0.01元');
    }
    if (!Model('goods')->getGoodsInfo(array('u_id' => $this->admin_info['id'], 'id' => $_POST['goodsId']))) {
      $this->output(0, '该商品不存在');
    }
    if (Model('goods')->getGoodsInfo(array('id' => array('neq', $_POST['goodsId']), 'u_id' => $this->admin_info['id'], 'cat_id' => $_POST['goodsCategoryId'], 'name' => $_POST['goodsName']))) {
      $this->output(0, '该商品已存在');
    }
    $data = array(
      'cat_id' => $_POST['goodsCategoryId'],
      'brand' => $_POST['goodsBrand'],
      'unit_id' => $_POST['goodsUnitId'],
      'name' => $_POST['goodsName'],
      'price' => $_POST['goodsPrice']
    );

    Model('goods')->updateGoods($data, array('id' => $_POST['goodsId']));
    $this->output(1, '更新成功');
  }

  private function _checkCatIdValidate($cat_id)
  {
    return Model('category')->getCategoryInfo(array('cat_id' => $cat_id));
  }

  private function _checkUnitIdValidate($unit_id)
  {
    return Model('measure')->getMeasureInfo(array('id' => $unit_id));
  }
}
