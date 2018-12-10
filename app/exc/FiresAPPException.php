<?php

namespace App\EXC;

class FiresAPPException extends \Exception
{

    public function __construct($message, $HttpCode = 500, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpcode = $HttpCode;
    }

    public function getHttpCode()
    {
        return $this->httpcode;
    }
}
