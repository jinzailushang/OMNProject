<?php

/**
 * 物流单号管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class shipment_codeModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('shipment_code');
        $this->table="shipment_code";
    }

    /**
     * 获取详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getShipmentCodeInfo($condition=array(), $field = '*', $order = 'id asc') {

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
    public function getShipmentCodeList($condition = array(), $field = '*', $page = 0, $order = 'id asc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getShipmentCodeCount($condition=array()) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addShipmentCode($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateShipmentCode($data,$condition) {

        return $this->table($this->table)->where($condition)->update($data);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delShipmentCode($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    /**
     * 生成不重复的物流单号
     * @param type $num
     * @return boolean
     */
    public function buildCode($num = 0) {
        if((int)$num > 0){
            for($i=0;$i<$num;$i++){
                $nums = $this->getShipmentCodeCount();
                $scode = 'WL7'. sprintf('%07d', $nums) . $this->random(4);
                $this->addShipmentCode(array('scode'=>$scode,'add_time'=>time()));
            }
            return $scode;
        }
        return false;
    }
    /**
     * 生成一个随机数
     * @param type $length
     * @return string
     */
    public function random($length = 12) {
        //$chars = '0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $chars = '0123456789';
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

}

