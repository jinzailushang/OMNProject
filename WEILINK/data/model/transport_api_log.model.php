<?php
/**
 * 转运接口日志
 * @copyright 2016-08-02, coolzbw
 */
defined('InOmniWL') or exit('Forbidden!');

class transport_api_logModel extends Model {

    public $table = 'transport_api_log';

    /**
     * 读取单条
     * @copyright 2016-08-02, coolzbw
     * @param array $condition 查询条件
     * @param array $extend 追加返回相关的信息,如array()
     * @return array
     */
    public function getTransportApiLogInfo($condition, $extend = array(), $fields = '*') {
        $info = $this->table($this->table)->field($fields)->where($condition)->find();
        if (empty($info)) {
            return array();
        }
       
        return $info;
    }

    /**
     * 读取列表
     * @copyright 2016-08-02, coolzbw
     * @param unknown $condition
     * @param unknown $extend 追加返回那些表的信息,如 array()
     * @param string $order
     * @param string $field
     * @param string $pagesize
     * @param string $limit                        
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getTransportApiLogList($condition, $extend = array(), $field = '*', $order = 'id', $pagesize = '', $limit = '') {
        $list = $this->table($this->table)->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
        if (empty($list)) {
            return array();
        }

        $news_list = array();
        foreach ($list as $info) {
            if (!empty($extend)) {
                $news_list[$info['id']] = $info;
            }
        }
        if (empty($news_list)) {
            $news_list = $list;
        }
        return $news_list;
    }

    /**
     * 读取数量
     * @copyright 2016-08-02, coolzbw
     * @param unknown $condition
     */
    public function getTransportApiLogCount($condition) {
        return $this->table($this->table)->where($condition)->count();
    }

    /**
     * 判断是否存在 
     * @copyright 2016-08-02, coolzbw
     * @param array $condition
     */
    public function isExist($condition) {
        $result = $this->getTransportApiLogInfo($condition);
        if (empty($result)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 添加
     * @copyright 2016-08-02, coolzbw
     * @param array $insert 
     * @return array
     */
    public function addTransportApiLog($data) {

        $ret = $this->table($this->table)->insert($data);
        return $ret ? array('status' => 1, 'msg' => '添加成功', 'log_id' => $ret) : array('status' => 0, 'msg' => '添加失败');
    }

    /**
     * 更新
     * @copyright 2016-08-02, coolzbw
     * @param array $data 更新数据
     * @param array $condition 条件
     * @return boolean
     */
    public function editTransportApiLog($data, $condition) {
        
        $ret = $this->table($this->table)->where($condition)->update($data);
        return $ret ? array('status' => 1, 'msg' => '编辑成功') : array('status' => 0, 'msg' => '编辑失败');
    }

    /**
     * 验证
     * @copyright 2016-08-02, coolzbw
     * @param array $data_array 
     * @return array
     */
    public function validate($data_array) {

    }

    /**
     * 删除
     * @copyright 2016-08-02, coolzbw
     * @param   array $condition 列表条件
     * @return boolean
     */
    public function deleteTransportApiLog($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }

}

