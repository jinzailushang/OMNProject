<?php
/**
 * 单位管理
 * @copyright  2016-05-25 10:49:28 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class measureModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('measure');
        $this->table="measure";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMeasureInfo($condition, $field = '*', $order = 'id desc') {

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
    public function getMeasureList($condition = array(), $field = '*', $page = 0, $order = 'id',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getMeasureCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addMeasure($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateMeasure($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delMeasure($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    public function getMeasureNameById($id = 0) {
        $res = $this->getMeasureInfo(array('id'=>$id),'measure_name_cn');
        return $res ? $res['measure_name_cn'] : '';
    }

}

