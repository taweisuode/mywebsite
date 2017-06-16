<?php
    class IndexController extends Controller {
        public function indexAction() {
            $this->load('Common/Function');
            $str = "hello_aaa.html";
            daddslashes($str,1);
            
            $this->view->assign("result","hello world!");
            $this->view->show();
        }
        public function addAction() {
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
        public function vardumpAction() {
            $str = <<< EOF
array(12) {
  ["id"]=>
  int(107792)
  ["product_id"]=>
  int(75600457)
  ["subproduct_id"]=>
  int(104126576)
  ["depart_date"]=>
  int(1500048000)
  ["cost_total"]=>
  int(2963)
  ["cost_hotel"]=>
  int(840)
  ["cost_items"]=>
  string(10) "2017-07-19"
  ["cost_single_diff"]=>
  int(1050)
  ["flight_type"]=>
  int(4)
  ["price"]=>
  int(3299)
  ["channels"]=>
  array(1) {
    [0]=>
    array(4) {
      ["id"]=>
      int(1)
      ["plan"]=>
      int(5)
      ["display"]=>
      int(5)
      ["sold"]=>
      int(3)
    }
  }
  ["gross_rate"]=>
  int(10)
}
EOF;
            $this->view->show();
        }
        public function do_var_dumpAction() {
            $str = $_POST['str'];
            $print_flag = "";
            $str = trim($str);
            if(substr($str,0,5) == 'Array') {
                $print_flag = "print";
            }elseif (substr($str,0,5) == 'array') {
                $print_flag = "var_dump";
            }else {
                echo "格式错误";
            }
            if($print_flag == 'print') {
                $str = str_replace("[","",$str);
                $str = str_replace("]","",$str);
                $str = str_replace("=>",":",$str);
                $str = str_replace(" ","",$str);
                $str = str_replace("Array","",$str);
                $str = str_replace("\n\r","",$str);
                $str = str_replace("(","",$str);
                $str = str_replace(")","",$str);
            }else{
                $first_show = stripos($str,"{");
                $last_show = strripos($str,"}");
                $str = substr($str,$first_show+1,$last_show-$first_show-1);
                $str = str_replace("[","",$str);
                $str = str_replace("]","",$str);
                $str = str_replace("=>\r\n",":",$str);
                $str = str_replace("=>\n",":",$str);
                $str = str_replace(" ","",$str);
                $str = preg_replace("/string\([\d]+\)\"/","",$str);
                $str = str_replace("\"","",$str);
                $str = str_replace("int(","",$str);
                $str = str_replace("float(","",$str);
                $str = str_replace("bool(","",$str);
                $str = str_replace(")","",$str);
            }
            echo  trim($str);die;
        }
        public function showAction() {
            $this->view->show();
        }
    }
?>
