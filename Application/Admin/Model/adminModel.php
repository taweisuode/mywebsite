<?php
error_reporting(E_ALL ^ E_NOTICE);
class adminModel extends Model
{
    protected $_tablename = 'adminlist';
    public function getList($data) {
        $select = $this->db->select("*")->from($this->_tablename)->where($data);
        $result = $select->fetchRow();
        return $result;
    }
}
?>
