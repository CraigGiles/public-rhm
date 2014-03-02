<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use Carbon\Carbon;
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
     * @return mixed
     */
    public function save(Note $note) {
        $id = $note->getNoteId();
        if (isset($id)) {
            $this->update($note);
        } else {
            $id = DB::table('notes')->insertGetId(array(
                self::C_ACCOUNT_ID => $note->getAccountId(),
                self::C_CONTACT_ID => $note->getContactId(),
                self::C_ACTION => $note->getAction(),
                self::C_AUTHOR => $note->getAuthor(),
                self::C_TEXT => $note->getText(),
                self::C_CREATED_AT => Carbon::now(),
                self::C_UPDATED_AT => Carbon::now(),
            ));
            $note->setNoteId($id);
        }
        return $id;
    }

    private function update(Note $note) {
        $update = $this->getUpdateArray($note);
        $id = null;

        if (isset($update) && count($update) > 0) {
            $id = DB::table('notes')
                    ->where(self::C_ID, '=', $note->getNoteId())
                    ->update($update);
        }

        return $id;

    }

    private function getUpdateArray(Note $note) {
        $array = [];
        $accountId = $note->getAccountId();
        $contactId = $note->getContactId();
        $action = $note->getAction();
        $author = $note->getAuthor();
        $text = $note->getText();

        if (isset($accountId)) $array[] = $accountId;
        if (isset($contactId)) $array[] =  $contactId;
        if (isset($action)) $array[] = $action;
        if (isset($author)) $array[] = $author;
        if (isset($text)) $array[] = $text;

        return $array;
    }
}
