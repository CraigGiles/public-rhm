<?php namespace redhotmayo\api\auth\exceptions;

use Exception;

class InvalidSessionException extends Exception {
    const MESSAGE = "Session not found or invalid.";

    public function __construct() {
        parent::__construct(self::MESSAGE);
    }
} 