<?php
class User {
    private $db;

    public function __construct() {
        $this->db = DBB::getInstance();
    }

    public function create($fields = array()) {
        if(!$this->db->insert('users', $fields)) {
            throw new Exception('Error! Cant creating new account!');
        }
    }
}