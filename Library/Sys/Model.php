<?php
require_once(PPF_PATH."/Library/Database/Pdo_Abstract.php");
/**
 *  模型基类 Model
 *  主要是pdo_mysql的封装
 *
 */
class Model extends Pdo_Abstract
{
    protected $table_name;
    private $strDsn;
    public $db;
    public function __CONSTRUCT($conf = array()) {
        include APPLICATION_PATH."/Config/Config.php";
        $this->db = $this::Db_init($conf);
    }
    public function loadDataConfig($configName = "") {
        if(!empty($configName)) {
            include APPLICATION_PATH."/Config/Database.php";
            return $config['mysql'][$configName];
        }
    }
    public function exec($sql, $debug=false)
    {
        if($debug == true)
        {
            var_dump($sql);die;
        }else
        {
            $result = $this->db->exec($sql);
        }
        return $result;
    }
    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }
    public function rollback()
    {
        $this->db->rollback();
    }
    public function commit()
    {
        $this->db->commit();
    }
    public function destruct()
    {
        $this->db = null;
    }
}
?>
