<?php

class NoteSQL {
    public static function GetColumns() {
        return array(
            'notes.accountId',
            'notes.contactId',
            'notes.action',
            'notes.author',
            'notes.text',
            'notes.created_at',
            'notes.updated_at',

        );
    }

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