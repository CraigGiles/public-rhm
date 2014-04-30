<?php  namespace redhotmayo\registration\exceptions; 

use Illuminate\Support\MessageBag;
use redhotmayo\exception\Exception;

class ThrottleException extends Exception {
    const MESSAGE = 'Registration limited to invited guests. Please try back at a later date.';

    private $token;

    public function getToken() {
        return $this->token;
    }

    public function __construct($token) {
        $this->token = $token;
        $messageBag = new MessageBag(['registration' => self::MESSAGE]);
        parent::__construct($messageBag, self::MESSAGE);
    }
}
