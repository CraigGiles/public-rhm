<?php
use Illuminate\Database\Connection;

class DataAccessObjectSQL {
    protected $db;

    public function __construct(Connection $db) {
        //if DB isn't set, use the laravel 4 facade
        if (!isset($db)) {
            $this->db = DB;
        } else {
            $this->db = $db;
        }
    }
} 