<?php
/**
 * 渠道管理
 * @copyright  2016-08-10 16:05:38 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class trans_house_channelModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('trans_house_channel');
        $this->table="trans_house_channel";
    }

    /**
     * 获取详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getTransHouseChannelInfo($condition=array(), $field = '*', $order = 'channel_id asc') {

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
    public function getTransHouseChannelList($condition = array(), $field = '*', $page = 0, $order = 'channel_id asc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getTransHouseChannelCount($condition=array()) {

        return $this->table($this->table)->where($condition)->count();

    }
    public function is_exit($condition=array()) {
        
        $count = $this->getTransHouseChannelCount($condition);
        if($count){
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
    public function addTransHouseChannel($data) {

        return $this->table($this->table)->insertAll($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateTransHouseChannel($data,$condition) {

        return $this->table($this->table)->where($condition)->update($data);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delTransHouseChannel($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    

}

