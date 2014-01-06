<?php

class NoteSQL {

    /**
     * @param Note $note
     */
    public function save($note) {
        $id = DB::table('notes')->insertGetId(array(
            'accountId' => $note->getAccountId(),
            'contactId' => $note->getContactId(),
            'action' => $note->getAction(),
            'author' => $note->getAuthor(),
            'text' => $note->getText(),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ));

        $note->setId($id);
        return $id;
    }
} 