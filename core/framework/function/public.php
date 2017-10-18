<?php

/**
 * 统一调用函数
 * copyright 2015-06-02, jack
 * */
defined('InOmniWL') or exit('Access Invalid!');

/**
 * 取得通关状态文字输出形式
 *
 * @param array $order_info 订单数组
 * @return string $order_state 描述输出
 */
function orderCustomsState($order_info) {

    switch ($order_info['customs_state']) {
        case ORDER_CUSTOMS_STATE_0:
            $order_state = '已取消';
            break;
        case ORDER_CUSTOMS_STATE_10:
            $order_state = '待申报';
            break;
        case ORDER_CUSTOMS_STATE_20:
            $order_state = '申报中';
            break;
        case ORDER_CUSTOMS_STATE_30:
            $order_state = '已放行';
            break;
        case ORDER_CUSTOMS_STATE_40:
            $order_state = '未通过';
            break;
    }
    return $order_state;
}


/**
 * 取得订单发货模式文字输出描述
 *
 * @param array $send_type
 * @return string 描述输出
 */
function orderSendType($send_type) {
    return str_replace(
            array('1', '2', '3'), array('保税', '普通', '直邮'), $send_type);
}

function customs_state_list() {
    return array(
        '5' => '已取消',
        '10' => '待申报',
        '20' => '申报中',
        '30' => '已放行',
        '40' => '未通过',
    );
}

