<?php

namespace App\Exceptions;

use Exception;

class ApolloCoreException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
