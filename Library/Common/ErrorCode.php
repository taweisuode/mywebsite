<?php
/**
 * @desc 错误码集合类
 *
 */
class ErrorCode {
    const test  		= 10001;

    //REDIS ERROR CODE
    const NO_CFGKEY		= 5000;
    const NO_CFGPARAMS	= 5001;
    const NO_CFG_HOST	= 5002;
    const NO_CFG_PORT	= 5003;

    const PARAM_REQUIRED	= 1000;
    const PARAM_EMPTY		= 1001;
    const PARAM_NOT_INT		= 1002;
    const PARAM_NOT_FLOAT	= 1003;
    const PARAM_NOT_NUMBER	= 1004;
    const PARAM_NOT_STRING	= 1005;
    const PARAM_NOT_ARRAY	= 1006;
    const PARAM_NOT_OBJECT	= 1007;
    const PARAM_NOT_BOOL	= 1008;
    const PARAM_OUT_OF_RANGE= 1009;
    const PARAM_NOT_SUPPORT	= 1010;
    const PARAM_INVALID_ENUM= 1011;
    const PARAM_INVALID_DATE= 1012;
    const PARAM_INVALID_TIME= 1013;
    const PARAM_INVALID_DATETIME	= 1014;
    const PARAM_NOT_MULTI_INT		= 1015;
    const PARAM_INVALID_IPV4		= 1016;
    const PARAM_INVALID_JSON		= 1017;
    const PARAM_INVALID_EMAIL		= 1018;
    const PARAM_NOT_INDEX_ARRAY		= 1019;
    const PARAM_MUST_APPLY_ANY		= 1020;
    const PARAM_INVALID_DATA		= 1021;
    const PARAM_NOT_WORD			= 1022;
    const PARAM_NOT_PRINT_CHARS		= 1023;
    const PARAM_NOT_ALPHANUMBER		= 1024;
    const PARAM_ERROR_TYPE			= 1025;
    const PARAM_ERROR_FORMAT		= 1026;
    const PARAM_NOT_ALPHA			= 1027;
    const PARAM_NOT_MULTI_WORD		= 2018;
    const PARAM_INVALID_MOBILE		= 2019;
    const PARAM_INVALID_MULTI_DATE	= 2020;
    const PARAM_NOT_MULTI_STR		= 2021;
    const PARAM_NOT_UPPER_CASE		= 2022;
    const PARAM_NOT_NO_CHINESE		= 2023;
    const PARAM_NOT_UPPER_CASE_SPACE= 2024;
    const PARAM_INVALID_MULTI_EMAIL = 2025;

    //提供获取错误的方法
    public function get($code) {
        include PPF_PATH."/Library/Common/ErrorCodeCN.php";
        $codeCN = $lang_cn[$code];
        return $codeCN;
    }
}
?>
