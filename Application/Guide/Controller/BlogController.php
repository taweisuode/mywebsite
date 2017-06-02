<?php

/**
 * @desc ppf框架用户手册
 */
    class BlogController extends Controller {
        /**
         * @desc 博客详情页
         */
        Public function detailAction() {
            $this->load('Common/Function');
            $this->view->assign("title","关于请求被挂起页面加载缓慢问题的追查");
            $this->view->show();
        }
        Public function addAction() {
            $indexModel = new IndexModel();
            $movie_list = $indexModel->test();

            $fruit = array("loving"=>'banana',"hating"=>'apple',"no_sense"=>'orange');
            $test = array(
                "aaa" => array(
                    "yes" => "no",
                    "sad" => 'happy'
                ),
                "bbb" => array(
                    "one" => "two",
                    "three"=> "four"
                )
            );
            $this->view->assign("movie_list",$movie_list);
            $this->view->assign("fruit",$fruit);
            $this->view->assign("test",$test);
            $this->view->assign("result","hello");
            $this->view->show();
        }

    }
?>
