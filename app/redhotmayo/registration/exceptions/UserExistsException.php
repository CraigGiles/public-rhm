<?php namespace redhotmayo\registration\exceptions;

use Exception;

class UserExistsException extends Exception {
    const MESSAGE = 'User already exists.';

    public function __construct() {
        parent::__construct(self::MESSAGE);
    }
} 