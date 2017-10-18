<?php
/**
 * 收件人管理
 * @copyright  2016-05-25 10:59:34 jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class consigneeModel extends Model {

    public $table = "";
    public function __construct() {
        parent::__construct('consignee');
        $this->table="consignee";
    }

    /**
     * 获取单条记录详情
     * @copyright 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getConsigneeInfo($condition, $field = '*', $order = 'is_default = "Y" desc, cid desc') {

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
    public function getConsigneeList($condition = array(), $field = '*', $page = 0, $order = 'is_default = "Y" desc,
    cid desc',
                                     $limit='') {

        return $this->table($this->table)->where($condition)->field($field)->page($page)->order($order)->limit($limit)->select();

    }

    /**
     * 获取数量
     * @copyright 
     * @param array $condition
     * @return int
     */
    public function getConsigneeCount($condition) {

        return $this->table($this->table)->where($condition)->count();

    }
    /**
     * 插入
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function addConsignee($data) {

        return $this->table($this->table)->insert($data);

    }
    /**
     * 更新
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function updateConsignee($update, $condition) {

        return $this->table($this->table)->where($condition)->update($update);

    }
    /**
     * 删除
     * @copyright 
     * @param	array $data 
     * @return	array 数组格式的返回结果
     */
    public function delConsignee($condition) {
        return $this->table($this->table)->where($condition)->delete();
    }


    /**
     * 组合返回数组（默认）
     * copyright 2015-06-02, coolzbw
     * @return boolean
     */
    public function getHandle($filePath,$uid) {
        $importArray = $this->getTitle();
        import('libraries.cls_PHPExcel');
        $excel_obj = new phpExcelMod();
        $column = @$excel_obj->getHColumn($filePath);
        if($column != count($importArray)){
            return array('status'=>0,'msg'=>'模板错误！');
        }

        $xlsArr = @$excel_obj->excelToArray($filePath, $importArray, '2');

        $consignee_list = array();

        if (is_array($xlsArr)) {
            $i = 0;
            $time = time();
            foreach ($xlsArr as $k=>$row) {
              $consignee = array(
                'u_id' => $uid,
                'name' => $row['name'],
                'phone' => $row['phone'],
                'ID' => $row['ID'],
                'province' => $row['province'],
                'city' => $row['city'],
                'area' => $row['area'],
                'address' => $row['address'],
                'zipcode' => $row['zipcode'],
                'add_time' => $time
              );
              $consignee_list[$i] = $consignee;
              $i++;
            }
        }
        return array('status'=>1,'data'=>$consignee_list);
    }

    public function saveConsignee($data) {
        $success_num = $fail_num = 0;
        foreach ($data as $d) {
            $cid = $this->addConsignee($d);
            if ($cid) {
                $success_num++;
            } else {
                $fail_num++;
            }
        }

        return array('status'=>1,'snum'=>$success_num, 'fnum'=>$fail_num);
    }

    private function getTitle() {
        return array(
          'province' => '收件省/直辖市名',
          'city'=> '收件城市',
          'area'=> '区域',
          'name'=> '收件人姓名',
          'zipcode'=> '收件人邮编',
          'address'=> '收件人地址',
          'phone' => '收件人电话号码',
          'ID' => '身份证号',
        );
    }
}

