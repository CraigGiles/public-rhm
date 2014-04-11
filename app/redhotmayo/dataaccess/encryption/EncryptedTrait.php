<?php  namespace redhotmayo\dataaccess; 

trait EncryptedTrait {
    /**
     * Obtain a list of encrypted columns
     */
    public abstract function getEncryptedColumns();

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
                $newData = Crypt::decrypt($data);
            } else {
                $newData = $value;
            }

            $decrypted[$key] = $newData;
        }

        return $decrypted;
    }

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
                $newData = Crypt::encrypt($data);
            } else {
                $newData = $value;
            }

            $encrypted[$key] = $newData;
        }

        return $encrypted;
    }
} 
