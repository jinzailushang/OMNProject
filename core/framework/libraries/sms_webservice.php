<?php

/**
 * webservice短信类
 *
 */
defined('InOmniWL') or exit('Access Invalid!');

final class SMS_WEBSERVICE {

    /**
     * 接口地址
     */
    private $url = 'http://61.145.229.29:9003/MWGate/wmgw.asmx';

    /**
     * 请求接口 
     * */
    private $pginface = array('MongateSendSubmit', 'MongateGetDeliver', 'MongateQueryBalance');

    /**
     * 用户账号
     */
    private $userId = 'J02211';

    /**
     * 认证密钥
     */
    private $password = '931920';

    /**
     * 错误信息
     * */
    private $error_msg = array(
        '1' => '成功',
        '-1' => '参数为空。信息、电话号码等有空指针，登陆失败',
        '-12' => '有异常电话号码',
        '-14' => '实际号码个数超过100',
        '-999' => '服务器内部错误',
        '-10001' => '用户登陆不成功(帐号不存在/停用/密码错误)',
        '-10003' => '用户余额不足',
        '-10011' => '信息内容超长',
        '-10029' => '此用户没有权限从此通道发送信息(用户没有绑定该性质的通道，比如：用户发了小灵通的号码)',
        '-10030' => '不能发送移动号码',
        '-10031' => '手机号码(段)非法',
        '-10057' => 'IP受限',
        '-10056' => '连接数超限',
    );

    /**
     * 发送短信
     *
     * @param string $mobile 手机号码
     * @param string $con 短信内容
     * @return array 发送状态
     */
    public function MongateSend($mobile, $con, $pszSubPort = '*') {
        $sms_log_model = Model('sms_log');
        $action = $this->url . "/" . $this->pginface[0];
        if ($mobile == '') {
            return array('status' => '0', 'msg' => '请输入手机号！');
        }

        $Conts = substr_count($mobile, ',') + 1;  //手机号码个数		

        if ($Conts > 100) {
            return array('status' => '0', 'msg' => '号码个数超过100！');
        }

        $lenTest = $this->strLength($con); //短信字符个数
        if ($lenTest > 350) {
            return array('status' => '0', 'msg' => '短信字符个数超过350！');
        }
        //$con = '员工您好，感谢您对鲜LIFE部门此次测试的配合。';//测试短信内容
        $data = array(
            'userId' => $this->userId, //用户账号
            'password' => $this->password, //用户密码
            'pszMobis' => $mobile, //手机号码
            'pszMsg' => str_replace("\\\\", "\\", $con), //短信内容 						
            'iMobiCount' => $Conts, //手机号码个数
            'pszSubPort' => $pszSubPort, //扩展子号
            'MsgId' => '0'            //短信ID
        );
        $re = $this->postSMS($action, $data); //POST方式提交
        //$report = $this->MongateGetDeliver();//短信状态报告
        //记录短信日志
        $param['mobile'] = $mobile;
        $param['msg'] = str_replace("\\\\", "\\", $con);
        $param['addtime'] = date('Y-m-d H:i:s', time());
        $param['status_code'] = $re;
        $param['smsid'] = 0;
        $param['send_type'] = $Conts > 1 ? '2' : '1';
        $param['code'] = $this->error_msg[intval($re)];
        $sms_log_model->addSMSLog($param);

        if (isset($this->error_msg[intval($re)])) {
            return array('status' => '0', 'msg' => $this->error_msg[intval($re)]);
        } else {
            return array('status' => '1', 'msg' => $re);
        }
    }

    /**
     * 查询余额
     * */
    function MongateQueryBalance() {
        $action = $this->url . "/" . $this->pginface[2];
        $data = array(
            'userId' => $this->userId, //用户账号
            'password' => $this->password, //用户密码
        );
        $re = $this->postSMS($action, $data); //POST方式提交

        if (isset($this->error_msg[$re])) {
            return $this->error_msg[$re];
        } else {
            return $re;
        }
    }

    /**
     * 获取上行/状态报告
     * */
    function MongateGetDeliver() {
        $url = $this->url . "/" . $this->pginface[1];
        ;
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port'] : 80;
        $file = $row['path'];

        $post = 'userId=' . $this->userId . '&password=' . $this->password . '&iReqType=2';

        $len = strlen($post);

        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';

            $head = "POST " . $file . " HTTP/1.1\r\n";
            $head .= "Host: " . $host . "\r\n";
            $head .= "Content-type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: " . $len . "\r\n";
            $head .= "\r\n";
            $head .= $post;

            $write = fputs($fp, $head);

            while (!feof($fp)) {
                $receive.= fread($fp, 4096);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
            unset($receive[0]);
            $receive = implode("", $receive);
            preg_match_all("/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/", $receive, $return);
            return $return[2][0];
        }
    }

    /**
     * 短信POST发送
     * 
     * @param $url 短信发送地址
     * @param $data 短信发送内容
     * @return array 短信发送返回数据
     * */
    function postSMS($url, $data = '') {
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port'] : 80;
        $file = $row['path'];
        while (list($k, $v) = each($data)) {
            if ($k == 'pszMsg') {
                $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&"; //转URL标准码				
            } else {
                $post .= rawurlencode($k) . "=" . $v . "&"; //转URL标准码
            }
        }

        $post = substr($post, 0, -1);

        $len = strlen($post);

        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';

            $head = "POST " . $file . " HTTP/1.1\r\n";
            $head .= "Host: " . $host . "\r\n";
            $head .= "Content-type: application/x-www-form-urlencoded\r\n";
            $head .= "Content-Length: " . $len . "\r\n";
            $head .= "\r\n";
            $head .= trim($post);

            $write = fputs($fp, $head);

            while (!feof($fp)) {
                $receive.= fread($fp, 4096);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
            unset($receive[0]);
            $receive = implode("", $receive);
            preg_match_all("/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/", $receive, $return);
            return $return[2][0];
        }
    }

    /**
     * 检验短信字数
     * */
    function strLength($str, $charset = 'utf-8') {
        if ($charset == 'utf-8')
            $str = iconv('utf-8', 'gb2312', $str);
        $num = strlen($str);
        $cnNum = 0;
        for ($i = 0; $i < $num; $i++) {
            if (ord(substr($str, $i + 1, 1)) > 127) {
                $cnNum++;
                $i++;
            }
        }
        $enNum = $num - ($cnNum * 2);
        $number = ($enNum) + $cnNum;
        return ceil($number);
    }

}
