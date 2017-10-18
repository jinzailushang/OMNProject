<?php
/**
 * 物流信息管理
 * @copyright  2016-05-25 10:59:34 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class order_logistics_logModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('order_logistics_log');
        $this->table="order_logistics_log";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getOrderLogisticsLogInfo($condition, $field = '*', $order = 'order_id desc') {

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
    public function getOrderLogisticsLogList($condition = array(), $field = '*', $page = 0, $order = 'order_id desc',
                                             $limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getOrderLogisticsLogCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addOrderLogisticsLog($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateOrderLogisticsLog($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delOrderLogisticsLog($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

