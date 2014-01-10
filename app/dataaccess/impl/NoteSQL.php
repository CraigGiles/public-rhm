<?php

class NoteSQL {
    const TABLE_NAME = 'notes';
    const C_ACCOUNT_ID = 'accountId';
    const C_CONTACT_ID = 'contactId';
    const C_ACTION = 'action';
    const C_AUTHOR = 'author';
    const C_TEXT = 'text';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public static function GetColumns() {
        return array(
            self::TABLE_NAME . '.' . self::C_ACCOUNT_ID,
            self::TABLE_NAME . '.' . self::C_CONTACT_ID,
            self::TABLE_NAME . '.' . self::C_ACTION,
            self::TABLE_NAME . '.' . self::C_AUTHOR,
            self::TABLE_NAME . '.' . self::C_TEXT,
            self::TABLE_NAME . '.' . self::C_CREATED_AT,
            self::TABLE_NAME . '.' . self::C_UPDATED_AT,
        );
    }

    /**
     * @param Note $note
     */
    public function save(Note $note) {
        $id = $note->getId();
        if (!isset($id)) {
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
        }
        return $id;
    }
} 