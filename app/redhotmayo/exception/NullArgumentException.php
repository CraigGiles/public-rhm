<?php namespace redhotmayo\exception;


use Illuminate\Support\MessageBag;

class NullArgumentException extends Exception {
    const ERROR_MESSAGE = 'Null argument detected';

    public function __construct() {
        $msg = new MessageBag([self::ERROR_MESSAGE]);
        parent::__construct($msg);
    }
}
