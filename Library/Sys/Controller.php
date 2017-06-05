<?php
/**
 *   控制器基类Controller
 *   所有的控制器都继承于他
 *   构造方法中需要实例化View类，并加载所有的该module下面的model.php文件
 */
class Controller
{
    private $loadException  = "FrameException";
    private $hasException   = false;
    protected $view;
    public function __CONSTRUCT()
    {
    
        //默认导入TestException异常类
        $this->load('Exception/FrameException');
        $this->load('Common/ErrorCode');
        //设置异常处理函数
        restore_exception_handler();
        set_exception_handler(array($this,"setExceptionHandler"));

        $this->view = new View();
        //自动加载所有的model文件
        //$autoload_model_path = APPLICATION_PATH.'/'.Dispath::$current_module.'/Model';
        //var_dump($autoload_model_path);die;
        //var_dump($autoload_model_path);die;
        //$allFile = scandir($autoload_model_path);
/*        array_splice($allFile,0,2);//去掉前面的 '.' 和 '..'
        //获取文件夹的所有文件
        foreach($allFile as $key => $val)
        {   
            if(pathinfo($val,PATHINFO_EXTENSION) == 'php')
            {   
                //加载Model下面的所有文件
               require_once($autoload_model_path.'/'.$val); 
            }   
        }*/
    }
    public function setExceptionHandler(Throwable $e = null) {
        $this->hasException = true;
        $this->ShowErrorHtml($e);

    }

    /**
     * @desc 显示错误信息
     */
    private function ShowErrorHtml(Throwable $e = null) {
        $html = <<< HTML
        <pre class="alert alert-danger" style="color:#a94442;background-color: #f2dede;border-color: #ebccd1;font-size: 15px">
        <h2 class="error-danger" style="text-align:center">出错了</h2>
        错误描述: {$e->getMessage()}<br>
        文件位置: {$e->getFile()}第{$e->getLine()}行<br>
        信息栈:<br><br><div style="padding-left: 72px">{$e->getTraceAsString()}</div>
HTML;
        echo $html;die;
    }
    public function showError($msg, $code = -1) {
        $code   = $msg->getCode();
        $desc   = $msg->getMessage();
        $return = array(
            'e' =>  array(
                'code'  => $code,
                'desc'  => $desc
            )
        );
        echo json_encode($return);die;
    
    }
    public function load($path) {
        if(is_array($path)) {
            foreach($path as $key => $val) {
                $this->load($val);
            }
        }else {
            require_once(PPF_PATH.'/Library/'.$path.".php");
        }
    }
}
?>
