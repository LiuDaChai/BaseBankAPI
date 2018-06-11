<?php

/**
 * 失败时，返回消息
 * @param  string $message 消息通知
 * @param  array  $data    数据内容
 * @return [type]          返回结果
 */
function failed($message='')
{
    return reback_msg('failed', $message, null);
}

/**
 * 成功时，返回消息
 * @param  string $message 消息通知
 * @param  array  $data    数据内容
 * @return [type]          返回结果
 */
function success($message='', $data=null)
{
    return reback_msg('success', $message, $data);
}

/**
 * 返回消息模版
 * @param  string $status    success or failed
 * @param  string $message   消息通知
 * @param  array  $data      数据内容
 * @return array             返回结果
 */
function reback_msg($status, $message, $data)
{
    // 返回消息数组
    $back_data = array();
    $back_data['status'] = $status;

    // 消息不为空时，传递
    if (!empty($message)) {
        $back_data['message'] = $message;
    }

    // 数据不为空时，传递
    if (!empty($data)) {
        $back_data['data'] = $data;
    }

    return json_encode($back_data);
}