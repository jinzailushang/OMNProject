
<?php
/**
 * 问题与帮助
 * @copyright 
 * */
defined('InOmniWL') or exit('Access Invalid!');

class guideControl extends SystemControl {
    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/guide');
    }
    public function indexOp() {
        Tpl::output('position', '下单指引');
        Tpl::showpage('index', 'index_layout');
    }
}
