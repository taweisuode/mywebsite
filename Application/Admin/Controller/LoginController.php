<?php
    class LoginController extends Controller {
        public function indexAction() {
            $this->view->show();
        }
        public function loginAction() {
            $adminModel = new adminModel();
            $arr['user_name'] = $_POST['user_name'];
            $arr['user_pass'] = md5($_POST['user_pass']);
            $result = $adminModel->getList($arr);
            if(!empty($result['user_id']) ) {
                session_start();
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['user_name'] = $result['user_name'];
                $_SESSION['cool_name'] = $result['cool_name'];
                echo "<script>alert('登陆成功！');window.location.href='/Admin/Index/index';</script>";
            }else {
                echo "<script>alert('用户名或密码错误！');window.history.go(-1);</script>";
            }
        }
        public function showAction() {
            $this->view->show();
        }
        public function logoutAction()
        {
            session_start();
            session_destroy();
            echo "<script>alert('登出成功！');window.location.href='/Admin/Login/index';</script>";
        }
    }
?>
