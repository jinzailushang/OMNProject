<?php

/**
 * 会员管理
 * @copyright 
 */
defined('InOmniWL') or exit('Access Invalid!');

class userModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('user');
        $this->table="user";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getUserInfo($condition, $field = '*', $order = 'user.u_id desc') {

        return $this->table('user,user_other')
                ->join('left join')
                ->on('user.u_id = user_other.u_id')
                ->field($field)->where($condition)->order($order)->limit(1)->find();

    }

    /**
     * 获取列表
     * @copyright 
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string 
     */
    public function getUserList($condition = array(), $field = '*', $page = 0, $order = 'user.u_id desc',$limit='') {

        return $this->table('user,user_other')
                ->join('left join')
                ->on('user.u_id = user_other.u_id')
                ->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getUserCount($condition) {

        return $this->table('user,user_other')
                ->join('left join')
                ->on('user.u_id = user_other.u_id')->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addUser($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateUser($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delUser($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

