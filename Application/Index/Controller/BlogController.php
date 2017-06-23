<?php

/**
 * @desc 博客总控制器
 */
    class BlogController extends Controller {
        /**
         * @desc 博客详情页
         */
        Public function detailAction() {
            $detail_id = $_GET['id'];
            $studyModel = new StudyModel();
            $detail = $studyModel->get_detail($detail_id);
            $this->load('Common/Function');
            $this->view->assign("detail",$detail);
            $this->view->show();
        }
        public function listAction() {
            $indexModel = new IndexModel();
            $list = $indexModel->getIndexList();
            $this->view->assign("list",$list);
            $this->view->show();
        }

    }
?>
