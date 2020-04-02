<?php

class Approval {

    private $_db,
            $_data;

    public function __construct() {
        $this->_db = DBB::getInstance();
    }

    public function addNew($fields = array()) {
        $version = 0;
        $title = $this->_db->query('SELECT * FROM agreements_configuration WHERE Title = "' . $fields['Title'] . '" ORDER BY CreateAt DESC');

        if($title->count()) {
            $version = (int)$title->firstResult()->Version;
        }

        $fields['AgreementGuid'] = md5($fields['Title'] . $fields['Content']);
        $fields['Version']       = 1 + $version;
        $fields['Content']       = nl2br($fields['Content']);
        $fields['IsActived']     = ($fields['IsActived']== 'on') ? 1 : 0;
        $fields['CreateAt']      = date('Y-m-d H:i:s');

        if(!$this->_db->insert('agreements_configuration', $fields)) {
            throw new Exception('#362 Error cant add new agreement');
        }
    }

    public function data() {
        $this->_data = $this->_db->query('SELECT * FROM agreements_configuration ORDER BY CreateAt DESC');

        return $this->_data->results();
    }
}