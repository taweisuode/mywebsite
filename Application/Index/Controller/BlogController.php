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
            $tag = $_GET['tag'];
            $indexModel = new IndexModel();
            $studyModel = new StudyModel();
            $list = $indexModel->getIndexList($tag);
            $tagList =$studyModel->getTagList();
            $this->view->assign("list",$list);
            $this->view->assign("tag",$tag);
            $this->view->assign("tagList",$tagList);
            $this->view->show();
        }

    }
?>
