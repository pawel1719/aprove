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
        $this->_data = $this->_db->query('SELECT DISTINCT
                 ac.Title
                ,ac2.Version
                ,ac2.IsActived
                ,ac2.DateStart
                ,ac2.DateEnd
                ,ac2.AgreementGuid
                ,ac2.CreateAt
                ,(SELECT count(IDUsers) FROM agreements WHERE IDagreementsConfiguration = ac.ID) NoUsers
            FROM agreements_configuration ac LEFT JOIN agreements_configuration ac2 ON ac.ID=ac2.ID
            ORDER BY ac.Title ASC, ac2.Version DESC');

        if(!$this->_data)
        {
            Logs::addError('#133 Cant find agreement!');
            throw new Exception('#133 Cant find agreement!');
        }

        return $this->_data->results();
    }

    public function getApproval($where = array())
    {
        $data = $this->_db->get('agreements_configuration', $where);

        if(!$data)
        {
            Logs::addError('#132 Cant find data');
            throw new Exception('#132 Cant find data');
        }

        return $data->firstResult();
    }

    public function userApproval($where)
    {
        $data = $this->_db->query('SELECT a.ID ID_a, a.*, u.Email, u.IDHash, ud.FirstName, ud.MiddleName, ud.LastName, ac.*
                                        FROM agreements a 	LEFT JOIN users u ON a.IDUsers=u.ID
                                                            LEFT JOIN users_data ud ON a.IDUsers=ud.IDUsers
                                                            LEFT JOIN agreements_configuration ac ON a.IDagreementsConfiguration=ac.ID '
                                        . $where);
        if(!$data)
        {
            Logs::addError('#134 Cant find data for agreement!');
            throw new Exception('#134 Cant find data user for agreement!');
        }

        if($data->count())
        {
            return $data->results();
        }

        return false;
    }

    public function update($id = null, $fields = array())
    {
        $fields['UpdatedAt'] = date('Y-m-d H:i:s');
        $fields['IsActived'] = ($fields['IsActived']== 'on') ? 1 : 0;

        if(!$this->_db->update('agreements_configuration', $id, $fields))
        {
            throw new Exception('There was a problem updating!');
        }
    }

    public function updateAgreement($id = null, $fields = array())
    {
        $fields['DataAccept']   = date('Y-m-d H:i:s');
        $fields['IPAddress ']   = Input::get('REMOTE_ADDR');
        $fields['Port']         = Input::get('REMOTE_PORT');
        $fields['Device']       = Input::get('HTTP_USER_AGENT');

        if(!$this->_db->update('agreements', $id, $fields))
        {
            throw new Exception('There was a problem updating!');
        }
    }

}