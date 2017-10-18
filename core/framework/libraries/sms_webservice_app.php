<?php

/**
 * webservice短信类
 *
 */
defined('InOmniWL') or exit('Access Invalid!');

final class SMS_WEBSERVICE_APP {

    /**
     * 接口地址
     */
    private $url = 'http://106.ihuyi.cn/webservice/sms.php?method=';

    /**
     * 请求接口 
     * */
    private $pginface = array('Submit', 'GetNum', 'ChangePassword');

    /**
     * 用户账号
     */
    private $account = 'cf_xiansh';

    /**
     * 认证密钥
     */
    private $password = '123456';

    /**
     * 错误信息
     * */
    private $error_msg = array(
        '0' => '提交失败',
        '2' => '提交成功',
        '400' => '非法ip访问',
        '401' => '帐号不能为空',
        '402' => '密码不能为空',
        '403' => '手机号码不能为空',
        '4030' => '手机号码已被列入黑名单',
        '404' => '短信内容不能为空',
        '405' => '用户名或密码不正确',
        '4050' => '账号被冻结',
        '4051' => '剩余条数不足',
        '4052' => '访问ip与备案ip不符',
        '406' => '手机格式不正确',
        '407' => '短信内容含有敏感字符',
        '4070' => '签名格式不正确',
        '4071' => '没有提交备案模板',
        '4072' => '短信内容与模板不匹配',
        '4073' => '短信内容超出长度限制',
        '408' => '您的帐户疑被恶意利用，已被自动冻结，如有疑问请与客服联系。',
        '4085' => '验证码短信每天每个手机号码只能发5条',
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
        $action = $this->url . "" . $this->pginface[0];
        if ($mobile == '') {
            return array('status' => '0', 'msg' => '请输入手机号！');
        }

        $Conts = substr_count($mobile, ',') + 1;  //手机号码个数		

        if ($Conts > 1) {
            return array('status' => '0', 'msg' => '号码个数超过1！');
        }

        $lenTest = $this->strLength($con); //短信字符个数
        if ($lenTest > 300) {
            return array('status' => '0', 'msg' => '短信字符个数超过300！');
        }
        if (!$this->is_moblie($mobile)) {
            return array('status' => '0', 'msg' => '手机号码错误！');
        }
        
        $data = array(
            'account' => $this->account, //用户账号
            'password' => $this->password, //用户密码
            'mobile' => $mobile, //手机号码
            'content' => str_replace("\\\\", "\\", $con), //短信内容 
        );

        $re = $this->postSMS($action, $data); //POST方式提交

        import('libraries.Xml');
        $xml = xml::decode($re);
        
        $resultData = $xml['SubmitResult'];
        $code = $resultData['code'];
        //记录短信日志
        $param['mobile'] = $mobile;
        $param['msg'] = str_replace("\\\\", "\\", $con);
        $param['addtime'] = date('Y-m-d H:i:s',time());
        $param['status_code'] = $code;
        $param['smsid'] = $resultData['smsid'];
        $param['send_type'] = $Conts > 1 ? '2' : '1';
        $param['code'] = $this->error_msg[intval($code)];
        $sms_log_model->addSMSLog($param);

        if (isset($this->error_msg[intval($code)])) {
            if (intval($code) == 2) {
                return array('status' => '1', 'msg' => $this->error_msg[intval($code)]);
            } else {
                return array('status' => '0', 'msg' => $this->error_msg[intval($code)]);
            }
        } else {
            return array('status' => '0', 'msg' => $this->error_msg[intval($code)]);
        }
    }
    /**
     * 检测手机号码是否正确
     *
     */
    public function is_moblie($moblie)
    {
       return  preg_match("/^0?1((3|8)[0-9]|5[0-35-9]|4[57])\d{8}$/", $moblie);
    }
   
    /**
     * 查询余额
     * */
    public function MongateQueryBalance() {
        $action = $this->url . "" . $this->pginface[1];
        $data = array(
            'account' => $this->account, //用户账号
            'password' => $this->password, //用户密码
        );
        $re = $this->postSMS($action, $data); //POST方式提交
        
        import('libraries.Xml');
        $xml = xml::decode($re);
        $resultData = $xml['GetNumResult'];
        $code = $resultData['code'];
        
        if (isset($this->error_msg[$code])) {
            if ($code == '2') {
                return $resultData['num'];
            } else {
                return $this->error_msg[intval($code)];
            }
        } else {
            return '0';
        }
    }

    /**
     * 获取上行/状态报告
     * */
    public function MongateGetDeliver() {
        
    }

    /**
     * 短信POST发送
     * 
     * @param $url 短信发送地址
     * @param $data 短信发送内容
     * @return array 短信发送返回数据
     * */
    private function postSMS($url, $data = '') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    /**
     * 检验短信字数
     * 
     * */
    private function strLength($str, $charset = 'utf-8') {
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
