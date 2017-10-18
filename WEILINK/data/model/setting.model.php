<?php

/**
 * 系统设置内容
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class settingModel extends Model {

    public function __construct() {
        parent::__construct('setting');
    }

    /**
     * 返回值
     * copyright 2015-06-02, jack
     */
    public function getValueByName($name) {
        $res = $this->table('setting')->where(array('name' => $name))->find();
        return $res ? $res['value'] : '';
    }

    /**
     * 读取系统设置信息
     * copyright 2015-06-02, jack
     * @param string $name 系统设置信息名称
     * @return array 数组格式的返回结果
     */
    public function getRowSetting($name) {
        $param = array();
        $param['table'] = 'setting';
        $param['where'] = "name='" . $name . "'";
        $result = Db::select($param);
        if (is_array($result) and is_array($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * 读取系统设置列表
     * copyright 2015-06-02, jack
     * @return array 数组格式的返回结果
     */
    public function getListSetting() {
        $param = array();
        $param['table'] = 'setting';
        $result = Db::select($param);
        if (is_array($result)) {
            $list_setting = array();
            foreach ($result as $k => $v) {
                $list_setting[$v['name']] = $v['value'];
            }
        }
        return $list_setting;
    }

    /**
     * 插入信息
     * copyright 2015-06-02, jack
     * @param array $param 插入数据
     * @return bool 布尔类型的返回结果
     */
    public function insertSetting($param) {
        if (empty($param)) {
            return false;
        }
        $res = $this->table('setting')->insert($param);
        H('setting', true);
        @unlink(BASE_DATA_PATH . DS . 'cache' . DS . 'setting.php');
        return $res;
    }

    /**
     * 更新信息
     * copyright 2015-06-02, jack
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updateSetting($param) {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            foreach ($param as $k => $v) {
                $tmp = array();
                $specialkeys_arr = array('statistics_code');
                $tmp['value'] = (in_array($k, $specialkeys_arr) ? htmlentities($v, ENT_QUOTES) : $v);
                $where = " name = '" . $k . "'";
                $result = Db::update('setting', $tmp, $where);
                if ($result !== true) {
                    return $result;
                }
            }
            H('setting', true);
            @unlink(BASE_DATA_PATH . DS . 'cache' . DS . 'setting.php');
            return true;
        } else {
            return false;
        }
    }

}
