<?php

/**
 * 文本替换类
 * @copyright 2016-01-26, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class cls_string {

    private $_var = array();
    private $template_dir = '';

    public function __construct() {
        
    }

    /**
     * 设置模板目录
     * @copyright 2016-01-26, jack
     * @access  public
     * @param   string      $val
     * 
     * @return  void
     */
    public function setdir($val) {
        $this->template_dir = $val;
    }

    /**
     * 注册变量
     * @copyright 2016-01-26, jack
     * @access  public
     * @param   mix      $tpl_var
     * @param   mix      $value
     * 
     * @return  void
     */
    public function assign($tpl_var, $value = '') {
        if (is_array($tpl_var)) {
            foreach ($tpl_var AS $key => $val) {
                if ($key != '') {
                    $this->_var[$key] = $val;
                }
            }
        } else {
            if ($tpl_var != '') {
                $this->_var[$tpl_var] = $value;
            }
        }
    }
       
    /**
     * 返回替换内容
     * @copyright 2016-01-26, jack
     * @access  public
     * @param   string      $filename
     * @param   string      $cache_id
     * 
     * @return  void
     */
    public function replace($filename, $cache_id = 0) {
        $out = $this->fetch($filename);
        if (count($this->_var) > 0) {
            foreach ($this->_var as $key => $val) {
                if (is_array($val)) {
                    $s = '<volist name=\"' . $key . '\">';
                    $e = '<\/volist>';
                    $str = '/' . ($s) . '(.*)' . ($e) . '/isU';
                    preg_match($str, $out, $m);
                    if (!empty($m)) {
                        $html = $this->get_html($m[1],$val);
                        $out = str_replace($m[0], $html, $out);
                    }
                }else{
                    $out = str_replace('{$' . $key . '}', $val, $out);
                }
            }
        }
        return $out;
    }
    
    /**
     * 返回循环部分
     * @copyright 2016-01-26, jack
     * @access  private
     * @param   string      $html
     * @param   array      $data
     * 
     * @return  string
     */
    private function get_html($out,$data) {
        $str = '';
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                if(is_array($val)){
                    $new = $out;
                    foreach($val as $key2=>$val2){
                        $new = str_replace('{$list["' . $key2 . '"]}', $val2, $new);
                    }
                    $str .= $new;
                }
            }
        }
        return $str;
    }
    /**
     * 处理模板文件
     * @copyright 2016-01-26, jack
     * @access  private
     * @param   string      $filename
     *
     * @return  string
     */
    private function fetch($filename) {
        if (!file_exists($filename)) {
            $filename = $this->template_dir . '/' . $filename;
        }
        if (!file_exists($filename)) {
            return FALSE;
        }
        $out = file_get_contents($filename);
        return $out;
    }

}
//
//<div class="index_content_1">
//
//    <div class='index_fd'>
//        <div class="index-ban">
//			{$name}
//            <div id="focus">
//                <ul>
//                    <volist name="goods">
//                        <li>{$list["name"]}[{$list["sku"]}]</li>
//                    </volist>
//                </ul>
//            </div>
//            <div class="clearfix"></div>
//        </div>
//        <div class="index-ban">
//			{$name}
//            <div id="focus">
//                <ul>
//                    <volist name="goods">
//                        <li>{$list["name"]}[{$list["sku"]}]</li>
//                    </volist>
//                </ul>
//            </div>
//            <div class="clearfix"></div>
//        </div>
//    </div>
//    
//</div> 
//
//$string = new cls_string();
//$string->setdir(BASE_DATA_PATH);
//$goods = array(
//    array('name'=>'产品1','sku'=>'XL000001'),
//    array('name'=>'产品2','sku'=>'XL000002'),
//);
//$string->assign('name','钟必闻');
//$string->assign('goods',$goods);
//echo($string->replace('index.dwt'));