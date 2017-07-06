<?php

/**
 *  数据库表抽象类 Db_Table_Abstract
 *  主要是pdo_mysql的封装
 *  以及实现$select = $this->db->select()->from()->where()->orderby()->limit();这类的查询
 *
 */

class Redis_Abstract
{
    public $select;
    protected $table_name;
    private $strDsn;
    public $DbConnect;
    protected static $getInstance;
    private $get_query_sql = "";

    public $DbSqlArr = array();

    private function __CONSTRUCT() {
        include APPLICATION_PATH . "/Config/Config.php";
        include APPLICATION_PATH . "/Config/Redis.php";

    }

    public static function Db_init() {
        if (self::$getInstance === null) {
            self::$getInstance = new self();
        }
        return self::$getInstance;
    }
}

