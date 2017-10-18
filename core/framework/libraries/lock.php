<?php

/**
 * 解决问题：Linux下需要每5分钟去执行以下某个php脚本，但是这个脚本可能在5分钟之内执行不完，那么怎么去判断php脚本是否正在执行？如果正在执行就不重复执行了。等着下一个5分钟再执行。
 * 保持单步执行，只有一个进程。
 * 运行的时候建个xxx.lock文件，
 * 程序结束时清理锁文件.
 * 每次运行检查文件是否存在，
 * 存在则退出执行
 * @copyright 2015-08-06, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

class lock {

    private $dir = ''; //生成的锁目录
    private $file = ''; //生成的锁文件

    /**
     * 构造函数
     * @copyright 2015-08-06, jack
     */

    public function __construct($config = array()) {
        if (count($config) > 0) {
            $this->_init($config);
        } else {
            $this->file = time();
        }
    }

    /**
     * 变量初始化
     * @copyright 2015-08-06, jack
     * @access	private
     * @param	array	配置数组	 
     * @return	void
     */
    private function _init($config = array()) {
        foreach ($config as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * 返回锁状态
     * @copyright 2015-08-06, jack
     * @access 	public
     * @param 	array 	配置数组
     * @return	boolean
     */
    public function get_status($config = array()) {
        if (count($config) > 0) {
            $this->_init($config);
        }
        $file = $this->dir . '' . $this->file;
        if (file_exists($file)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 生成锁
     * @copyright 2015-08-06, jack
     * @access 	public
     * @param 	array 	配置数组
     * @return	boolean
     */
    public function open_lock($config = array()) {
        if (count($config) > 0) {
            $this->_init($config);
        }
        $file = $this->dir . '' . $this->file;
        return @fopen($file, 'wb ');
    }

    /**
     * 关闭锁
     * @copyright 2015-08-06, jack
     * @access 	public
     * @param 	array 	配置数组
     * @return	boolean
     */
    public function close_lock($config = array()) {
        if (count($config) > 0) {
            $this->_init($config);
        }
        $file = $this->dir . '' . $this->file;
        return @unlink($file);
    }

}

////使用的样例代码如下:
//$lock = new lock(array('dir' => BASE_ROOT_PATH . DS . DIR_LOCK . DS, 'file' => 'text.lock'));
//if ($lock->get_status()) {
//    return FALSE;
//} else {
//    $lock->open_lock();
//    //此处放内容
//    $lock->close_lock();
//}
?>