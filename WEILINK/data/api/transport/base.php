<?php

/**
 * 转运接口类
 * @copyright (c) 2016-08-02, coolzbw 
 */
defined('IN_TRANSPORT_QU') or exit('Access Invalid!');

class transport_base {

    private $save_version = '2.0.0.1';

    /**
     * 转运接口类
     * @copyright (c) 2016-08-02, coolzbw 
     */
    public function load($model, $base = NULL) {
        $filedir = QU_TRANSPORT_ROOT . "/$model/transport.class.php";
        if ((file_exists($filedir) === false) || (is_readable($filedir) === false)) {
            return false;
        } else {
            include_once($filedir);
        }
        $base = $base ? $base : $this;
        if (empty($_ENV[$model])) {
            eval('$_ENV[$model] = new ' . $model . '_model($base);');
        }
        return $_ENV[$model];
    }

}
