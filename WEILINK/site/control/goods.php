<?php
/**
 * 收件人管理
 * @copyright (c) 2016-05-25, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class goodsControl extends SystemControl {

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/goods');
    }
    
    public function indexOp() {
        //省份列表
        Tpl::output('cat_list', Model('category')->getCategoryList());
        Tpl::output('unit_list', Model('measure')->getMeasureList());
        Tpl::output('position', '商品管理');
        Tpl::showpage('index', 'index_layout');
    }
    /**
     * 获取入库单列表填充数据
     * @return JSON
     */
    public function get_dataOp() {
        $model_goods = Model('goods');
        $condition = $this->get_search_condition();

        $count = $model_goods->getGoodsCount($condition);
        $goods_list = $model_goods->getGoodsList($condition, '*', 20);
        $temp = array();
        if($goods_list){
            foreach($goods_list as $k=>$g) {
                $cat = Model('category')->getCategoryInfo(array('cat_id'=>$g['cat_id']));
                $goods_list[$k]['cat_name'] = $cat['cat_name'];
                $unit = Model('measure')->getMeasureInfo(array('id'=>$g['unit_id']));
                $goods_list[$k]['measure_name_cn'] = $unit['measure_name_cn'];
            }
            die(json_encode(array('status'=>1,'data'=>$goods_list,'page'=>$model_goods->showpage(9,'clickpage'),'count'=>$count)));
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
        if (trim($_GET['cat_id'])) {
            $condition['cat_id'] = $_GET['cat_id'];
        }
//        if($this->admin_info['sp'] == 0){
            $condition['u_id'] = $this->admin_info['id'];
//        }

        return $condition;
    }

    public function saveGoodsOp() {
        if (empty($_POST['name'])) {
            $this->output(0, '商品名称不能为空');
        }
        if (empty($_POST['cat_id'])) {
            $this->output(0, '商品分类不能为空');
        }
        if (empty($_POST['unit_id'])) {
            $this->output(0, '商品单位不能为空');
        }
        if (empty($_POST['brand'])) {
            $this->output(0, '商品品牌不能为空');
        }
        if (empty($_POST['price'])) {
            $this->output(0, '商品价格不能为空');
        }
        if (!is_numeric($_POST['price']) || $_POST['price'] < 0.01) {
            $this->output(0, '商品价格格式不对');
        }
        $data = array(
          'name' => $_POST['name'],
          'cat_id' => $_POST['cat_id'],
          'unit_id' => $_POST['unit_id'],
          'brand' => $_POST['brand'],
          'price' => $_POST['price']
        );
        if (!empty($_POST['id'])) {
            Model('goods')->updateGoods($data, array('u_id'=>$this->admin_info['id'], 'id'=>$_POST['id']));
        } else {
            $data['u_id'] = $this->admin_info['id'];
            $data['add_time'] = time();
            Model('goods')->addGoods($data);
        }

        $this->output(1, ($_POST['id']?'更新':'添加').'成功');
    }

    public function deleteGoodsOp() {
        if (empty($_POST['id'])) {
            $this->output(0, '无效的商品ID');
        }
        Model('goods')->delGoods(array('id'=>$_POST['id'], 'u_id'=>$this->admin_info['id']));
        $this->output(1,'删除成功');
    }

    public function exportOp() {
        $data = Model('goods')->getGoodsList(array('u_id'=>$this->admin_info['id']));
        $this->createExcel($data);
    }

  /**
   * 收件人导入模板
   */
  public function upload_egOp()
  {
    $file_name = "goods_eg.xlsx";
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
    Tpl::output('position', '导入商品');
    Tpl::showpage('import', 'index_layout');
  }


  /**
   * 商品导入操作
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
        $model = Model('goods');
        //读取excel数据
        $res = $model->getHandle($filePath, $this->admin_info['id']);

        if (empty($res['status'])) {
          $res = json_encode(array('status' => 0, 'msg' => $res['msg']));
          die("<script>parent.callback(" . $res . ")</script>");
        }

        $result = $model->saveGoods($res['data'], $this->admin_info['id']);
        $res = json_encode(array('status' => 1, 'snum' => $result['snum'], 'fnum' => $result['fnum'],'error' => $result['error']));
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
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '商品名称');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '分类');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '单位');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '品牌');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '价格');
        //data
        $model = Model();
        $model_cat = Model('category');
        $model_unit = Model('measure');

        $i = 1;
        foreach ((array)$data as $k => $v) {
          $excel_data[$i][] = array('data' => replachString($v['name']));
          $cate = $model_cat->getCategoryInfo(array('cat_id' => $v['cat_id']));
          $unit = $model_unit->getMeasureInfo(array('id' => $v['unit_id']));
          $excel_data[$i][] = array('data' => $cate ? replachString($cate['cat_name']) : '');
          $excel_data[$i][] = array('data' => $unit ? replachString($unit['measure_name_cn']) : '');
          $excel_data[$i][] = array('data' => replachString($v['brand']));
          $excel_data[$i][] = array('data' => $v['price']);
          $i++;
        }
        //print_r($excel_data);die;
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('商品', CHARSET));
        $excel_obj->generateXML($excel_obj->charset('商品', CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }
}

