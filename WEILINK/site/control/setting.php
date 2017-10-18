<?php

/**
 * 系统设置
 * copyright 2016-06-23, jack
 */
defined('InOmniWL') or exit('Access Invalid!');

class settingControl extends SystemControl {

    private $links = array(
        array('url' => 'act=setting&op=base', 'lang' => 'web_set'),
        array('url' => 'act=setting&op=dump', 'lang' => 'dis_dump'),
    );

    public function __construct() {
        parent::__construct();
        Tpl::setDir('tpl/setting');
        Tpl::output('position', '基本设置');
    }

    /**
     * 基本设置
     * copyright 2015-06-02, jack
     */
    public function systemOp() {
        $this->checkPcl();
        $model_setting = Model('setting');
        if (chksubmit()) {
            unset($_POST['form_submit']);
            foreach ($_POST as $k => $v) {
                $info = $model_setting->getRowSetting($k);
                if ($info) {
                    $res = $model_setting->updateSetting(array($k => $v));
                } else {
                    $res = $model_setting->insertSetting(array('name' => $k, 'value' => $v));
                }
            }
            if ($res) {
                redirect_url('操作成功！');
            } else {
                redirect_url('操作失败！');
            }
        }
        $list_setting = $model_setting->getListSetting();
        foreach ($this->getTimeZone() as $k => $v) {
            if ($v == $list_setting['time_zone']) {
                $list_setting['time_zone'] = $k;
                break;
            }
        }

        Tpl::output('setting', $list_setting);
        Tpl::showpage('system', 'index_layout');
    }

    /**
     * 时区
     * copyright 2015-06-02, jack
     */
    private function getTimeZone() {
        return array(
            '-12' => 'Pacific/Kwajalein',
            '-11' => 'Pacific/Samoa',
            '-10' => 'US/Hawaii',
            '-9' => 'US/Alaska',
            '-8' => 'America/Tijuana',
            '-7' => 'US/Arizona',
            '-6' => 'America/Mexico_City',
            '-5' => 'America/Bogota',
            '-4' => 'America/Caracas',
            '-3.5' => 'Canada/Newfoundland',
            '-3' => 'America/Buenos_Aires',
            '-2' => 'Atlantic/St_Helena',
            '-1' => 'Atlantic/Azores',
            '0' => 'Europe/Dublin',
            '1' => 'Europe/Amsterdam',
            '2' => 'Africa/Cairo',
            '3' => 'Asia/Baghdad',
            '3.5' => 'Asia/Tehran',
            '4' => 'Asia/Baku',
            '4.5' => 'Asia/Kabul',
            '5' => 'Asia/Karachi',
            '5.5' => 'Asia/Calcutta',
            '5.75' => 'Asia/Katmandu',
            '6' => 'Asia/Almaty',
            '6.5' => 'Asia/Rangoon',
            '7' => 'Asia/Bangkok',
            '8' => 'Asia/Shanghai',
            '9' => 'Asia/Tokyo',
            '9.5' => 'Australia/Adelaide',
            '10' => 'Australia/Canberra',
            '11' => 'Asia/Magadan',
            '12' => 'Pacific/Auckland'
        );
    }

}
