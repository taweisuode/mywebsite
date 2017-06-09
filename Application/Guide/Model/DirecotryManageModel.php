<?php
error_reporting(E_ALL ^ E_NOTICE);
class DirecotryManageModel extends Model
{
    protected $_tablename = 'directory_manage';
    public function directoryData() {
        $select = $this->db->select("*")->from($this->_tablename);
        $result = $select->fetchAll();
        $main_node_arr = array();
        $children_node = array();
        if($result) {
            foreach($result as $key => $val) {
                if($val['level'] == 1) {
                    $main_node_arr[]  = $val;
                }
                $children_node[$val['parent_node']][] = $val;
            }
            foreach($main_node_arr as $key => $val) {
                $main_node_arr[$key]['children_node'] = $children_node[$val['id']];
            }
        }
        return $main_node_arr;
    }
    public function generalDir($dirname= "") {
        $where = array(
            'controller' => $dirname
        );
        $select = $this->db->select("*")->from($this->_tablename);
        $select->where($where);
        $result = $select->fetchAll();
        return $result;
    }
}
?>
