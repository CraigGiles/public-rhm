<?php

namespace redhotmayo\dataaccess\encryption;

use Illuminate\Support\Facades\Crypt;

abstract class EncryptedSQLTable {
    /**
     * Obtain a list of encrypted columns
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public abstract function getEncryptedColumns();

    /**
     * Take in a list of key/value pairs and return the set of encrypted data
     *
     * @param array $data
     *
     * @return array
     */
    public function encrypt(array $data) {
        $encrypted = [];
        $encryptedColumnNames = $this->getEncryptedColumns();

        foreach ($data as $key => $value) {
            if (in_array($key, $encryptedColumnNames)) {
                $newData = Crypt::encrypt($value);
            } else {
                $newData = $value;
            }

            $encrypted[$key] = $newData;
        }

        return $encrypted;
    }

    /**
     * Take in a list of key/value pairs and return the set of decrypted the results
     *
     * @param array $data
     *
     * @return array
     */
    public function decrypt(array $data) {
        $decrypted = [];
        $encryptedColumnNames = $this->getEncryptedColumns();

        foreach ($data as $key => $value) {
            if (in_array($key, $encryptedColumnNames)) {
                $newData = Crypt::decrypt($value);
            } else {
                $newData = $value;
            }

            $decrypted[$key] = $newData;
        }

        return $decrypted;
    }
}
