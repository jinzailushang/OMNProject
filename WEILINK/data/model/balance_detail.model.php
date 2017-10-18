<?php
/**
 * 交易管理
 * @copyright 2016-06-03 10:37:04 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class balance_detailModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('balance_detail');
        $this->table="balance_detail";
    }

    /**
     * 获取详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBalanceDetailInfo($condition=array(), $field = '*', $order = 'bid desc') {

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
    public function getBalanceDetailList($condition = array(), $field = '*', $page = 0, $order = 'bid desc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getBalanceDetailCount($condition=array()) {

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
    public function addBalanceDetail($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateBalanceDetail($data,$condition) {

        return $this->table($this->table)->where($condition)->update($data);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delBalanceDetail($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    

}

