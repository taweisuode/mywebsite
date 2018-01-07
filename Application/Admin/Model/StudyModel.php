<?php
error_reporting(E_ALL ^ E_NOTICE);
class StudyModel extends Model
{
    protected $_tablename = 'article';
    public function getList() {
        $this->db->select("*");
        $this->db->from($this->_tablename);
        $select  = $this->db->orderby("id","desc");
        $result = $select->fetchAll();
        return $result;

    }
    //获取文章标签列表
    public function getTagList() {
        $select = $this->db->select("*")->from("tags")->where(array("status"=>1));
        $result = $select->fetchAll();
        return $result;
    }
    public function addContent($data) {
        if(!empty($data)) {
            return $this->db->insert($this->_tablename,$data);
        }
        return false;
    }
    public function updateContent($data) {
        if(!empty($data)) {
            $this->db->where(array("id"=>$data['id']));
            unset($data['id']);
            return $this->db->update($this->_tablename,$data);
        }
        return false;
    }
    public function delete($id) {
        if(!empty($id)) {
            $this->db->where(array("id"=>$id));
            return $this->db->delete($this->_tablename);
        }
        return 0;
    }
    public function get_detail($id) {
        $this->db->select("*");
        $this->db->from($this->_tablename);
        $select = $this->db->where(array("id"=>$id));
        $result = $select->fetchRow();
        return $result;
    }
}
?>
