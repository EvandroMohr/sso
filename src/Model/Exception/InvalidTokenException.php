<?php namespace Auth\Model\Exception;

use Exception;

/**
 * Exception when token is not valid anymore or in wrong format
 * @since 0.1.0
 * @author Evandro Mohr
 *
 */
class InvalidTokenException extends Exception
{
    
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
