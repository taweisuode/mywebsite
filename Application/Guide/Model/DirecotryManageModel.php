<?php
error_reporting(E_ALL ^ E_NOTICE);
class DirecotryManageModel extends Model
{
    protected $_tablename = 'directory_manage';
    public function test() {
        $select = $this->db->select("*")->from($this->_tablename);
        $result = $select->fetchAll();
        return $result;
    }
}
?>
