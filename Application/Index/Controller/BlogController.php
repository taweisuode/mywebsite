<?php

/**
 * @desc 博客总控制器
 */
    class BlogController extends Controller {
        private  $redis = "";
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            //$this->redis = $this->connectDb("redis");
        }
        /**
         * @desc 博客详情页
         */
        public function detailAction() {
            $detail_id = $_GET['id'];
            $studyModel = new StudyModel();
            $detail = $studyModel->get_detail($detail_id);
            if(empty($detail)) {
                $this->view->show('notFound');die;
            }
            $this->load('Common/Function');
            $this->view->assign("detail",$detail);
            $this->view->assign("title","{$detail['article_title']}--庄景鹏个人博客");
            $this->view->assign("keyword","{$detail['keyword']},庄景鹏个人博客,php博客，高质量的个人博客网站");
            $this->view->assign("description","{$detail['description']}");

            $this->view->show();
        }
        public function listAction() {
            //正则替换url中的特殊字符
            $data = $_GET;
            $data['tag'] = $this->replaceAll($data['tag']);
            $validate_params = array(
                'tag#string',
                'offset#int'=> 0,
            );
            $validate_data = $this->validate->check($validate_params, $data);
            $indexModel = new IndexModel();
            $studyModel = new StudyModel();
            $list = $indexModel->getBlogList($validate_data);
            if(empty($list['data'])) {
                $this->view->show("NotFound");die;
            }

            $validate_data['total'] = $list['total'];
            $validate_data['offset'] =$list['offset'];
            $validate_data['page_num'] =$list['page_num'];
            $validate_data['count'] =$list['count'];

            $tagList =$studyModel->getTagList();
            $this->view->assign("list",$list['data']);
            $this->view->assign("tag",$validate_data['tag']);
            $this->view->assign("tagList",$tagList);
            $this->view->assign('params',$validate_data);
            $this->view->assign("title","文章列表--庄景鹏个人博客");
            $this->view->assign("keyword","庄景鹏个人博客,php博客，高质量的个人博客网站");
            $this->view->assign("description","庄景鹏个人博客,我会原创以及转载更多高质量的文章分享给大家，大家可以扫描右方二维码进行交流");
            $this->view->show();
        }
        private function replaceAll($str = "") {
            $str = urldecode($str);
            $str = str_replace("%2B","+",$str);
            $str = str_replace("%2F","/",$str);
            $str = str_replace("%22","\"",$str);
            $str = str_replace("%27","\'",$str);
            return $str;
        }

    }
?>
