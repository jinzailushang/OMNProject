<?php

/**
 * 产品管理
 * @copyright  2016-05-25 10:49:28 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class goodsModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('goods');
        $this->table="goods";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getGoodsInfo($condition, $field = '*', $order = 'id desc') {

        return $this->table($this->table)->field($field)->where($condition)->order($order)->limit(1)->find();

    }

    /**
     * 获取列表
     * @copyright 
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string 
     */
    public function getGoodsList($condition = array(), $field = '*', $page = 0, $order = 'id desc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getGoodsCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addGoods($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateGoods($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delGoods($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

    /**
     * 组合返回数组（默认）
     * copyright 2015-06-02, coolzbw
     * @return boolean
     */
    public function getHandle($filePath,$uid) {
        $importArray = $this->getTitle();
        import('libraries.cls_PHPExcel');
        $excel_obj = new phpExcelMod();
        $column = @$excel_obj->getHColumn($filePath);
        if($column != count($importArray)){
            return array('status'=>0,'msg'=>'模板错误！');
        }

        $xlsArr = @$excel_obj->excelToArray($filePath, $importArray, '2');

        $goods_list = array();

        if (is_array($xlsArr)) {
            $i = 0;
            $time = time();
            foreach ($xlsArr as $k=>$row) {
                $goods = array(
                  'u_id' => $uid,
                  'name' => $row['name'],
                  'cat_id' => $this->getCatIdByName($row['cat_name']),
                  'unit_id' => $this->getUnitIdByName($row['unit_name']),
                  'brand' => $row['brand'],
                  'price' => $row['price'],
                  'add_time' => $time
                );
                $goods_list[$i] = $goods;
                $i++;
            }
        }
        return array('status'=>1,'data'=>$goods_list);
    }

    public function saveGoods($data, $uid) {
        $success_num = $fail_num = 0;
        $err = array();
        $index = 1;
        foreach ($data as $d) {
            $goods = $this->getGoodsInfo(array('u_id'=>$uid, 'name'=>$d['name'],'cat_id'=>$d['cat_id']));
            if ($goods) {
                //$cid = $goods['id'];
                $fail_num++;
                $err[] = '第' . $index . '行商品已存在';
            } else {
                $cid = $this->addGoods($d);
                if ($cid) {
                    $success_num++;
                } else {
                    $fail_num++;
                    $err[] = '第' . $index . '行导入失败';
                }
            }
            $index++;
        }

        return array('status'=>1,'snum'=>$success_num, 'fnum'=>$fail_num,'error'=>count($err)> 0 ? implode("<br />",
          $err) : '');
    }

    private function getTitle() {
        return array(
          'name' => '商品名称',
          'cat_name'=> '商品分类',
          'brand'=> '品牌',
          'unit_name'=> '商品单位',
          'price'=> '商品单价',
        );
    }

    private function getCatIdByName($cat_name) {
        $cat = Model('category')->getCategoryInfo(array('cat_name'=>$cat_name));
        return $cat? $cat['cat_id']:'';
    }

    private function getUnitIdByName($unit_name) {
        $unit = Model('measure')->getMeasureInfo(array('measure_name_cn'=>$unit_name));
        return $unit? $unit['id']:'';
    }

}

