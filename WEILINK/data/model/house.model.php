<?php

/**
 * @version 货站模型管理
 * @copyright (c) 2015-03-27, coolzbw
 */
defined('InOmniWL') or exit('Access Invalid!');

class houseModel extends Model {

    public function __construct() {
        parent::__construct('house');
    }

    /**
     * 查询货站列表
     * @copyright (c) 2015-03-27, coolzbw
     * @param array $condition 查询条件
     * @param string $order 排序
     * @param string $field 字段
     * @return array
     */
    public function getHouseList($condition = array(), $order = 'house_id', $field = '*') {
        $result = $this->field($field)->where($condition)->order($order)->select();
        return $result;
    }

    /**
     * 货站返回默认物流
     * @copyright (c) 2015-03-27, coolzbw
     * @param array $condition 查询条件
     * @return array
     */
    public function getHouseDefauleExpressID($house_id, $express_id = 0) {
        $house_info = $this->getHouseInfoByID($house_id);
        if (empty($house_info)) {
            return 0;
        }
        $express_list = $house_info['express_list'];
        if (empty($express_list)) {
            return 0;
        }
        $express_arr = explode(',', $express_list);
        if (is_array($express_arr)) {
            if(in_array($express_id,$express_arr)){
                return $express_id;
            }
            return $express_arr[0];
        } else {
            return $express_list;
        }
    }

    /**
     * 查询货站信息
     * @copyright (c) 2015-03-27, coolzbw
     * @param array $condition 查询条件
     * @return array
     */
    public function getHouseInfo($condition) {
        $house_info = $this->table('house')->where($condition)->find();
        return $house_info;
    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getHouseCount($condition) {

        $num = $this->table('house')->where($condition)->count();
        return $num;
    }

    /**
     * 查询货站下的物流公司
     * @copyright (c) 2015-03-27, coolzbw
     * @param array $condition 查询条件
     * @return array
     */
    public function getHouseExtend($condition) {
        $express_list = Model()->table('express')->where($condition)->select();
        return $express_list;
    }

    /**
     * 通过货站编号查询货站信息
     * @copyright (c) 2015-03-27, coolzbw
     * @param int $house_id 货站编号
     * @return array
     */
    public function getHouseInfoByID($house_id) {
        $house_info = rcache($house_id, 'house_info');
        if (empty($house_info)) {
            $house_info = $this->getHouseInfo(array('house_id' => $house_id));
            wcache($house_id, $house_info, 'house_info');
        }
        return $house_info;
    }

    /**
     * 编辑货站
     * @copyright (c) 2015-03-27, coolzbw
     * @param array $update 更新信息
     * @param array $condition 条件
     * @return bool
     */
    public function editHouse($update, $condition) {
        $this->where($condition)->update($update);
        //清空缓存
//        $platform_list = $this->getPlatformList();
//        foreach ($platform_list as $platform_info) {
//            wcache($platform_info['platform_id'], $platform_info, 'house_info');
//        }
        return true;
    }

    public function addHouse($data) {
        return $this->insert($data);
    }

    public function getHouseName($condition) {
        $house_info = $this->table('house')->field('house_name')->where($condition)->find();
        if ($house_info) {
            return $house_info['house_name'];
        }
        return '';
    }

}
