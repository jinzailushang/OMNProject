<?php
/**
 * 收件人管理
 * @copyright (c) 2016-05-25, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class consigneeControl extends SystemControl {

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/consignee');
    }
    
    public function indexOp() {
        //省份列表
        $pro_list = Model('area')->getAreaList(array('area_parent_id'=>0));
        Tpl::output('pro_list', $pro_list);
        Tpl::output('position', '收件人管理');
        Tpl::showpage('index', 'index_layout');
    }
    /**
     * 获取入库单列表填充数据
     * @return JSON
     */
    public function get_dataOp() {
        $model_consignee = Model('consignee');
        $condition = $this->get_search_condition();

        $count = $model_consignee->getConsigneeCount($condition);
        $consignee_list = $model_consignee->getConsigneeList($condition, '*', 20);
        $temp = array();
        if($consignee_list){
            die(json_encode(array('status'=>1,'data'=>$consignee_list,'page'=>$model_consignee->showpage(9,'clickpage'),'count'=>$count)));
        }
        die(json_encode(array('status'=>0,'msg'=>'暂无数据','count'=>$count)));
    }
    /**
     * 获取搜索条件
     * @return string
     */
    public function get_search_condition() {
        $condition = array();
        if (trim($_GET['name'])) {
            $condition['name'] = $_GET['name'];
        }
        if (trim($_GET['phone'])) {
            $condition['phone'] = $_GET['phone'];
        }
        if (trim($_GET['province']) && $_GET['province'] != L('nc_please_choose')) {
            $condition['province'] = $_GET['province'];
        }
        if (trim($_GET['city']) && $_GET['city'] != L('nc_please_choose')) {
            $condition['city'] = $_GET['city'];
        }
//        if($this->admin_info['sp'] == 0){
            $condition['u_id'] = $this->admin_info['id'];
//        }

        return $condition;
    }

    public function setDefaultOp($return = FALSE) {
        if (empty($_POST['cid'])) {
            $this->output(0, '无效的收货人ID');
        }
      $flag = empty($_POST['flag']) || $_POST['flag'] != 'N'? 'Y':'N';

      if ($flag == 'Y') {
        $res = Model('consignee')->updateConsignee(array('is_default' => 'Y'), array(
          'u_id' => $this->admin_info['id'], 'cid' => $_POST['cid']));
        if ($res) {
          Model('consignee')->updateConsignee(array('is_default' => 'N'), array('u_id' => $this->admin_info['id'],
            'cid' => array('neq', $_POST['cid'])));
        }
        if ($return) {
          return TRUE;
        }
        $this->output(1, '已设为默认');
      } else {
        Model('consignee')->updateConsignee(array('is_default' => 'N'), array(
          'u_id' => $this->admin_info['id'], 'cid' => $_POST['cid']));
        $this->output(1, '已取消默认');
      }
    }

    public function saveConsigneeOp() {
        /*if (empty($_POST['cid'])) {
            $this->output(0, '无效的收件人ID');
        }*/
        if (empty($_POST['name'])) {
            $this->output(0, '收件人姓名不能为空');
        }
        if (empty($_POST['phone'])) {
            $this->output(0, '收件人电话不能为空');
        }
        if (empty($_POST['ID'])) {
            $this->output(0, '收件人身份证号不能为空');
        }
        if (!$this->IDCardValidate($_POST['ID'])) {
            $this->output(0, '收件人身份证号错误，请重新输入');
        }
        if (empty($_POST['ID_front'])) {
            $this->output(0, '身份证正面照片不能为空');
        }
        if (empty($_POST['ID_back'])) {
            $this->output(0, '身份证背面照片不能为空');
        }
        if (empty($_POST['province'])) {
            $this->output(0, '收件人省份不能为空');
        }
        if (empty($_POST['city'])) {
            $this->output(0, '收件人城市不能为空');
        }
        /*if (empty($_POST['area'])) {
            $this->output(0, '收件人地区不能为空');
        }*/
        if (empty($_POST['address'])) {
            $this->output(0, '收件人地址不能为空');
        }
        if (empty($_POST['zipcode'])) {
            $this->output(0, '收件人邮编不能为空');
        }
      $isnew = false;
      if (empty($_POST['cid'])) {
        $_POST['cid'] = Model('consignee')->addConsignee(array(
          'u_id' => $this->admin_info['id'],
          'name' => $_POST['name'],
          'phone' => $_POST['phone'],
          'ID' => $_POST['ID'],
          'ID_front' => $_POST['ID_front'],
          'ID_back' => $_POST['ID_back'],
          'province' => $_POST['province'],
          'city' => $_POST['city'],
          'area' => $_POST['area']?$_POST['area']:'',
          'address' => $_POST['address'],
          'zipcode' => $_POST['zipcode'],
        ));
        if (empty($_POST['cid'])) {
          $this->output(0,'添加失败');
        }
        $isnew = true;
      } else {
        $result = Model('consignee')->updateConsignee(array(
          'name' => $_POST['name'],
          'phone' => $_POST['phone'],
          'ID' => $_POST['ID'],
          'ID_front' => $_POST['ID_front'],
          'ID_back' => $_POST['ID_back'],
          'province' => $_POST['province'],
          'city' => $_POST['city'],
          'area' => $_POST['area'],
          'address' => $_POST['address'],
          'zipcode' => $_POST['zipcode'],
        ), array('cid' => $_POST['cid'], 'u_id' => $this->admin_info['id']));
        if (!$result) {
          $this->output(0,'更新失败');
        }
      }

      if (!empty($_POST['is_default']) && $_POST['is_default'] == 'Y') {
        $this->setDefaultOp(TRUE);
      }

      $this->output(1, ($isnew?'添加':'更新').'成功');
    }

    public function deleteConsigneeOp() {
        if (empty($_POST['cid'])) {
            $this->output(0, '无效的收件人ID');
        }
        Model('consignee')->delConsignee(array('cid'=>$_POST['cid'], 'u_id'=>$this->admin_info['id']));
        $this->output(1,'删除成功');
    }

  public function exportOp() {
    $data = Model('consignee')->getConsigneeList(array('u_id'=>$this->admin_info['id']));
    $this->createExcel($data);
  }

  /**
   * 收件人导入模板
   */
  public function upload_egOp()
  {
    $file_name = "consignee_eg.xlsx";
    $file_dir = BASE_ROOT_PATH . DS . XLSX_TPL . DS;

    if (!file_exists($file_dir . $file_name)) {
      showDialog("找不到指定文件", '', 'error', '');
    } else {
      $file = fopen($file_dir . $file_name, "r");
      Header("Content-type: application/octet-stream");
      Header("Accept-Ranges: bytes");
      Header("Accept-Length: " . filesize($file_dir . $file_name));
      Header("Content-Disposition: attachment; filename=" . $file_name);
      echo fread($file, filesize($file_dir . $file_name));
      fclose($file);
      exit();
    }
  }

  public function importOp()
  {
    Tpl::output('position', '导入收件人');
    Tpl::showpage('import', 'index_layout');
  }

  /**
   * 收件人导入操作
   */
  public function uploadImportOp()
  {
    set_time_limit(0);
    if (chksubmit()) {
      $file_name = $_POST['file_xls'];
      if (!empty($_FILES['file_xls']['name']) && 0 == $_FILES['file_xls']['error']) {
        $fileName = date('YmdHis') . rand(1, 3009) . ".xls";
        $filePath = BASE_ROOT_PATH . DS . XLSX_TEMP . DS . $fileName;
        if (!file_exists(BASE_ROOT_PATH . DS . XLSX_TEMP)) {
          if (!mkdir(BASE_ROOT_PATH . DS . XLSX_TEMP, 0777, true)) {
            $res = json_encode(array('status' => 0, 'msg' => '创建目录失败！'));
            die("<script>parent.callback(" . $res . ")</script>");
          }
        }
        if (!move_uploaded_file($_FILES['file_xls']['tmp_name'], $filePath)) {
          $is_moved = 0;
        } else {
          $is_moved = 1;
        }
        if (!$is_moved && !copy($_FILES['file_xls']['tmp_name'], $filePath)) {
          $res = json_encode(array('status' => 0, 'msg' => '导入失败！'));
          die("<script>parent.callback(" . $res . ")</script>");
        }
        $model = Model('consignee');
        //读取excel数据
        $res = $model->getHandle($filePath, $this->admin_info['id']);

        if (empty($res['status'])) {
          $res = json_encode(array('status' => 0, 'msg' => $res['msg']));
          die("<script>parent.callback(" . $res . ")</script>");
        }

        $result = $model->saveConsignee($res['data'], $this->admin_info['id']);
        $res = json_encode(array('status' => 1, 'snum' => $result['snum'], 'fnum' => $result['fnum']));
        die("<script>parent.callback(" . $res . ")</script>");
      } else {
        $res = json_encode(array('status' => 0, 'msg' => '请上传批量xls文件！'));
        die("<script>parent.callback(" . $res . ")</script>");
      }
    }
  }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array())
    {
        if (empty($data)) {
            return false;
        }
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id' => 's_title', 'Font' => array('FontName' => '宋体', 'Size' => '12', 'Bold' => '1')));
        //header
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '收件人姓名');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '手机号码');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '身份证号');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '省份');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '城市');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '区县');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '详细地址');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '邮编');
        //data

        $i = 1;
        foreach ((array)$data as $k => $v) {
          $excel_data[$i][] = array('data' => replachString($v['name']));
          $excel_data[$i][] = array('data' => replachString($v['phone']));
          $excel_data[$i][] = array('data' => replachString($v['ID']));
          $excel_data[$i][] = array('data' => replachString($v['province']));
          $excel_data[$i][] = array('data' => replachString($v['city']));
          $excel_data[$i][] = array('data' => replachString($v['area']));
          $excel_data[$i][] = array('data' => replachString($v['address']));
          $excel_data[$i][] = array('data' => replachString($v['zipcode']));
          $i++;
        }
        //print_r($excel_data);die;
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('收件人', CHARSET));
        $excel_obj->generateXML($excel_obj->charset('收件人', CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }
}

