<?php

/**
 * @desc ppf框架用户手册
 */
    class OverviewController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $directory_list = $directoryModel->test();
            $this->view->assign('directory_list',$directory_list);
        }
        public function startAction() {
            $this->view->show();
        }
        public function indexAction() {
            $this->view->show();
        }
        public function folderAction() {
            $this->view->assign('test',"111");
            $this->view->show();
        }
        public function appflowAction() {
            $this->view->show();
        }
    }

?>
