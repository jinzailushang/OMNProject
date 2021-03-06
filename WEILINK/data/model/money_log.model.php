<?php

/**
 * 交易日志管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class money_logModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('money_log');
        $this->table="money_log";
    }

    /**
     * 获取订单日志详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMoneyLogInfo($condition, $field = '*', $order = 'log_id desc') {

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
    public function getMoneyLogList($condition = array(), $field = '*', $page = 0, $order = 'log_id desc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 订单日志数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getMoneyLogCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入订单日志
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addMoneyLog($data) {

        return $this->table($this->table)->insert($data);

    }
    
    public function delMoneyLog($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

