<?php
    class AppController extends Controller {
        public function __CONSTRUCT() {
            parent::__CONSTRUCT();
            $this->recommendImageModel = new recommendImageModel();
        }
        public function recommendImageListAction() {
            $list = $this->recommendImageModel->getList();
            $this->view->assign("list",$list);
            $this->view->show();
        }
        public function recommendImageAddAction() {
            if($_POST) {
                //加载上传图片公共类
                $this->load("Common/upload");
                $upload = new FileUpload();

                if(!$upload->upload('img_url')) {
                    echo "<script>alert('上传推荐图片失败！');history.go(-1);</script>";die;
                }
                $img_url = $upload->getFileName();
                $data["type"]      = $_POST["type"];
                $data["img_name"]  = $_POST["img_name"];
                $data["img_url"]   = $img_url;
                $data['img_link']  = $_POST["img_link"];
                $data["sort"]      = $_POST['sort'];
                $data["createtime"]= date("Y-m-d H:i:s");

                $arr = $this->recommendImageModel->addContent($data);
                if($arr) {
                    echo "<script>alert('发表成功！');window.location.href='/admin/app/recommendImageList'</script>";
                }else {
                    echo "<script>alert('发表失败！');history.go(-1);</script>";
                }
            }
            $this->view->show();
        }
        public function recommendImageUpdateAction() {
            $id = $_REQUEST['id'];
            $detail = $this->recommendImageModel->get_detail($id);
            if($_POST) {
                //加载上传图片公共类
                $upload_img_url = $_FILES['img_url'];
                $img_url = $detail['img_url'];
                if($upload_img_url['name']) {
                    $this->load("Common/upload");
                    $upload = new FileUpload();
                    if(!$upload->upload('img_url')) {
                        echo "<script>alert('上传文章缩略图失败！');history.go(-1);";die;
                    }
                    $img_url = $upload->getFileName();
                }
                $data['id']        = $_POST['id'];
                $data["type"]      = $_POST["type"];
                $data["img_name"]  = $_POST["img_name"];
                $data["img_url"]   = $img_url;
                $data['img_link']  = $_POST["img_link"];
                $data["sort"]      = $_POST['sort'];
                $data["createtime"]= date("Y-m-d H:i:s");
                $arr = $this->recommendImageModel->updateContent($data);
                if($arr) {
                    echo "<script>alert('修改成功！');window.location.href='/admin/app/recommendImageList'</script>";
                }else {
                    echo "<script>alert('修改失败！');window.location.href='/admin/app/recommendImageList'</script>";
                }
            }
            $this->view->assign('detail',$detail);
            $this->view->show();
        }
        public function recommendImageDeleteAction() {
            $id = $_GET['id'];
            $result = $this->recommendImageModel->delete($id);
            if($result > 0) {
                echo "<script>alert('删除成功！');window.location.href='/admin/app/recommendImageList'</script>";
            }else {
                echo "<script>alert('删除失败！');history.go(-1);";
            }
        }
    }
?>
