<?php namespace redhotmayo\model;

class Note extends DataObject {
    private $accountId;
    private $contactId;
    private $text;
    private $action;
    private $author;

    public function getNoteId() {
        return $this->getId();
    }

    public function setNoteId($id) {
        $this->setId($id);
    }

    /**
     * @param mixed $accountId
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }

    /**
     * @return mixed
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param mixed $contactId
     */
    public function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    /**
     * @return mixed
     */
    public function getContactId() {
        return $this->contactId;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author) {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * @param mixed $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText() {
        return $this->text;
    }

} 