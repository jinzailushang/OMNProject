<?php

/**
 * 余额管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class moneyModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('money');
        $this->table="money";
    }

    /**
     * 余额详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMoneyInfo($condition, $field = '*', $order = 'u_id desc') {

        return $this->table($this->table)->field($field)->where($condition)->order($order)->limit(1)->find();

    }

    /**
     * 订单日志列表
     * @copyright 
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMoneyList($condition = array(), $field = '*', $page = 0, $order = 'u_id desc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 订单日志数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getMoneyCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入订单日志
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addMoney($data) {
        return $this->table($this->table)->insert($data);
    }

    /**
     * 更新订单日志
     * @param type $update
     * @param type $condition
     * @return type
     */
    public function updateMoney($update,$condition) {
        return $this->table($this->table)->where($condition)->update($update);
    }
    
    public function delMoney($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

