<?php namespace redhotmayo\exception;

use Illuminate\Support\MessageBag;

class Exception extends \Exception {
    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * Constructor
     *
     * @param MessageBag $errors
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($errors, $message = '', $code = 0, Exception $previous = null) {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return MessageBag
     */
    public function getErrors() {
        return $this->errors;
    }
} 
