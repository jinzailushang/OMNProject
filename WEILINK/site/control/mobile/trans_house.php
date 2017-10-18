<?php

/**
 * 转运仓数据
 */
class trans_houseControl extends SystemControl
{
  public function __construct()
  {
    //parent::__construct();
  }

  public function listOp()
  {
    $trans_houses = Model('trans_house')->getTransHouseList();
    $list = array();
    foreach ($trans_houses as $th) {
      $item = array(
        'warehouseId' => $th['tc_code'],
        'warehouseName' => $th['tc_name'],
        'warehouseCountry' => $th['country'],
        'warehousePrice' => '首重:' . $th['first_weight_fee'] . '元,续重:' . $th['continue_weight_fee'].'元',
        'warehouseAddress' => array( //货站地址
          'name' => $th['receiver'], //收货人姓名
          'address' => $th['address'], //地址
          'city' => $th['city'],
          'state' => $th['province'],
          'zipCode' => $th['zipcode'],
          'tel' => $th['phone'],
        ),
        'countryImg' => $th['country_img'],
        'valueAddedService' => $this->_getExtraService($th)
      );
      $list[] = $item;
    }

    $this->output(1, null, $list);
  }

  public function getInfoOp()
  {
    $warehouseId = $_GET['warehouseId'];
    if (!$warehouseId) {
      $this->output(0, '无效的货站ID!');
    }
    $info = model('trans_house')->getTransHouseInfo(array('tc_code' => $warehouseId));
    if (!$info) {
      $this->output(0, '无效的货站ID!!');
    }
    $data = array(
      'warehouseAddress' => array(       //货站地址
        'name' => $info['tc_name'],                //收货人姓名
        'address' => $info['address'],            //地址
        'city' => $info['city'],
        'state' => $info['province'],
        'zipCode' => $info['zipcode'],
        'tel' => $info['phone'],
      ),
      'countryImg' => $info['country_img'],
      'valueAddedService' => $this->_getExtraService($info)    //增值服务
    );

    $this->output(1, NULL, $data);
  }

  private function _getExtraService($th)
  {
    $extra_services_list = require BASE_PATH . '/include/extra_service_fee.php';
    $return = array();
    foreach ($extra_services_list as $ekey => $eval) {
      list($key, $val) = explode(':', $ekey);
      if (isset($th[$key])) {
        $name = explode('(', $eval['text']);
        if (!isset($return[$key])) {
          $return[$key] = array(
            'name' => $name[0],
            'options' => array()
          );
        }
        if (isset($name[1])) {
          $name = substr($name[1], 0, -1);
        } else {
          $name = $name[0];
        }
        $data = json_decode($th[$key], TRUE);
        $return[$key]['options'][$val] = array(
          'text' => $name,
          'fee' => $data[$val]
        );
      }
    }
    /*$extra_services = array(
      'force_type' => array(
        'name' => '加固类型',
        'options' => array(
          '0' => array('text'=>'不加固','fee'=>'0.00'),
          '1' => array('text'=>'基础加固', 'fee'=>'0.00'),
          '2' => array('text'=>'特殊加固','fee'=>'0.00')
        )
      ),
      'is_cover' => array(
        'name' => '是否投保',
        'options' => array(
          '是' => array('text'=>'是', 'fee'=>'0.00'),
          '否' => array('text'=>'否','fee'=>'0.00')
        )
      ),
      'is_invoice' => array(
        'name' => '是否取出发票',
        'options' => array(
          '是' => array('text'=>'是', 'fee'=>'0.00'),
          '否' => array('text'=>'否','fee'=>'0.00')
        )
      ),
      'box_change' => array(
        'name' => '是否外箱替换',
        'options' => array(
          'out' => array('text'=>'更换外箱', 'fee'=>'0.00'),
          'auto' => array('text'=>'智能换箱','fee'=>'0.00')
        )
      ),
      'open_box' => array(
        'name' => '是否开箱清点',
        'options' => array(
          '是' => array('text'=>'是', 'fee'=>'0.00'),
          '否' => array('text'=>'否','fee'=>'0.00')
        )
      ),

    );

    $config = require BASE_PATH.'/include/extra_service_fee.php';
    foreach ($extra_services as $k=>$v) {
      foreach($config as $ck=>$cv) {
        list($kk,$vv) = explode(' => ', $ck);
        if ($kk == $k) {
          $extra_services[$k]['options'][$vv]['fee'] = sprintf('%.2f',$cv['fee']);
        }
      }
    }

    return $extra_services;*/
    return $return;
  }
}
