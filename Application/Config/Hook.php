<?php
/*
*  数据库配置文件 php7后 define 可以定义数组变量
*/

$hook['pre_controller'] = array(
    'class'    => 'hookClass',
    'function' => 'hello',
    'filename' => 'hookClass.php',
    'filepath' => 'hooks',
    'params'   => array("spring")
);
?>
