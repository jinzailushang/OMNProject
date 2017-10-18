<?php

/**
 * 物流单号管理
 * @copyright (c) 2016-05-24, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class shipment_codeControl extends SystemControl {
    
    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/shipment_code');
    }
    
    public function indexOp() {
        Tpl::output('position', '物流单号管理');
        Tpl::showpage('index', 'index_layout');
    }
    /**
     * 获取列表填充数据
     * @return JSON
     */
    public function get_dataOp() {
        $model = Model('shipment_code');
        $condition = $this->get_search_condition();

        $count = $model->getShipmentCodeCount($condition);
        $list = $model->getShipmentCodeList($condition, '*', 20);
        $temp = array();
        if($list){
            foreach($list as $k=>&$v){
                $v['ctime'] = format_time($v['add_time']);
                $v['utime'] = format_time($v['use_time']);
                $v['status'] = $v['flag'] ? '已使用' : '未使用';
            }
            die(json_encode(array('status'=>1,'data'=>$list,'page'=>$model->showpage(9,'clickpage'),'count'=>$count)));
        }
        die(json_encode(array('status'=>0,'msg'=>'暂无数据','count'=>$count)));
    }
    /**
     * 获取搜索条件
     * @return string
     */
    public function get_search_condition() {
        $condition = array();
        $condition['flag'] = $_GET['status'];
        if(trim($_GET['scode'])){
            $condition['scode'] = trim($_GET['scode']);
        }
        return $condition;
    }

    
    /**
     * 批量生成物流单号
     */
    public function buildOp() {
        $num = $this->getPostData('num',0);
        if($num){
            $model = Model('shipment_code');
            if($model->buildCode($num)){
                die(json_encode(array('status'=>1)));
            }
        }
        die(json_encode(array('status'=>0)));
    }
    
    public function testOp() {

        $filePath = BASE_ROOT_PATH . DS . XLSX_TEMP . DS . 'aa.xlsx';;
        $importArray = array(
            'cat_name' => 'name',
            'cat_id' => 'id',
        );
        import('libraries.cls_PHPExcel');
        try {
            $excel_obj = new phpExcelMod();
            $xlsArr = @$excel_obj->excelToArray($filePath, $importArray, '2');
        } catch (Exception $exc) {
            showDialog("文档错误！", '', 'error', '');
        }
        foreach($xlsArr as $v){
            Model()->table('category')->insert(array('cat_id'=>$v['cat_id'] ,'cat_name'=>$v['cat_name']));
        }
        echo 'ok';
    }
}

