<?php
defined('InOmniWL') or exit('Access Invalid!');

return array(
  'force_type:base' => array(
    'text' => '加固(基础)',
    //'fee' => 5.00,
    'unit' => '单'
  ),
  'force_type:spec' => array(
    'text' => '加固(特殊)',
    //'fee' => 8.00,
    'unit' => '箱'
  ),
  'combine_separate:combine' => array(
    'text' => '合箱分箱(合箱)',
    //'fee' => 15.00,
    'unit' => '单'
  ),
  'combine_separate:separate' => array(
    'text' => '合箱分箱(分箱)',
    //'fee' => 15.00,
    'unit' => '单'
  ),
  'invoice_out:Y' => array(
    'text' => '发票取出',
    //'fee' => 2.00,
    'unit' => '箱'
  ),
  'box_change:out' => array(
    'text' => '换箱(外箱更换)',
    //'fee' => 5.00,
    'unit' => '单'
  ),
  'box_change:auto' => array(
    'text' => '换箱(智能换箱)',
    //'fee' => 0.00,
    'unit' => ''
  ),
  'open_box:Y' => array(
    'text' => '开箱清点',
    //'fee' => 10.00,
    'unit' => '箱'
  ),
  'paste_barcode:Y' => array(
    'text' => '产品条码贴标',
    //'fee' => 2.00,
    'unit' => 'PCS'
  ),
  'pack_size:min' => array(
    'text' => '包装(信封/快递袋)',
    //'fee' => 3.00,
    'unit' => 'PCS'
  ),
  'pack_size:max' => array(
    'text' => '包装(纸箱)',
    //'fee' => 5.00,
    'unit' => 'PCS'
  ),
  'insured:p20' => array(
    'text' => '保价(保￥1000)',
    //'fee' => 5.00,
    'unit' => '单'
  ),
  'insured:p40' => array(
    'text' => '保价(保￥2000)',
    //'fee' => 5.00,
    'unit' => '单'
  ),
);
