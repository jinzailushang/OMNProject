<?php

/**
 * @version 纵腾转运接口类
 * @copyright (c) 2015-09-07, coolzbw
 */
defined('IN_TRANSPORT_QU') or exit('Access Invalid!');

class zongteng_model implements transport {

    /**
     * @version 类所在目录
     * @copyright (c) 2015-09-07, coolzbw
     */
    private $transport_directory = 'zongteng';
    private $transport_id = '1';
    private $template_dir = '';
    private $transport_config = NULL;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $qu_config = substr(__FILE__, 0, -20);
        include $qu_config . '/config.ini.php';
        $this->transport_config = $api_config;
        $this->template_dir = BASE_DATA_PATH . DS . 'api' . DS . 'transport' . DS . $this->transport_directory . DS;
    }

    /**
     * @version 取属性值魔术方法
     * @copyright (c) 2015-09-07, coolzbw
     * @param string $name 属性
     * @return string | int | array    属性值
     */
    public function __get($name) {
        return $this->$name;
    }

    /**
     * @version array转换成xml
     * @copyright (c) 2015-09-07, coolzbw
     * @param type $type  类型
     * @param type $body  数据
     * @return string
     */
    private function buildData($type, $body) {
        $body = json_encode($body);
        $Sign = strtolower($body . $this->transport_config['apKey']);
        $Sign = md5($Sign);

        $data = array(
            'Sign' => $Sign,
            'RequestId' => null,
//            'RequestTime' => date('Y-m-d H:i:s', time()),
            'Version' => null,
            'Data' => $body,
        );
//        dump($data);die();
//        dump(json_encode($data));die();
        return json_encode($data);
    }

    /**
     * @version 税费缴纳
     * @copyright (c) 2015-09-07, coolzbw
     * @param array $data_info 数组
     * @return array 同步结果array
     */
    public function onlineTax($data_info = array()) {

        //返回测试数据
        if (empty($this->transport_config['api_status'])) {
            $data = array(
                'tax_amount' => 12,
            );
            return array('status' => 1, 'msg' => '测试', 'data' => $data);
        }

        if (!is_array($data_info) || empty($data_info)) {
            return FALSE;
        }

        $message = '转运单:' . $data_info["customer_code"] . ' 税费缴纳';

        $data = array(
            'CustomerIdentity' => $this->transport_config['CustomerIdentity'],
            'TrackingNumber' => $data_info['tracking_number'],
        );
//        dump($data);die();
        $handle = 'GeneralService/onlineTax';

        try {

            $ret_data = $this->buildData($handle, $data);

            $soapData = $this->postData($ret_data, $handle);

            $response = $soapData;

            if ($response['ResponseResult'] == '1') {
                $message .= '成功,' . $response['message'] . '！';
                $this->saveLog($message, 'success', __FUNCTION__, $ret_data, $soapData);
                $data = array(
                    'tax_amount' => $response['Data']['TaxAmount'],
                );
                return array('status' => 1, 'msg' => $message, 'data' => $data);
            } else {
                $message .= '失败,' . $this->getMessage($response['ResponseError']) . '！';
                $this->saveLog($message, 'error', __FUNCTION__, $ret_data, $soapData);
                return array('status' => 0, 'msg' => $message);
            }
        } catch (Exception $e) {
            $message .= '失败，接口出错' . $e->getMessage() . '！';
            $this->saveLog($message, 'error', __FUNCTION__, $ret_data, $e->getMessage());
            return array('status' => 0, 'msg' => $message);
        }
    }

    /**
     * @version 物流追踪
     * @copyright (c) 2015-09-07, coolzbw
     * @param array $data_info 数组
     * @return array 同步结果array
     */
    public function queryTraceStatusFlow($data_info = array()) {

        //返回测试数据
        if (empty($this->transport_config['api_status'])) {
            $data = array(
            );
            return array('status' => 1, 'msg' => '测试', 'data' => $data);
        }

        if (!is_array($data_info) || empty($data_info)) {
            return FALSE;
        }

        $message = '转运单:' . $data_info["customer_code"] . ' 物流追踪';

        $data = array(
            'TrackingNumber' => $data_info['tracking_number'],
        );
//        dump($data);die();
        $handle = 'GeneralService/queryTraceStatusFlow';

        try {

            $ret_data = $this->buildData($handle, $data);

            $soapData = $this->postData($ret_data, $handle);

            $response = $soapData;

            if ($response['ResponseResult'] == '1') {
                $message .= '成功,' . $response['message'] . '！';
                $this->saveLog($message, 'success', __FUNCTION__, $ret_data, $soapData);
                $data = $response['Data'];
                return array('status' => 1, 'msg' => $message, 'data' => $data);
            } else {
                $message .= '失败,' . $this->getMessage($response['ResponseError']) . '！';
                $this->saveLog($message, 'error', __FUNCTION__, $ret_data, $soapData);
                return array('status' => 0, 'msg' => $message);
            }
        } catch (Exception $e) {
            $message .= '失败，接口出错' . $e->getMessage() . '！';
            $this->saveLog($message, 'error', __FUNCTION__, $ret_data, $e->getMessage());
            return array('status' => 0, 'msg' => $message);
        }
    }

    /**
     * @version 发送同步
     * @copyright (c) 2015-09-07, coolzbw
     * @param string $ret_data 处理的xml
     * @param string $method 方法
     * @return array 返回数组
     */
    public function postData($ret_data, $method) {

        ini_set('default_socket_timeout', 10);

        if (empty($ret_data) || empty($this->transport_config['location'])) {
            return FALSE;
        }

//        dump($data);die();
        $ret_data = $this->post($this->transport_config['location'] . $method, $ret_data);
//        ob_end_clean();
        $response = json_decode($ret_data, true);
//        print_r($ret_data);
//        die();
        return $response;
    }

    /**
     * @version post提交
     * @copyright (c) 2015-09-07, coolzbw
     */
    private function post($url, $post_data = '', $timeout = 5) {
//        print_r($url);
//        print_r($post_data);
        $headers = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post_data),
            'Authorization: ' . $this->_encryption(),
        );
//        dump($headers);die();
        if (is_callable("curl_init")) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HEADER => 0, //表示不需要response header
                CURLOPT_NOBODY => 0, //表示需要response body
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_HTTPHEADER => $headers
            ));

            $resp_data = curl_exec($curl);
            curl_close($curl);
//            dump($resp_data);
            return ($resp_data == FALSE) ? FALSE : $resp_data;
        } else {
            return FALSE;
        }
    }

    /**
     * 加密
     * @copyright (c) 2015-09-07, coolzbw
     * @return string
     */
    private function _encryption() {
        import('libraries.Des');
        $des = new Des();
        $des->setdes($this->transport_config['key'], 0);
        $str = $this->transport_config['appToken'] . '&' . $this->transport_config['apKey'];
//        dump($str);
        return $des->encrypt($str);
    }

    /**
     * @version 保存面单
     * @copyright (c) 2015-09-07, coolzbw
     */
    public function saveLabelPic($byte, $id) {
        $files = BASE_DATA_PATH . DS . 'upload' . DS . 'label' . DS . $id . '.png';
        $fp = fopen($files, 'w');
        fwrite($fp, base64_decode($byte));
        fclose($fp);
    }

    /**
     * @version 返回错误信息
     * @copyright (c) 2015-09-07, coolzbw
     */
    public function getMessage($data) {
        return $data['LongMessage'];
    }

    /**
     * @version 操作日志
     * @copyright (c) 2015-09-07, coolzbw
     * @param string $message 日志内容
     * @param string $state 成功失败
     * @param string $action 操作方法
     * @param string $call_text 调用内容
     * @param string $back_text 反馈内容
     * @return void
     */
    public function saveLog($message, $state, $action = '', $call_text = '', $back_text = '') {

        $files = $this->template_dir . 'log/log-';
        $files .= $state == 'error' ? 'error' : 'success';
        $files .= '-' . date('Y-m-d', time()) . '.log';

        file_put_contents($files, date('Y-m-d H:i:s') . '[' . $message . ']' . PHP_EOL . PHP_EOL, FILE_APPEND);

        $ret = Model('transport_api_log')->addTransportApiLog(array(
            'transport_id' => $this->transport_id,
            'action' => $action,
            'message' => $state,
            'remarks' => $message,
            'call_content' => is_array($call_text) ? var_export($call_text, TRUE) : $call_text,
            'back_content' => is_array($back_text) ? var_export($back_text, TRUE) : $back_text,
            'log_time' => date('Y-m-d H:i:s', time()),
        ));
        return $ret['log_id'];
    }

}
