<?php
    class StudyController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $this->studyModel = new StudyModel();
        }
        public function indexAction() {
            $this->view->show();
        }
        public function listAction() {
            $list = $this->studyModel->getList();
            $this->view->assign("list",$list);
            $this->view->show();
        }
        public function addAction() {
            $tagList =$this->studyModel->getTagList();
            if($_POST) {
                //加载上传图片公共类
                $this->load("Common/upload");
                $upload = new FileUpload();
                if(!$upload->upload('author_img')) {
                    echo "<script>alert('上传作者头像失败！');history.go(-1);";die;
                }
                $author_img = $upload->getFileName();

                if(!$upload->upload('img_url')) {
                    echo "<script>alert('上传文章缩略图失败！');history.go(-1);";die;
                }
                $img_url = $upload->getFileName();
                $data["article_title"]      = $_POST["title"];
                $data["keyword"]            = $_POST["keyword"];
                $data["description"]        = $_POST["description"];
                $data['type']               = $_POST["article_type"];
                $data["article_link"]       = $_POST['article_link'];
                $data["article_author"]     = !empty($_POST['author']) ? $_POST['author'] : "庄景鹏";
                $data["author_img"]         = $author_img;
                $data["img_url"]            = $img_url;
                $data["content_description"]= $_POST["content_description"];
                $data["content"]            = $_POST["content"];
                $data["tag"]                = $_POST["tag"];
                //$data['score'] = $_POST['score'];
                $data["add_time"]           = date("F d,Y",time());

                $arr = $this->studyModel->addContent($data);
                if($arr) {
                    echo "<script>alert('发表成功！');window.location.href='/Admin/Study/list'</script>";
                }else {
                    echo "<script>alert('发表失败！');history.go(-1);";
                }
            }
            $this->view->assign("tag",$tagList);
            $this->view->show();
        }
        public function editAction() {
            $tagList =$this->studyModel->getTagList();
            $id = $_REQUEST['id'];
            $detail = $this->studyModel->get_detail($id);
            if($_POST) {
                //加载上传图片公共类
                $upload_author_img = $_FILES['author_img'];
                $upload_img_url = $_FILES['img_url'];
                $author_img = $detail['author_img'];
                $img_url = $detail['img_url'];
                if($upload_author_img['name'] || $upload_img_url['name']) {
                    $this->load("Common/upload");
                    $upload = new FileUpload();
                    if(!$upload->upload('author_img')) {
                        echo "<script>alert('上传作者头像失败！');history.go(-1);";die;
                    }
                    $author_img = $upload->getFileName();

                    if(!$upload->upload('img_url')) {
                        echo "<script>alert('上传文章缩略图失败！');history.go(-1);";die;
                    }
                    $img_url = $upload->getFileName();
                }
                $data['id']                 = $_POST['id'];
                $data["article_title"]      = $_POST["title"];
                $data["keyword"]            = $_POST["keyword"];
                $data["description"]        = $_POST["description"];
                $data['type']               = $_POST["article_type"];
                $data["article_link"]       = $_POST['article_link'];
                $data["article_author"]     = !empty($_POST['author']) ? $_POST['author'] : "庄景鹏";
                $data["author_img"]         = $author_img;
                $data["img_url"]            = $img_url;
                $data["content_description"]= $_POST["content_description"];
                $data["content"]            = $_POST["content"];
                $data["tag"]                = $_POST["tag"];
                $data["update_time"]        = date("F d,Y",time());
                $arr = $this->studyModel->updateContent($data);
                if($arr) {
                    echo "<script>alert('发表成功！');window.location.href='/Admin/Study/list'</script>";
                }else {
                    echo "<script>alert('发表失败！');window.location.href='/Admin/Study/list'</script>";
                }
            }
            $this->view->assign("tag",$tagList);
            $this->view->assign('detail',$detail);
            $this->view->show();
        }
        public function deleteAction() {
            $id = $_GET['id'];
            $result = $this->studyModel->delete($id);
            if($result > 0) {
                echo "<script>alert('删除成功！');window.location.href='/Admin/Study/list'</script>";
            }else {
                echo "<script>alert('删除失败！');history.go(-1);";
            }
        }
    }
?>
