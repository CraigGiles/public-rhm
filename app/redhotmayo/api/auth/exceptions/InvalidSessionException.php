<?php namespace redhotmayo\api\auth\exceptions;

use Exception;

/**
 * Thrown when a session has been invalidated or is not found.
 *
 * Class InvalidSessionException
 *
 * @package redhotmayo\api\auth\exceptions
 * @author Craig Giles < craig@gilesc.com >
 */
class InvalidSessionException extends Exception {
    const MESSAGE = "Session not found or invalid.";

    public function __construct() {
        parent::__construct(self::MESSAGE);
    }
} 
