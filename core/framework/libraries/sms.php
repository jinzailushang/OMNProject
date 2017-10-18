<?php

/**
 * 短信类
 *
 */
defined('InOmniWL') or exit('Access Invalid!');

final class SMS {

    /**
     * 接口地址
     */
    private $url = 'http://smsapi.c123.cn/OpenPlatform/OpenApi';

    /**
     * 用户账号
     */
    private $ac = '1001@500973690001';

    /**
     * 认证密钥
     */
    private $authkey = 'AD4F8A744B8954B5E7126614F45EC4A6';

    /**
     * 通道组编号
     */
    private $cgid = '1899';

    /**
     * 签名编号 ,可以为空时，使用系统默认的编号
     */
    private $csid = '';

    /**
     * 发送时间,可以为空表示立即发送,yyyyMMddHHmmss 如:20130721182038
     */
    private $t = '';
    private $error_msg = array(
        '1' => '成功',
        '0' => '帐户格式不正确(正确的格式为:员工编号@企业编号)',
        '-1' => '服务器拒绝(速度过快、限时或绑定IP不对等)如遇速度过快可延时再发',
        '-2' => ' 密钥不正确',
        '-3' => '密钥已锁定',
        '-4' => '参数不正确(内容和号码不能为空，手机号码数过多，发送时间错误等)',
        '-5' => '无此帐户',
        '-6' => '帐户已锁定或已过期',
        '-7' => '帐户未开启接口发送',
        '-8' => '不可使用该通道组',
        '-9' => '帐户余额不足',
        '-10' => '内部错误',
        '-11' => '扣费失败',
    );

    public function __construct($cgid = '1899', $csid = '', $t = '') {
        $this->cgid = $cgid;
        $this->csid = $csid;
        $this->t = $t;
    }

    /**
     * 发送邮件
     *
     * @param string $email_to 发送对象邮箱地址
     * @param string $subject 邮件标题
     * @param string $message 邮件内容
     * @param string $from 页头来源内容
     * @return bool 布尔形式的返回结果
     */
    public function sendSMS($mobile, $con, $action = 'sendOnce') {
        $data = array(
            'action' => $action, //发送类型 ，可以有sendOnce短信发送，sendBatch一对一发，sendParam	动态参数短信接口
            'ac' => $this->ac, //用户账号
            'authkey' => $this->authkey, //认证密钥
            'cgid' => $this->cgid, //通道组编号
            'm' => $mobile, //号码,多个号码用逗号隔开
            'c' => $con, //iconv('gbk','utf-8',$con),		                 //如果页面是gbk编码，则转成utf-8编码，如果是页面是utf-8编码，则不需要转码
            'csid' => $this->csid, //签名编号 ，可以为空，为空时使用系统默认的签名编号
            't' => $this->t                                              //定时发送，为空时表示立即发送
        );
        $re = $this->postSMS($this->url, $data);                        //POST方式提交
        preg_match_all('/result="(.*?)"/', $re, $res);
        $status = trim($res[1][0]);
        $rt_data = '';
        if ($status == '1') {  //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
            preg_match_all('/\<Item\s+(.*?)\s+\/\>/', $re, $item);
            for ($i = 0; $i < count($item[1]); $i++) {
                preg_match_all('/cid="(.*?)"/', $item[1][$i], $cid);
                preg_match_all('/sid="(.*?)"/', $item[1][$i], $sid);
                preg_match_all('/msgid="(.*?)"/', $item[1][$i], $msgid);
                preg_match_all('/total="(.*?)"/', $item[1][$i], $total);
                preg_match_all('/price="(.*?)"/', $item[1][$i], $price);
                preg_match_all('/remain="(.*?)"/', $item[1][$i], $remain);

                $send['cid'] = $cid[1][0];             //企业编号
                $send['sid'] = $sid[1][0];             //员工编号
                $send['msgid'] = $msgid[1][0];         //发送编号
                $send['total'] = $total[1][0];         //计费条数
                $send['price'] = $price[1][0];         //短信单价
                $send['remain'] = $remain[1][0];       //余额
                $send_arr[] = $send;                   //数组send_arr 存储了发送返回后的相关信息
            }
            $rt_data = $send_arr;
            $msg = '发送成功';   //发送成功返回的值
        } else {  //发送失败的返回值
            $msg = $this->error_msg[$status];
        }
        return array('status' => $status, 'msg' => $msg, 'data' => $rt_data);
    }

    function postSMS($url, $data = '') {
        $row = parse_url($url);
        $host = $row['host'];
        $port = $row['port'] ? $row['port'] : 80;
        $file = $row['path'];
        while (list($k, $v) = each($data)) {
            $post .= rawurlencode($k) . "=" . rawurlencode($v) . "&"; //转URL标准码
        }
        $post = substr($post, 0, -1);
        $len = strlen($post);
        $fp = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.0\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n", $receive);
            unset($receive[0]);
            return implode("", $receive);
        }
    }

}
