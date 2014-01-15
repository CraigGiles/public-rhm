<?php namespace redhotmayo\rest\exceptions;

use Exception;

class InvalidSearchException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
} 