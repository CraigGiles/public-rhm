<?php namespace redhotmayo\model;

class Note extends DataObject {
    private $accountId;
    private $contactId;
    private $text;
    private $action;
    private $author;

    public static function FromStdClass($note) {
        $accountId = isset($note->accountId) ? intval($note->accountId) : null;
        $contactId = isset($note->contactId) ? intval($note->contactId) : null;
        $action = isset($note->action) ? $note->action : null;
        $text = $note->text;
        $author = $note->author;

        $note = new Note();
        $note->setAccountId($accountId);
        $note->setContactId($contactId);
        $note->setText($text);
        $note->setAccountId($action);
        $note->setAuthor($author);
        return $note;
    }

    public static function FromArray($note) {
        $accountId = isset($note['accountId']) ? intval($note['accountId']) : null;
        $contactId = isset($note['contactId']) ? intval($note['contactId']) : null;
        $action = isset($note['action']) ? $note['action'] : null;
        $text = $note['text'];
        $author = $note['author'];

        $obj = new Note();
        $obj->setAccountId($accountId);
        $obj->setContactId($contactId);
        $obj->setText($text);
        $obj->setAction($action);
        $obj->setAuthor($author);
        return $obj;
    }

    public static function create($note) {
        if ($note instanceof \stdClass) {
            return Note::FromStdClass($note);
        } else if (is_array($note)) {
            return Note::FromArray($note);
        }
    }

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
        if (isset($accountId)) {
            $this->accountId = $accountId;
        }
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
        if (isset($contactId)) {
            $this->contactId = $contactId;
        }
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
        if (isset($action)) {
            $this->action = $action;
        }
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

    public function toArray() {
        return array(
            'accountId' => $this->accountId,
            'contactId' => $this->contactId,
            'text' => $this->text,
            'action' => $this->action,
            'author' => $this->author,
        );
    }

} 