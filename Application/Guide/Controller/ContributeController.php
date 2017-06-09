<?php

/**
 * @desc ppf框架用户手册
 */
    class ContributeController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $main_node_arr = $directoryModel->directoryData();
            $this->view->assign('main_node_arr',$main_node_arr);
        }
        public function indexAction() {
            $this->view->show();
        }
        public function notebookAction() {
            $this->view->show();
        }
    }
?>
