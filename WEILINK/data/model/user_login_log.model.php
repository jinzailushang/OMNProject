<?php
/**
 * @copyright  2016-05-25 10:59:34 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class user_login_logModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('user_login_log');
        $this->table="user_login_log";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getUserLoginLogInfo($condition, $field = '*', $order = 'id desc') {

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
    public function getUserLoginLogList($condition = array(), $field = '*', $page = 0, $order = 'id desc',$limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getUserLoginLogCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addUserLoginLog($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateUserLoginLog($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delUserLoginLog($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

