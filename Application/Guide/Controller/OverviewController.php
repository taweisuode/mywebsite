<?php

/**
 * @desc ppf框架用户手册
 */
    class OverviewController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $main_node_arr = $directoryModel->directoryData();

            $this->view->assign('main_node_arr',$main_node_arr);
        }
        public function startAction() {
            $this->view->show();
        }
        public function indexAction() {
            $directoryModel = new DirecotryManageModel();
            $generalDir = $directoryModel->generalDir("Overview");
            $this->view->assign('generalDir',$generalDir);
            $this->view->show();
        }
        public function meaningAction() {
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
