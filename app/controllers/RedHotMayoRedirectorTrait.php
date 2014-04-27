<?php

use Illuminate\Http\Response as ResponseCodes;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;

trait RedHotMayoRedirectorTrait {
    private $statusCode = ResponseCodes::HTTP_OK;
    private $redirect;
    private $messages = [];
    private $headers = [];

    /**
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param $status
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setStatusCode($status) {
        $this->statusCode = (int)$status;
    }

    /**
     * @param $redirect
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setRedirect($redirect) {
        $this->redirect = $redirect;
    }

    /**
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getRedirect() {
        return $this->redirect;
    }

    /**
     * @param $messages
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setMessages($messages) {
        $this->messages = $messages;
    }

    /**
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * @param $headers
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    /**
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param $redirect
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function respondSuccess($redirect) {
        $this->setStatusCode(ResponseCodes::HTTP_OK);
        $this->setRedirect($redirect);

        return $this->respond();
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function respondWithUnknownError($message) {
        $this->setStatusCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        $this->setMessages($message);

        return $this->respond();
    }

    /**
     * @param $message
     * @param null $redirect
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function respondNotFound($message, $redirect = null) {
        $this->setStatusCode(ResponseCodes::HTTP_NOT_FOUND);
        $this->setMessages($message);
        $this->setRedirect($redirect);
        $this->setHeaders([]);

        return $this->respond();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function respond() {
        // convert message to an array if it isn't already one
        $messages = is_array($this->getMessages()) ? $this->getMessages() : [$this->getMessages()];

        $redirect = $this->getRedirect();
        $headers = $this->getHeaders();
        $content = [];

        /** @var MessageBag $message */
        foreach ($messages as $message) {
            if ($message instanceof MessageBag) {
                $content['message'][] = $message->getMessages();
            } else {
                $content['message'] = $message;
            }
        }

        if (isset($redirect)) {
            $content['redirect'] = $redirect;
        }

        return Response::json($content, $this->getStatusCode(), $headers);
    }
} 
