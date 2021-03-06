<?php
error_reporting(E_ALL ^ E_NOTICE);
class StudyModel extends Model
{
    protected $_tablename = 'article';
    public function getList() {
        $this->db->select("*");
        $this->db->from($this->_tablename);
        $select  = $this->db->orderby("add_time","desc");
        $result = $select->fetchAll();
        return $result;

    }
    public function addContent($data) {
        if(!empty($data)) {
            return $this->db->insert($this->_tablename,$data);
        }
        return false;
    }
    public function get_detail($id) {
        $this->db->select("*");
        $this->db->from($this->_tablename);
        $select = $this->db->where(array("id"=>$id));
        $result = $select->fetchRow();
        return $result;
    }
    //获取文章标签列表
    public function getTagList() {
        $select = $this->db->select("*")->from("tags")->where(array("status"=>1));
        $result = $select->fetchAll();
        return $result;
    }
}
?>
