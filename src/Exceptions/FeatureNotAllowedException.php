<?php

namespace Shahnewaz\Permissible\Exceptions;

use Exception;

class FeatureNotAllowedException extends Exception
{
    public function construct($message = null, $code = 401)
    {
        parent::__construct();
        $message = $message ?: 'You are not authorized to access this feature.';
        throw new Exception($message, $code);
    }
}