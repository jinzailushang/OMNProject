<?php
/**
 * 收件人管理
 * @copyright  2016-05-25 10:59:34 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class order_goodsModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('order_goods');
        $this->table="order_goods";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getOrderGoodsInfo($condition, $field = '*', $order = 'odg_id desc') {

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
    public function getOrderGoodsList($condition = array(), $field = '*', $page = 0, $order = 'odg_id desc',
                                     $limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getOrderGoodsCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addOrderGoods($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateOrderGoods($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delOrderGoods($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }


    public function saveOrderGoods($data) {
        $success_num = $fail_num = 0;
        foreach ($data as $d) {
            $odg_id = $this->addOrderGoods($d);
            if ($odg_id) {
                $success_num++;
            } else {
                $fail_num++;
            }
        }

        return array('status'=>1,'snum'=>$success_num, 'fnum'=>$fail_num);
    }
}

