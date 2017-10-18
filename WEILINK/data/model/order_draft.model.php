<?php
/**
 * 草稿管理
 * @copyright  2016-05-25 10:59:34 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class order_draftModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('order_draft');
        $this->table="order_draft";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getOrderDraftInfo($condition, $field = '*', $order = '') {

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
    public function getOrderDraftList($condition = array(), $field = '*', $page = 0, $order = '',
                                     $limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getOrderDraftCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addOrderDraft($data, $replace = false) {

        return $this->table($this->table)->insert($data, $replace);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateOrderDraft($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delOrderDraft($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

