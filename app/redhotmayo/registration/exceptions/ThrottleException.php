<?php  namespace redhotmayo\registration\exceptions; 

use Exception;

class ThrottleException extends Exception {
    const MESSAGE = 'Registration limited to invited guests. Please try back at a later date.';

    public function __construct() {
        parent::__construct(self::MESSAGE);
    }
}
