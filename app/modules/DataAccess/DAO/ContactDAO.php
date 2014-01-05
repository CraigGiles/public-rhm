<?php

interface ContactDAO {
    /**
     * Save a record and return the objectId
     *
     * @param Contact $contact
     * @return mixed
     */
    public function save(Contact $contact);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $contacts
     * @return array
     */
    public function saveAll(array $contacts);
}
