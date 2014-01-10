<?php

interface NoteDAO {
    /**
     * Save a record and return the objectId
     *
     * @param Note $note
     * @return mixed
     */
    public function save(Note $note);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $notes
     * @return array
     */
    public function saveAll(array $notes);
}
