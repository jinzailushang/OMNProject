<?php

/**
 * 包裹接口
 * @copyright 2016-05-27 14:26:13 jack & jay
 */
defined('InOmniWL') or exit('Access Invalid!');
ini_set('soap.wsdl_cache_enabled', '0'); //关闭缓存

class package_serviceModel extends Model
{

  public $wsdl = '';
  public $client = null;
  public $wl_conf = array();

  public function __construct()
  {
    parent::__construct();
    $this->wsdl = C('wl_wsdl');
    $this->client = new SoapClient($this->wsdl);
    $this->wl_conf = array(
      'customerid' => C('wl_customerid'),
//            'huoz' => C('wl_hz'),
//            'qud' => C('wl_jd')
    );
  }

  private function _call($act, $data)
  {
    file_put_contents(BASE_DATA_PATH . DS . 'log/wl.txt', date('Y-m-d H:i:s') . print_r($act, true) . print_r($data, true));
    $Res = $act . 'Result';
    $resu = $this->client->$act(array('request' => $data))->$Res;
    file_put_contents(BASE_DATA_PATH . DS . 'log/wl.txt', date('Y-m-d H:i:s') . print_r($resu, true) . PHP_EOL, FILE_APPEND);
    return $resu;
  }

  /**
   * 获取纵腾商品分类，更新到本地数据库
   * @return boolean
   */
  public function queryCate()
  {
    //接口方法。
    $request = array(
      'Data' => array(
        'CustomerIdentity' => $this->wl_conf['customerid'],
        'IncludeMaxCategory' => 0
      )
    );
    $result = $this->_call('QueryCategory', $request);
    if ($result->Data->QueryCategoryModel) {
      $model = Model('category');
      foreach ($result->Data->QueryCategoryModel as $k => $o) {
        $is_exit = $model->getCategoryCount(array('cat_id' => $o->Code));
        if ($is_exit) {
          continue;
        } else {
          $model->addCategory(array('cat_id' => $o->Code, 'cat_name' => $o->Name, 'cat_name_en' => $o->EnName, 'tariff_number' => $o->TariffNumber));
        }
      }
    }
    return true;
  }

  /**
   * 包裹物流状态查询
   * @param type $track_no
   * @return boolean
   */
  public function queryOrderStatus($track_no)
  {
    //接口方法。
    $request = array(
      'Data' => $track_no
    );
    $result = $this->_call('QueryTraceStatusFlow', $request);
    //接口方法。
    //$result = $this->_call('UploadIDCard', $request);
    /*if ($result->ResponseResult == 'Success') {
      $flows = $result->Data->TraceFlow;
      $finish = false;
      foreach ($flows as $flow) {
        if ($flow['StatusDesc'] == '已收寄') {
          $finish = true;
          break;
        }
      }
      return array('status' => $finish?1:0);
    } else {
      return array('status' => 0, 'msg' => $result->ResponseError->LongMessage);
    }
    */
    return $result;
  }

  /**
   * 创建并打印面单(直邮)
   * @param array $order_info 运单信息
   * @return type
   */
  public function createdAndPrintOrderDm($order_info)
  {
    if (empty($order_info)) {
      return;
    }
    $customer_code = $order_info['customer_code'];
    $model = Model('order');
    if ($order_info) {
      $goods = array();
      $goods_list = $model->getOrderGoodsList(array('order_id' => $order_info['order_id']));
      //货品信息
      if ($goods_list) {
        foreach ($goods_list as $k => $v) {
          $goods[$k]['CategoryCode'] = $v['cat_id'];
          $goods[$k]['TariffNumber'] = '';     //CategoryCode与TariffNumber至少填写一个
          $goods[$k]['GoodsName'] = $v['goods_name'];
          $goods[$k]['Brands'] = $v['bland'];
          $goods[$k]['ModelNo'] = '2';
          $goods[$k]['Qty'] = $v['goods_num'];
          $goods[$k]['Unit'] = $v['goods_unit'];
          $goods[$k]['Price'] = $v['goods_price'];
        }
      }
      $area_info = Model('area')->getAreaInfo(array('area_name' => $order_info['reciver_state'], 'area_parent_id' => 0), 'area_id');
      //收件人地址信息
      $address = array(
        'ToName' => $order_info['reciver_name'],
        'ToAddress' => $order_info['reciver_address'],
        'ToCity' => $order_info['reciver_city'],
        'ToProvinceCode' => $area_info['area_id'],
        'ToArea' => $order_info['reciver_area'],
        'ToZIP' => $order_info['reciver_zipcode'],
        'ToEmail' => 'abc@126.com',
        'ToProvince' => $order_info['reciver_state'],
        'ToMobile' => $order_info['reciver_phone'],
      );
      //发件人信息
      $sender = array(
        'FromName' => $order_info['sender'],
        'FromAddress' => $order_info['sender_address'],
        'FromCity' => $order_info['sender_city'],
        'FromArea' => $order_info['sender_area'],
        'FromZIP' => $order_info['sender_zipcode'],
        'FromEmail' => 'info@163.com',
        'FromMobile' => $order_info['sender_phone'],
        'FromProvince' => $order_info['sender_province']
      );

      $request = array(
        'Data' => array(
          'CustomerIdentity' => $this->wl_conf['customerid'],
          'TrackingCenterCode' => $order_info['tc_code'], //货站编码
          'InsureStatus' => $order_info['is_cover'] == '是' ? 1 : 0, //是否投保;0-否，1-是
          'HasPrepaid' => $order_info['is_tariff'] == '是' ? 1 : 0, //是否代缴关税；0-否，1-是
          'Origin' => $order_info['origin'],
          'Weight' => isset($order_info['order_weight']) ? $order_info['order_weight'] : 0,
          'Length' => 1,
          'Height' => 1,
          'Width' => 1,
          'Addressee' => $address,
          'Goods' => $goods,
          'Sender' => $sender,
          'ChannelCode' => $order_info['channel'], //渠道
          'IdCardNumber' => $order_info['identity_code'], //身份证号码
          'HasReplaceUploadIdCard' => $order_info['has_identity'] == '是' ? 1 : 0  //是否代传身份证；0-否，1-是
        )
      );
      //接口方法。
      $result = $this->_call('CreatedAndPrintOrder', $request);

      if ($result->ResponseResult == 'Success') {
        //状态是20（草稿）的，才执行以下操作
        if ($order_info['order_state'] == 20) {
          $dir = DS . DIR_UPLOAD . DS . 'order' . DS . date('Ymd') . DS;
          mk_dir(BASE_ROOT_PATH . $dir);
          $file_name = mt_rand(1000000, 9999999) . '.png';
          file_put_contents(BASE_ROOT_PATH . $dir . $file_name, $result->Data->PDFStream);
          $model_ship = Model('shipment_code');
          //查找一个没有使用过的物流单号赋予shipping_code
          $ship = $model_ship->getShipmentCodeInfo(array('flag' => 0));
          if ($ship) {
            $code = $ship['scode'];
          } else {
            //如果物流单号已用完，则生成一个
            $code = $model_ship->buildCode(1);
          }
          $model->beginTransaction();
          try {
            //更新状态以及使用时间
            if (!$model_ship->updateShipmentCode(array('flag' => 1, 'use_time' => time()), array('scode' => $code))) {
              throw new Exception('更新物流表失败！');
            }
            //更新订单状态
            $update_arr = array(
              'order_state' => 30,  //发货中
              'shipping_code' => $code,  //分配一个内部的物流单号
              'track_no' => $result->Data->TrackingNumber,   //存放纵腾返回的物流单号
              'barcode_img' => $dir . $file_name
            );
            if (!$model->updateOrder($update_arr, array('order_id' => $order_info['order_id']))) {
              throw new Exception('更新订单表失败');
            }
            $model->commit();
            return array('status' => 1, 'img' => "<img src=" . substr(SITE_SITE_URL, 0, -4) . $dir . $file_name . " />");
          } catch (Exception $ex) {
            $model->rollback();
            return array('status' => 0, 'msg' => $ex->getMessage());
          }
        }
      }
      return array('status' => 0, 'msg' => $result->ResponseError->LongMessage);
    }
  }

  /**
   * 创建并打印面单(转运)
   * @param array $order_info
   * @param int $userid
   * @return type
   */
  public function createdAndPrintOrderTp($order_info, $userid)
  {
    $model = Model('order');
    $customer_code = $order_info['customer_code'];
    if ($order_info) {
      $goods = array();
      $goods_list = $model->getOrderGoodsList(array('order_id' => $order_info['order_id']));
      //货品信息
      if ($goods_list) {
        foreach ($goods_list as $k => $v) {
          $goods[$k]['MinCategoryCode'] = $v['cat_id'];
          $goods[$k]['GoodsName'] = $v['goods_name'];
          $goods[$k]['Brand'] = $v['bland'];
          $goods[$k]['ModelNo'] = '1';
          $goods[$k]['Quantity'] = $v['goods_num'];
          $goods[$k]['Unit'] = $v['goods_unit'];
          $goods[$k]['Price'] = $v['goods_price'];
        }
      }
      $area_info = Model('area')->getAreaInfo(array('area_name' => $order_info['reciver_state'], 'area_parent_id' => 0), 'area_id');
      //收件人地址信息
      $address = array(
        'ProvinceCode' => $area_info['area_id'],
        'Name' => $order_info['reciver_name'],
        'Street' => $order_info['reciver_address'],
        'City' => $order_info['reciver_city'],
        'Province' => $order_info['reciver_state'],
        'Area' => $order_info['reciver_area'],
        'ZIP' => $order_info['reciver_zipcode'],
        'Telphone' => $order_info['reciver_phone']
      );
      $user_info = Model('user')->getUserInfo(array('user.u_id' => $userid));
      if ($user_info['area']) {
        $res = $this->getFullAreaName($user_info['area']);
        krsort($res); //反向排序
        $addr = implode('', $res) . $user_info['address'];
      } else {
        $addr = $user_info['address'];
      }
      //发件人信息
      $sender = array(
        'Name' => $user_info['first_name'] || $user_info['last_name'] ? $user_info['first_name'] . $user_info['last_name'] : $user_info['u_name'],
        'Street' => $addr ? $addr : '--',
        'ZIP' => $user_info['zipcode'] ? $user_info['zipcode'] : '--',
        'Telphone' => $user_info['phone'] ? $user_info['phone'] : '--'
      );

      $request = array(
        'Data' => array(
          array(
            'CustomerOrderNumber' => $customer_code, //客户订单号，用于标识哪些包裹需合箱且要合在一起
            'TrackingNumber' => $order_info['pre_track_no'], //预报跟踪号，要求唯一
            'FreightCompany' => $order_info['company'], //货运公司
            'HasReplaceUploadIdCard' => $order_info['has_identity'] == '是' ? 1 : 0, //是否代传身份证；0-否，1-是
            'IdCardNumber' => $order_info['identity_code'], //身份证号码
            'DeclaredValue' => $order_info['order_amount'],
            'HasPrepaid' => $order_info['is_tariff'] == '是' ? 1 : 0, //是否代缴关税；0-否，1-是
            'IsInsure' => $order_info['is_cover'] == '是' ? 1 : 0, //是否投保;0-否，1-是
            'IsReplaceOuterBox' => $order_info['is_box_ch'] == '是' ? 1 : 0, //是否替换外箱(合箱包裹，该字段无效)
            'AllowAutoExchange' => $order_info['is_auto_ch'] == '是' ? 1 : 0, //是否智能换箱
            'IsRemovedInvoice' => $order_info['invoice_out'] == '是' ? 1 : 0, //是否取出发票
            'IsVerifyGoods' => $order_info['open_box'] == '是' ? 1 : 0, //是否开箱清点
            'Remark' => $order_info['remark'],
            'FirmType' => $order_info['force_type'], //加固类型。0-不加固；1-基础加固，2-特殊加固
            'CustomerIdentity' => $this->wl_conf['customerid'], //客户标识
            'TrackingCenterCode' => $order_info['tc_code'],  //货站编码
            'ChannelCode' => $order_info['channel'],  //渠道编码
            'CurrencyCode' => $order_info['currency'], //货币币种
            'Origin' => $order_info['origin'],
            'Address' => $address,
            'SendAddress' => $sender,
            'Goods' => $goods
          )
        )
      );
      //接口方法。
      $result = $this->_call('ImportBatchTrackingExpressBill', $request);
      return $result;
    }
  }

  /**
   * 身份证上传
   * @param type $customer_code
   * @return type
   */
  public function uploadIDCard($order_info)
  {

    if ($order_info['id_card_front'] && $order_info['id_card_back'] && file_exists(BASE_ROOT_PATH . $order_info['id_card_front']) && file_exists(BASE_ROOT_PATH . $order_info['id_card_back'])) {
      $request = array(
        'Data' => array(
          'IDCardNumber' => $order_info['identity_code'], //身份证号码
          'Addressee' => $order_info['reciver_name'],
          'OrderNumberOrTrackingNumber' => $order_info['pre_track_no']?$order_info['pre_track_no']:($order_info['track_no']?$order_info['track_no']:'--') , //预报跟踪号或物流跟踪号
          'IDCardFront' => file_get_contents(BASE_ROOT_PATH . $order_info['id_card_front']),
          'IDCardBack' => file_get_contents(BASE_ROOT_PATH . $order_info['id_card_back']),
          'CoverOldIDCard' => 1  //是否覆盖旧身份证,1覆盖,0不覆盖
        )
      );
      //接口方法。
      $result = $this->_call('UploadIDCard', $request);
      if ($result->ResponseResult == 'Success') {
        return array('status' => 1, 'msg' => '同步身份证成功！');
      } else {
        return array('status' => 0, 'msg' => $result->ResponseError->LongMessage);
      }
    } else {
      return array('status' => 0, 'msg' => '未上传身份证');
    }
  }

  /**
   *  获取重量、长宽高、物流追踪号(只适合转运)
   * @param string $track_no 预报跟踪号
   * @return array
   */
  public function getWeightTp($track_no)
  {
    //接口方法。
    $request = array(
      'Data' => $track_no
    );
    $result = $this->_call('SearchTesOrderWeightInformation', $request);
    if ($result->ResponseResult == 'Success') {
      return array('status'=>1, 'data'=>$result->Data->Weight);
    } else {
      return array('status' => 0, 'msg' => $result->ResponseError->LongMessage);
    }
  }

  /**
   * Interface on 2016-7-21
   * // @todo
   */

  /**
   * 注册URL
   *
   * @param $GetMessageUrl
   * @param $CustomerIdentity
   * @return array
   */
  public function RegisteredUrl($GetMessageUrl, $CustomerIdentity) {
    //接口方法。
    $request = array(
      'GetMessageUrl' => $GetMessageUrl,
      'CustomerIdentity' => $CustomerIdentity
    );
    $result = $this->_call('RegisteredUrlRequest', $request);
    if ($result->ModifyTraceOrderResponse->IsSuccess == 'true') {
      return array('status'=>1);
    } else {
      return array('status' => 0, 'msg' => $result->MessagModifyTraceOrderResponsee);
    }
  }

  /**
   * 根据area_id 获取上级地区名称（包含本级）
   * @param int $gid
   * @return array
   */
  private function getFullAreaName($areaid)
  {
    $info = Model()->table('area')->where(array('area_id' => $areaid))->field('area_name,area_parent_id')->find();
    $arr[] = $info['area_name'];
    if ($info['area_parent_id']) {
      $return = $this->getFullAreaName($info['area_parent_id']);
      if (count($return)) {
        foreach ($return as $a) {
          $arr[] = $a;
        }
      }
    }
    return $arr;
  }

}
