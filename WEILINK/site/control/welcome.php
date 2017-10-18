<?php

/**
 * 欢迎页面
 *
 * */
defined('InOmniWL') or exit('Access Invalid!');

class welcomeControl extends SystemControl {
    
    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/welcome');
    }
    public function indexOp() {
        Tpl::output('position', '欢迎页面');
        Tpl::showpage('index', 'index_layout');
    }
}

