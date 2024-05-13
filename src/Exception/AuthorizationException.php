<?php

namespace goodben\banking\Core\Exception;

use goodben\banking\Core\AuthorizationInterface;

/**
 * Class AuthorizationException.
 * 
 * @author Benjamin KALOMBO MUKENA <bmukena85@gmail.com>
 */
class AuthorizationException extends \Exception 
{
    public function __construct(private AuthorizationInterface $auth, string $message, \Throwable $previuous = null)
    {
        parent::__construct($message, 0, $previuous);
    }

    public function getAuthorization(): AuthorizationInterface {
        return $this->auth;
    }
}
