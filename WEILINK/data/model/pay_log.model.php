<?php

/**
 * 订单日志管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class pay_logModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('pay_log');
        $this->table="pay_log";
    }

    /**
     * 获取订单日志详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getPayLogInfo($condition, $field = '*', $order = 'flow_id desc') {
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
    public function getPayLogList($condition = array(), $field = '*', $page = 0, $order = 'flow_id desc',$limit='') {
        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 订单日志数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getPayLogCount($condition) {
        return $this->table($this->table)->where($condition)->count();
    }
    /**
     * 插入订单日志
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addPayLog($data) {
        return $this->table($this->table)->insert($data);
    }

    /**
     * 更新订单日志
     * @param type $update
     * @param type $condition
     * @return type
     */
    public function updatePayLog($update,$condition) {
        return $this->table($this->table)->where($condition)->update($update);
    }
    
    public function delPayLog($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

