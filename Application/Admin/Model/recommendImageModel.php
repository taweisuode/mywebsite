<?php
error_reporting(E_ALL ^ E_NOTICE);
class RecommendImageModel extends Model
{
    protected $_tablename = 'img_recommend';

    public function __CONSTRUCT() {
        $config = $this->loadDataConfig('music_api');
        parent::__CONSTRUCT($config);
    }

    public function getList() {
        $this->db->select("*");
        $this->db->from($this->_tablename);
        $select  = $this->db->orderby("sort","desc")->limit(6);
        $result = $select->fetchAll();
        return $result;

    }
    //增
    public function addContent($data) {
        if(!empty($data)) {
            return $this->db->insert($this->_tablename,$data);
        }
        return false;
    }
    //改
    public function updateContent($data) {
        if(!empty($data)) {
            $this->db->where(array("id"=>$data['id']));
            unset($data['id']);
            return $this->db->update($this->_tablename,$data);
        }
        return false;
    }
    //删
    public function delete($id) {
        if(!empty($id)) {
            $this->db->where(array("id"=>$id));
            return $this->db->delete($this->_tablename);
        }
        return 0;
    }
    //详细
    public function get_detail($id) {
        $this->db->select("*");
        $this->db->from($this->_tablename);
        $select = $this->db->where(array("id"=>$id));
        $result = $select->fetchRow();
        return $result;
    }
}
?>
