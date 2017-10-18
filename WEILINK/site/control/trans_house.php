<?php

/**
 *  货站管理
 * @copyright (c) 2016-06-02 15:00:45, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class trans_houseControl extends SystemControl {

    public $tc_type_arr = array('zt' => '纵腾仓', 'wms' => '威廉仓');

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/trans_house');
    }

    public function indexOp() {
        Tpl::output('position', '货站管理');
        Tpl::showpage('index', 'index_layout');
    }

    /**
     * 获取货站单列表填充数据
     * @return JSON
     */
    public function get_dataOp() {
        $model = Model('trans_house');
        $model_chn = Model('trans_house_channel');
        $condition = $this->get_search_condition();

        $count = $model->getTransHouseCount($condition);
        $list = $model->getTransHouseList($condition, '*', 20);
        $temp = array();
        if ($list) {
            foreach ($list as &$v) {
                $chns = $model_chn->getTransHouseChannelList(array('house_id'=>$v['tid']));
                $v['tc_type'] = isset($this->tc_type_arr[$v['tc_type']]) ? $this->tc_type_arr[$v['tc_type']] : '--';
                $v['channels'] = $chns;
            }
            die(json_encode(array('status' => 1, 'data' => $list, 'page' => $model->showpage(9, 'clickpage'), 'count' => $count)));
        }
        die(json_encode(array('status' => 0, 'msg' => '暂无数据', 'count' => $count)));
    }

    /**
     * 获取搜索条件
     * @return string
     */
    public function get_search_condition() {
        $condition = array();
        if (trim($_GET['tc_code'])) {
            $condition['tc_code'] = $_GET['tc_code'];
        }
        if (trim($_GET['tc_name'])) {
            $condition['tc_name'] = $_GET['tc_name'];
        }
        if (trim($_GET['province']) && $_GET['province'] != L('nc_please_choose')) {
            $condition['province'] = $_GET['province'];
        }
        if (trim($_GET['city']) && $_GET['city'] != L('nc_please_choose')) {
            $condition['city'] = $_GET['city'];
        }


        return $condition;
    }
    public function detailOp() {
        $tid = $this->getGetData('tid');
        $info = Model('trans_house')->getTransHouseInfo(array('tid'=>$tid));
        $info['force_type'] = json_decode($info['force_type'],true);  //加固类型
        $info['combine_separate'] = json_decode($info['combine_separate'],true);  //分箱
        $info['box_change'] = json_decode($info['box_change'],true);  //外箱更换
        $info['pack_size'] = json_decode($info['pack_size'],true); //包装：min(信封/快递袋),max(纸箱)
        $info['open_box'] = json_decode($info['open_box'],true);  //开箱清点
        $info['invoice_out'] = json_decode($info['invoice_out'],true);  //发票取出
        $info['insured'] = json_decode($info['insured'],true);  //保价类型
        $channels = Model('trans_house_channel')->getTransHouseChannelList(array('house_id'=>$tid));
        Tpl::output('info', $info);
        Tpl::output('channels', $channels);
        Tpl::output('fixs', '元/单');
        Tpl::showpage('detail', 'null_layout');
    }

    /**
     * 获取单个货站信息
     */
    public function get_tc_infoOp() {
        $tid = $this->getGetData('tid', 0);
        $ship_method = $this->getGetData('ship_method');
        if ($tid) {
            $data = Model('trans_house')->getTransHouseInfo(array('tid' => $tid));
            if ($data) {
                $channel_list = $channel_info = array();
                $channel_list = Model('trans_house_channel')->getTransHouseChannelList(array('house_id'=>$tid),'channel_name,channel_code');
                file_put_contents('aa.log', print_r($channel_list,true).print_r($tid,true));
                if($ship_method){
                    $channel_info = Model('trans_house_channel')->getTransHouseChannelInfo(array('house_id'=>$tid,'channel_code'=>$ship_method));
                }
                die(json_encode(array('status' => 1, 'data' => $data,'channel_list'=>$channel_list,'channel_info'=>$channel_info)));
            }
        }
        die(json_encode(array('status' => 0)));
    }

    public function settingOp() {
        Tpl::output('position', '货站管理');
        Tpl::output('extra_service_list', require BASE_PATH . '/include/extra_service_fee.php');
        Tpl::output('is_super', $this->admin_info['id'] == 1);
        Tpl::output('tc_type_list', $this->tc_type_arr);
        Tpl::showpage('index', 'index_layout');
    }

    public function saveOp() {
        $tid = $this->getPostData('tid', 0);
        if ($tid) {
            if (!Model('trans_house')->is_exist(array('tid' => $tid))) {
                die(json_encode(array('status' => 0, 'msg' => '无效的tid')));
            }
        } elseif (Model('trans_house')->is_exist(array('tc_code' => $this->getPostData('tc_code'))) ||
                Model('trans_house')->is_exist(array('tc_name' => $this->getPostData('tc_name')))
        ) {
            die(json_encode(array('status' => 0, 'msg' => '货站编号或者货站名称已存在')));
        }
        $fields = explode(',', 'tc_code,tc_name,tc_type,country,province,city,address,zipcode,phone,receiver,currency,countrys_img');
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->getPostData($field, '');
        }
        if ($data['countrys_img'] == 'templates/default/images/sfz.png') {
            $data['country_img'] = '';
        } else {
            $data['country_img'] = str_replace('../', '/', $data['countrys_img']);
        }
        unset($data['countrys_img']);

        $extra_service_list = require BASE_PATH . '/include/extra_service_fee.php';
        foreach ($extra_service_list as $key => $esl) {
            list($key, $val) = explode(':', $key);
            if (!isset($data[$key])) {
                $data[$key] = json_encode($_POST[$key]);
            }
            unset($val);
        }
        $data['channel'] = $_POST['channel'];
        $model = Model('trans_house');
        $model_chn = Model('trans_house_channel');
        $model->beginTransaction();
        try {
            $channel = $data['channel'];
            if(count($channel) > 1){
                $cks = array();
                foreach($channel as $row){
                    $cks[] = $row[1];
                }
                if(count(array_unique($cks)) == 1){
                    die(json_encode(array('status' => 0, 'msg' => '一个中转仓不能有相同的渠道编码')));
                }
            }

            unset($data['channel']);  
            //如果$tid存在，则编辑
            if ($tid) {
                $result = Model('trans_house')->updateTransHouse($data, array('tid' => $tid));
                if(!$result){
                    throw new Exception('编辑渠道失败！');
                }
                //找出旧的渠道
                $chns = $model_chn->getTransHouseChannelList(array('house_id'=>$tid));
                //新的渠道代码
                $new_chn = array();
                foreach($channel as $k=>$v){
                    $new_chn[] = $v[1];
                    $count = $model_chn->getTransHouseChannelCount(array('house_id'=>$tid,'channel_code'=>$v[1]));
                    $chn_arr = array(
                        'channel_name' => $v[0],
                        'first_weight' => $v[2],
                        'continue_weight' => $v[3],
                        'first_weight_fee' => $v[4],
                        'continue_weight_fee' => $v[5],
                        'first_weight_fee_h' => $v[6],
                        'continue_weight_fee_h' => $v[7]
                    );
                    
                    if($count){
                        $res_update = $model_chn->updateTransHouseChannel($chn_arr,array('house_id'=>$tid,'channel_code'=>$v[1]));
                        if(!$res_update){
                            throw new Exception('创建渠道失败！');
                        }
                    }else{
                        $chn_arr['channel_code'] = $v[1];
                        $chn_arr['house_id'] = $tid;
                        $arr = array($chn_arr);
                        $res_add = $model_chn->addTransHouseChannel($arr);
                        if(!$res_add){
                            throw new Exception('创建渠道失败！');
                        }
                    }
                }
                //删除多余的渠道
                $condition['house_id'] = $tid;
                $condition['channel_code'] = array('not in',$new_chn);
                $model_chn->delTransHouseChannel($condition);
            } else {  //如果$tid不存在，则新增
                $last_id = Model('trans_house')->addTransHouse($data);
                if(!$last_id){
                    throw new Exception('创建中转仓失败！');
                }
                $chn = array();
                foreach($channel as $k=>$v){
                    $chn[] = array(
                        'channel_name' => $v[0],
                        'channel_code' => $v[1],
                        'first_weight' => $v[2],
                        'continue_weight' => $v[3],
                        'first_weight_fee' => $v[4],
                        'continue_weight_fee' => $v[5],
                        'first_weight_fee_h' => $v[6],
                        'continue_weight_fee_h' => $v[7],
                        'house_id' => $last_id
                    );
                }
                $add_res = $model_chn->addTransHouseChannel($chn);
                if(!$add_res){
                    throw new Exception('创建渠道失败！');
                }
            }
            $model->commit();
            die(json_encode(array('status' => 1, 'msg' => '操作成功')));
        } catch (Exception $ex) {
            $model->rollback();
            die(json_encode(array('status' => 0, 'msg' => $ex->getMessage())));
        } 
    }

    public function delOp() {
        $tid = $this->getPostData('tid', 0);
        if (!$tid || !Model('trans_house')->is_exist(array('tid' => $tid))) {
            die(json_encode(array('status' => 0, 'msg' => '无效的tid')));
        }

        Model('trans_house')->delTransHouse(array('tid' => $tid));

        die(json_encode(array('status' => 1, 'msg' => '删除成功')));
    }

    public function uploadCountryPicOp() {
        $ID_front = $this->base642jpeg($_POST['file'], $this->getBase64FileName($_POST['file'], '../data/upload/country'));
        echo $ID_front;
    }

}
