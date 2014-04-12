<?php namespace redhotmayo\registration\exceptions;

use Illuminate\Support\MessageBag;
use redhotmayo\exception\Exception;

class UserExistsException extends Exception {
    const MESSAGE = 'User already exists.';

    public function __construct() {
        $messageBag = new MessageBag(['registration' => self::MESSAGE]);
        parent::__construct($messageBag, self::MESSAGE);
    }
} 
