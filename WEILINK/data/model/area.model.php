<?php

/**
 * 地区模型
 * @copyright 2016-05-25, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class areaModel extends Model {

    public function __construct() {
        parent::__construct('area');
    }

    public function getAreaList($condition = array(), $fields = '*', $group = '', $cache = true) {
        return $this->table('area')->where($condition)->field($fields)->limit(false)->group($group)->select(array('cache' => $cache));
    }

    public function getAreaInfo($condition = array(), $fields = '*') {
        return $this->table('area')->where($condition)->field($fields)->find();
    }

}
