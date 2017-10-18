<?php

/**
 * 品类管理
 * @copyright  2016-05-25 10:49:28 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class categoryModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('category');
        $this->table="category";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getCategoryInfo($condition, $field = '*', $order = 'cat_id desc') {

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
    public function getCategoryList($condition = array(), $field = '*', $page = 0, $order = 'cat_id',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getCategoryCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addCategory($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateCategory($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delCategory($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    /**
     * 根据id获取名称
     * @param type $gc_id
     * @return type
     */
    public function getCategoryNameById($cat_id = 0) {
        $res = $this->getCategoryInfo(array('cat_id'=>$cat_id),'cat_name');
        return $res ? $res['cat_name'] : '';
    }

}

