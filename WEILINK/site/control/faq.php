<?php
/**
 * 问题与帮助
 * @copyright (c) 2016-05-27 16:12:37, jack 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class faqControl extends SystemControl {
    
    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/faq');
    }
    public function indexOp() {
        Tpl::output('position', '问题与帮助');
        Tpl::showpage('index', 'index_layout');
    }
}

