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
        $fields['Content']       = $fields['Content'];
        $fields['IsActived']     = ($fields['IsActived']== 'on') ? 1 : 0;
        $fields['CreateAt']      = date('Y-m-d H:i:s');

        if(!$this->_db->insert('agreements_configuration', $fields)) {
            throw new Exception('#362 Error cant add new agreement');
        }
    }

    public function data() {
        $this->_data = $this->_db->query('SELECT 
            DISTINCT(ac.Title)
            ,ac2.Version
            ,ac2.IsActived
            ,ac2.DateStart
            ,ac2.DateEnd
            ,ac2.AgreementGuid
            ,ac2.CreateAt
        FROM agreements_configuration ac LEFT JOIN agreements_configuration ac2 ON ac.Title=ac2.Title #AND ac2.ID=(SELECT ID FROM agreements_configuration WHERE Title=ac.Title ORDER BY ID DESC LIMIT 1)
        ORDER BY ac.Title ASC, ac2.Version DESC');

        return $this->_data->results();
    }

    public function getApproval($where = array()) {
        $data = $this->_db->get('agreements_configuration', $where);

        if(!$data) {
            throw new Exception("#132 Cant find data");
        }

        return $data->firstResult();
    }

    public function update($id = null, $fields = array()) {
        $fields['UpdatedAt'] = date('Y-m-d H:i:s');
        $fields['IsActived']     = ($fields['IsActived']== 'on') ? 1 : 0;

        if(!$this->_db->update('agreements_configuration', $id, $fields)) {
            throw new Exception('There was a problem updating!');
        }
    }

}