<?php

/**
 * 清理缓存
 * @copyright 2015-06-02, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class cacheControl extends SystemControl {

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/cache');
        Language::read('cache');
        Tpl::output('position', '清理缓存');
    }

    /**
     * 清理缓存
     * @copyright 2015-06-02, jack
     */
    public function clearOp() {
        $this->checkPcl();
        $lang = Language::getLangContent();

        if (chksubmit()) {

            //清理所有缓存
            if ($_POST['cls_full'] == 1) {
                H('setting', true);
                delCacheFile('fields');
                redirect_url($lang['cache_cls_ok']);
                exit;
            }

            //清理基本缓存
            if (@in_array('setting', $_POST['cache'])) {
                H('setting', true);
            }

            //清理TABLE缓存
            if (@in_array('table', $_POST['cache'])) {
                delCacheFile('fields');
            }

            $this->log(L('cache_cls_operate'));
            redirect_url($lang['cache_cls_ok']);
        }
        Tpl::showpage('clear', 'index_layout');
    }

}
