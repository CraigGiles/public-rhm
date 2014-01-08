<?php
use Illuminate\Database\Connection;

class DataAccessObjectSQL {
    protected $db;

    public function __construct(Connection $connection) {
        $this->db = $connection;
    }
} 