<?php
    class StudyController extends Controller {
        public function indexAction() {
            $this->view->show();
        }
        public function listAction() {
            $studyModel = new StudyModel();
            $list = $studyModel->getList();
            $this->view->assign("list",$list);
            $this->view->show();
        }
        public function addAction() {
            if($_POST) {
                $studyModel = new StudyModel();
                $data["article_title"]      = $_POST["title"];
                $data['type']               = $_POST["article_type"];
                $data["article_link"]       = $_POST['article_link'];
                $data["article_author"]     = !empty($_POST['author']) ? $_POST['author'] : "庄景鹏";
                $data["img_url"]            = $_POST["img_url"];
                $data["content_description"]= $_POST["content_description"];
                $data["content"]            = $_POST["content"];
                //$data['score'] = $_POST['score'];
                $data["add_time"]           = date("F d,Y",time());
                $arr = $studyModel->addContent($data);
                if($arr)
                {
                    echo "<script>alert('发表成功！');window.location.href='/Admin/Study/list'</script>";
                }else
                {
                    echo "<script>alert('发表失败！');history.go(-1);";
                }
            }
            $this->view->show();
        }
        public function editAction() {
            $id = $_GET['id'];
            $studyModel = new StudyModel();
            $detail = $studyModel->get_detail($id);
            if($_POST) {
                $data['id']                 = $_POST['id'];
                $data["article_title"]      = $_POST["title"];
                $data['type']               = $_POST["content_type"];
                $data["article_link"]       = $_POST['article_link'];
                $data["article_author"]     = !empty($_POST['author']) ? $_POST['author'] : "庄景鹏";
                $data["img_url"]            = $_POST["img_url"];
                $data["content_description"]= $_POST["content_description"];
                $data["content"]            = $_POST["content"];
                $data["update_time"]        = date("F d,Y",time());
                $arr = $studyModel->updateContent($data);
                if($arr)
                {
                    echo "<script>alert('发表成功！');window.location.href='/Admin/Study/list'</script>";
                }else
                {
                    echo "<script>alert('发表失败！');window.location.href='/Admin/Study/list'</script>";
                }
            }
            $this->view->assign('detail',$detail);
            $this->view->show();
        }
        public function deleteAction() {
            $id = $_GET['id'];
            $studyModel = new StudyModel();
            $result = $studyModel->delete($id);
            if($result > 0)
            {
                echo "<script>alert('删除成功！');window.location.href='/Admin/Study/list'</script>";
            }else
            {
                echo "<script>alert('删除失败！');history.go(-1);";
            }
        }
    }
?>
