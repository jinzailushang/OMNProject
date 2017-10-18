<?php

/**
 * 缓存操作
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class cacheModel extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 方法
     * @copyright 2015-06-02, jack
     * @return array
     */
    public function call($method) {
        $method = '_' . strtolower($method);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            return false;
        }
    }

    /**
     * 基本设置
     * @copyright 2015-06-02, jack
     * @return array
     */
    private function _setting() {
        $list = $this->table('setting')->where(true)->select();
        $array = array();
        foreach ((array) $list as $v) {
            $array[$v['name']] = $v['value'];
        }
        unset($list);
        return $array;
    }

}
