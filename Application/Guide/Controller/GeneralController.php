<?php

/**
 * @desc ppf框架用户手册
 */
    class GeneralController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $directory_list = $directoryModel->test();
            $main_node_arr = array();
            $children_node = array();
            foreach($directory_list as $key => $val) {
                if($val['level'] == 1) {
                    $main_node_arr[]  = $val;
                }
                $children_node[$val['parent_node']][] = $val;
            }
            foreach($main_node_arr as $key => $val) {
                $main_node_arr[$key]['children_node'] = $children_node[$val['id']];
            }
            $this->view->assign('main_node_arr',$main_node_arr);
        }
        public function welcomeAction() {
            $this->view->show();
        }
    }
?>
