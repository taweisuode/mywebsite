<?php

/**
 * @desc ppf框架用户手册
 */
    class OverviewController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $main_node_arr = $directoryModel->test();

            $this->view->assign('main_node_arr',$main_node_arr);
        }
        public function startAction() {
            $this->view->show();
        }
        public function indexAction() {
            $this->view->show();
        }
        public function folderAction() {

            $this->view->show();
        }
        public function appflowAction() {
            $this->view->show();
        }
    }

?>
