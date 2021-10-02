<?php

namespace App\Exceptions\Previews;

use Exception;
use Throwable;

class RobotsTxtException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
