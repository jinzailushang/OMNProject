<?php

/**
 * 接口api模块
 * copyright 2016-07-28, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class indexControl extends apiControl {

    public function indexOp() {
        $funcode = explode('_', $_POST['funcode']);
        $act = $funcode[0];
        if ($act) {
            @include(dirname(__FILE__) . '/'.$act . '.php');
            $control = $act . 'Control';
            $obj = new $control();
            $act1 = isset($funcode[1]) ? $funcode[1] : 'index';
            $act1 = $act1.'Op';
            $obj->$act1();
        } else {
            $this->output(0, '错误的操作');
        }
    }

}
