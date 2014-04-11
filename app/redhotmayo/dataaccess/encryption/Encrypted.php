<?php namespace redhotmayo\dataaccess\encryption;

interface Encrypted {
    /**
     * Obtain a list of encrypted columns
     */
    public function getEncryptedColumns();

    /**
     * Take in a list of key/value pairs and return the set of decrypted the results
     *
     * @param array $data
     *
     * @return array
     */
    public function decrypt(array $data);

    /**
     * Take in a list of key/value pairs and return the set of encrypted data
     *
     * @param array $data
     *
     * @return array
     */
    public function encrypt(array $data);
} 
