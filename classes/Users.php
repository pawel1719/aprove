<?php

class Users {
    private $_db,
            $_data;

    public function __construct() {
        $this->_db = DBB::getInstance();
    }

    public function usersAll($offset = 10, $row_count = 15) {
        $users = $this->_db->query('SELECT 
                                            u.ID, u.IDHash, u.Email, u.LastLoginAt, 
                                            u.IsBlocked, u.BlockedAt, u.BlockedTo, d.*
                                        FROM users u LEFT JOIN users_data d ON u.ID=d.IDUsers
                                        LIMIT '. ((int)$offset) .', '. $row_count);

        if(!$users) {
            throw new Exception('#121 Cant get users information');
        }

        return $users;
    }

    public function usersToAgreements($agreement, $offset = 10, $row_count = 15) {
        $users = $this->_db->query("SELECT 
                                            u.ID,
                                            a.IDagreementsConfiguration, a.AcceptAgreement,
                                            u.*, ud.* 
                                        FROM users u LEFT JOIN users_data ud ON u.ID=ud.IDUsers
                                                     LEFT JOIN agreements a  ON u.ID=a.IDUsers AND a.IDagreementsConfiguration = {$agreement}
                                        ORDER BY a.IDagreementsConfiguration DESC, u.ID ASC
                                        LIMIT {$offset}, {$row_count};");

        if(!$users) {
            Logs::addError("#122 Can't get users to agreement. Variable: agreement = {$agreement}, offset = {$offset}, row_count = {$row_count}");
            throw new Exception('#122 Cant get users to agreement!');
        }

        return $users;
    }

    public function allNumberAccount() {
        return $this->_db->query("SELECT count(*) 'rows' FROM users");
    }

}