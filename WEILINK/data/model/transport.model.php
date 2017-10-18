<?php

/**
 * 转运管理
 * @copyright 2015-09-07, coolzbw
 */
defined('InOmniWL') or exit('Forbidden!');

class transportModel extends Model {

    public $table = 'transport';

    /**
     * 读取单条
     * @copyright 2015-09-07, coolzbw
     * @param array $condition 查询条件
     * @param array $extend 追加返回相关的信息,如array()
     * @return array
     */
    public function getTransportInfo($condition, $extend = array(), $fields = '*') {
        $info = $this->table($this->table)->field($fields)->where($condition)->find();
        if (empty($info)) {
            return array();
        }

        return $info;
    }

    /**
     * 根据id获取名称
     * @param integer $warehouse_id
     * @return string
     */
    public function getTransportNameById($transport_id = 0) {
        $res = $this->getTransportInfo(array('transport_id' => $transport_id), 'transport_name');
        return $res ? $res['transport_name'] : '';
    }

    /**
     * 读取列表
     * @copyright 2015-09-07, coolzbw
     * @param unknown $condition
     * @param unknown $extend 追加返回那些表的信息,如 array()
     * @param string $order
     * @param string $field
     * @param string $pagesize
     * @param string $limit                        
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getTransportList($condition, $extend = array(), $field = '*', $order = 'id', $pagesize = '', $limit = '') {
        $list = $this->table($this->table)->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
        if (empty($list)) {
            return array();
        }

        $news_list = array();
        foreach ($list as $info) {
            if (!empty($extend)) {
                $news_list[$info['transport_id']] = $info;
            }
        }
        if (empty($news_list)) {
            $news_list = $list;
        }
        return $news_list;
    }

    /**
     * 读取数量
     * @copyright 2015-09-07, coolzbw
     * @param unknown $condition
     */
    public function getTransportCount($condition) {
        return $this->table($this->table)->where($condition)->count();
    }

    /**
     * 判断是否存在 
     * @copyright 2015-09-07, coolzbw
     * @param array $condition
     */
    public function isExist($condition) {
        $result = $this->getTransportInfo($condition);
        if (empty($result)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 添加
     * @copyright 2015-09-07, coolzbw
     * @param array $insert 
     * @return array
     */
    public function addTransport($data) {

        $ret = $this->table($this->table)->insert($data);
        if ($ret) {
//            Model('transport_log')->addTransportLog($data, '增加操作 ');
        }
        return $ret ? array('status' => 1, 'msg' => '添加成功', 'last_id' => $ret) : array('status' => 0, 'msg' => '添加失败');
    }

    /**
     * 更新
     * @copyright 2015-09-07, coolzbw
     * @param array $data 更新数据
     * @param array $condition 条件
     * @return boolean
     */
    public function editTransport($data, $condition) {

        $ret = $this->table($this->table)->where($condition)->update($data);
        if ($ret) {
//            Model('transport_log')->addTransportLog($data, '更新操作 ');
        }
        return $ret ? array('status' => 1, 'msg' => '编辑成功') : array('status' => 0, 'msg' => '编辑失败');
    }

    /**
     * 验证
     * @copyright 2015-09-07, coolzbw
     * @param array $data_array 
     * @return array
     */
    public function validate($data_array) {
        
    }

    /**
     * 删除
     * @copyright 2015-09-07, coolzbw
     * @param   array $condition 列表条件
     * @return boolean
     */
    public function deleteTransport($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }
    
    /**
     * 税费缴纳
     * @copyright (c) 2015-09-07, coolzbw
     * @var string
     */
    public function onlineTax($order_id) {
        $model_orders = Model('order');
        $condition = array(
            'order.order_id' => intval($order_id),
            'tracking_number' => array('neq', ''),
        );
        $info = $model_orders->getOrderInfo($condition);
//        dump($info);die();
        if (empty($info)) {
            return array('status' => 0, 'msg' => '不存在，或数据状态不对');
        }
        $api = getApiObject('transport', 'onlineTax', 'zongteng');

        if (empty($api)) {
            return array('status' => 0, 'msg' => '找不到接口文件');
        }
        $api_ret = $api->onlineTax($info);
        if ($api_ret['status'] == 1) {
//            $update = array(
//                'tariff_fee' => $api_ret['data']['tax_amount],
//            );
//            $model_orders->updateOrder($update, $condition);
        }
        return($api_ret);
    }
    

    /**
     * 物流追踪
     * @copyright (c) 2015-09-07, coolzbw
     * @var string
     */
    public function queryTraceStatusFlow($order_id) {
        $model_orders = Model('order');
        $condition = array(
            'order.order_id' => intval($order_id),
            'tracking_number' => array('neq', ''),
        );
        $info = $model_orders->getOrderInfo($condition);
//        dump($info);die();
        if (empty($info)) {
            return array('status' => 0, 'msg' => '不存在，或数据状态不对');
        }
        $api = getApiObject('transport', 'queryTraceStatusFlow', 'zongteng');

        if (empty($api)) {
            return array('status' => 0, 'msg' => '找不到接口文件');
        }
        $api_ret = $api->queryTraceStatusFlow($info);
        if ($api_ret['status'] == 1) {
            //处理物流
//            $update = array(
//
//            );
//            $model_orders->updateOrder($update, $condition);
        }
        return($api_ret);
    }
  
}
