<?php

use Illuminate\Http\Response as ResponseCodes;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;

trait RedHotMayoRedirectorTrait {
    private $statusCode = ResponseCodes::HTTP_OK;
    private $redirect;
    private $messages = [];
    private $headers = [];

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($status) {
        $this->statusCode = (int)$status;
    }

    public function setRedirect($redirect) {
        $this->redirect = $redirect;
    }

    public function getRedirect() {
        return $this->redirect;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function respondSuccess($redirect) {
        $this->setStatusCode(ResponseCodes::HTTP_OK);
        $this->setRedirect($redirect);

        return $this->respond();
    }

    public function respondWithUnknownError($message) {
        $this->setStatusCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        $this->setMessages($message);

        return $this->respond();
    }

    public function respondNotFound($message, $redirect = null) {
        $this->setStatusCode(ResponseCodes::HTTP_NOT_FOUND);
        $this->setMessages($message);
        $this->setRedirect($redirect);
        $this->setHeaders([]);

        return $this->respond();
    }

    public function respond() {
        // convert message to an array if it isn't already one
        $messages = is_array($this->getMessages()) ? $this->getMessages() : [$this->getMessages()];

        $redirect = $this->getRedirect();
        $headers = $this->getHeaders();
        $content = [];

        /** @var MessageBag $message */
        foreach ($messages as $message) {
            $content['message'][] = $message->getMessages();
        }

        if (isset($redirect)) {
            $content['redirect'] = $redirect;
        }

        return Response::json($content, $this->getStatusCode(), $headers);
    }
} 
