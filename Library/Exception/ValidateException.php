<?php

class ValidateException extends Exception {

    private $extra_data	= null;

    public function __construct($message = '', $code = 0, $extra_data = null) {

        require_once(PPF_PATH."/Library/Common/ErrorCode.php");
        $errorCode = new ErrorCode();
        $message = sprintf($errorCode->get($code),$message);

        parent::__construct($message, $code);
        $this->extra_data	= $extra_data;
    }

    public function getExtraData() {
        return $this->extra_data;
    }
}
