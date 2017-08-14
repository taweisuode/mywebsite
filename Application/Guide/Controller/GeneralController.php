<?php

/**
 * @desc ppf框架用户手册
 */
    class GeneralController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $directoryModel = new DirecotryManageModel();
            $main_node_arr = $directoryModel->directoryData();
            $this->view->assign('main_node_arr',$main_node_arr);
        }
        public function indexAction() {
            $directoryModel = new DirecotryManageModel();
            $generalDir = $directoryModel->generalDir("General");
            $this->view->assign('generalDir',$generalDir);
            $this->view->show();
        }
        public function routeAction() {
            $this->view->show();
        }
        public function controllerAction() {
            $this->view->show();
        }
        public function modelAction() {
            $this->view->show();
        }
        public function viewAction() {
            $this->view->show();
        }
        public function exceptionAction() {
            $this->view->show();
        }
        public function assignAction() {
            $this->view->show();
        }
        public function commonAction() {
            $this->view->show();
        }
        public function cacheAction() {
            $this->view->show();
        }
    }
?>
