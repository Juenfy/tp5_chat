<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 返回JSON格式数据
 * @param type $status
 * @param type $return
 */
if(!function_exists('show_json')){
    function show_json($status = 1, $return = null, $flag = true)
    {
        $ret = array(
            'status' => $status,
            'result' => array()
        );

        if (!is_array($return)) {
            if ($return) {
                $ret['result']['message'] = $return;
            }
            die(json_encode($ret));
        } else {
            $ret['result'] = $return;
        }
        die(json_encode($ret));
    }
}
/**
 * 返回layui表单json数据格式
 */
if(!function_exists('layui_json')) {
    function layui_json($code = 0, $data = null, $count = 0, $msg = '')
    {
        $ret = array(
            'code' => $code,
            'count' => $count,
            'data' => $data,
            'msg' => $msg
        );
        die(json_encode($ret));
    }
}