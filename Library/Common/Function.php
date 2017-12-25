<?php
    /**
     * @desc 截取2个字符之间的字符串
     * @param $str   (待切割的字符串)
     * @param $mark1 (分隔符1)
     * @param $mark2 (分隔符2)
     * @return bool|string
     */
    function _getNeedBetween($str,$mark1,$mark2) {
        $start =stripos($str,$mark1);
        $end =stripos($str,$mark2);
        if(($start ===false||$end ===false)||$start >= $end)
            return false;
        $res=substr($str,($start+1),($end-$start-1));
        return $res;
    }
    /**
     * @desc 对输入的数组或者字符串进行过滤处理
     * @param $string
     * @param $force  (是否强制过滤)
     * @return string|array
     */
    function daddslashes($string, $force = 0) { 
        if(!$GLOBALS['magic_quotes_gpc'] || $force) { 
            if(is_array($string)) { 
                foreach($string as $key => $val) { 
                    $string[$key] = daddslashes($val, $force); 
                    }   
                } else { 
                    $string = addslashes($string); 
                }   
            }   
        return $string; 
    }
/**
 * 发送HTTP请求方法，目前只支持CURL发送请求
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
 function http($url, $params, $method = 'GET', $header = array(), $multi = false){
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => $header
    );

    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
        case 'GET':
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            $params = $multi ? $params : http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            die('不支持的请求方式！');
    }

    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    // dump($data);
    // exit();
    if($error)  die('请求发生错误：' . $error);
    return  $data;
}
