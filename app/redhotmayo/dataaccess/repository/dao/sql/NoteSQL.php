<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use DateTime;
use Illuminate\Support\Facades\DB;
use redhotmayo\model\Note;

class NoteSQL {
    const TABLE_NAME = 'notes';
    const C_ID = 'id as noteId';
    const C_ACCOUNT_ID = 'accountId';
    const C_CONTACT_ID = 'contactId';
    const C_ACTION = 'action';
    const C_AUTHOR = 'author';
    const C_TEXT = 'text';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public static function GetColumns() {
        return array(
            self::TABLE_NAME . '.' . self::C_ID,
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
        $id = $note->getNoteId();
        if (!isset($id)) {
            $id = DB::table('notes')->insertGetId(array(
                self::C_ACCOUNT_ID => $note->getAccountId(),
                self::C_CONTACT_ID => $note->getContactId(),
                self::C_ACTION => $note->getAction(),
                self::C_AUTHOR => $note->getAuthor(),
                self::C_TEXT => $note->getText(),
                self::C_CREATED_AT => date('Y-m-d H:i:s'),
                self::C_UPDATED_AT => date('Y-m-d H:i:s'),
            ));

            $note->setNoteId($id);
        }
        return $id;
    }
}
