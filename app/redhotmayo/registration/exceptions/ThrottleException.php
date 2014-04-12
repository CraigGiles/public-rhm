<?php  namespace redhotmayo\registration\exceptions; 

use Illuminate\Support\MessageBag;
use redhotmayo\exception\Exception;

class ThrottleException extends Exception {
    const MESSAGE = 'Registration limited to invited guests. Please try back at a later date.';

    public function __construct() {
        $messageBag = new MessageBag(['registration' => self::MESSAGE]);
        parent::__construct($messageBag, self::MESSAGE);
    }
}
