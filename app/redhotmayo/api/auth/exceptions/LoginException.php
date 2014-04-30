<?php  namespace redhotmayo\api\auth\exceptions;

use Illuminate\Support\MessageBag;
use redhotmayo\exception\Exception;
use Symfony\Component\HttpFoundation\Response;

class LoginException extends Exception {
    const MESSAGE = 'Invalid username or password.';
    public function __construct() {
        $messageBag = new MessageBag([self::MESSAGE]);
        return parent::__construct($messageBag, self::MESSAGE, Response::HTTP_UNAUTHORIZED);
    }
} 
