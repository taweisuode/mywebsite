<?php

/**
 * @desc ppf框架用户手册
 */
    class DatabaseController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $main_node_arr = $directoryModel->directoryData();
            $this->view->assign('main_node_arr',$main_node_arr);
        }
        public function indexAction() {
            $directoryModel = new DirecotryManageModel();
            $generalDir = $directoryModel->generalDir('Database');
            $this->view->assign('generalDir',$generalDir);
            $this->view->show();
        }
        public function configAction() {
            $this->view->show();
        }
        public function curdAction() {
            $this->view->show();
        }
        public function transactionAction() {
            $this->view->show();
        }
    }
?>
