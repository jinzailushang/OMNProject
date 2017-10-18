<?php
/**
 * 货站管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class trans_houseModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('trans_house');
        $this->table="trans_house";
    }

    /**
     * 获取详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getTransHouseInfo($condition=array(), $field = '*', $order = 'tid desc') {

        return $this->table($this->table)->field($field)->where($condition)->order($order)->limit(1)->find();

    }

    /**
     * 获取列表
     * @copyright 
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getTransHouseList($condition = array(), $field = '*', $page = 0, $order = 'tid desc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getTransHouseCount($condition=array()) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 检测是否存在
     * @param type $condition
     * @return boolean
     */
    public function is_exist($condition=array()) {
        $num = $this->getTransHouseCount($condition);
        if($num){
            return true;
        }
        return false;
    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addTransHouse($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateTransHouse($data,$condition) {

        return $this->table($this->table)->where($condition)->update($data);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delTransHouse($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    

}

