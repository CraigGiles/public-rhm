<?php  namespace redhotmayo\billing\exception;

use Illuminate\Support\MessageBag;
use redhotmayo\exception\Exception;

class BillingException extends Exception {
    /**
     * Billing errors
     *
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
     * Get billing errors
     *
     * @return MessageBag
     */
    public function getErrors() {
        return $this->errors;
    }
}
