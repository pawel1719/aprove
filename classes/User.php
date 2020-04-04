<?php
class User {
    private $_db,
            $_data,
            $_dataDetails,
            $_sessionName,
            $_isLoggedIn;


    public function __construct($user = null) {
        $this->_db = DBB::getInstance();
        $this->_sessionName = Config::get('session/session_name');

        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->_isLoggedIn = false;
                }
            }
        } else {
            $this->find($user);
        }
    }//end constructor

    public function create($fields = array()) {
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception('Error! Cant creating new account!');
        }
    }

    public function find($user = null) {
        if($user) {
            $field = (is_numeric($user)) ? 'ID' : 'Email';
            $data = $this->_db->get('users', array($field, '=', $user));

            if($data->count()) {
                $this->_data = $data->firstResult();
                $data_details = $this->_db->get('users_data', array('IDUsers', '=', $this->_data->ID));
                $this->_dataDetails = $data_details->firstResult();
                return true;
            }
        }
        return false;
    }

    public function update($fields = array(), $id = null) {
        if(!$id && $this->isLogged()) {
            $id = $this->data()->ID;
        }

        if(!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating!');
        }
    }

    public function updateDetails($fields = array(), $id = null) {
        if(!$id && $this->isLogged()) {
            $id = $this->dataDetails()->ID;
        }

        if(!$this->_db->update('users_data', $id, $fields)) {
            throw new Exception('There was a problem updating details!');
        }
    }

    public function passwordHistory($password, $created, $updated, $id = null) {
        if(!$id && $this->isLogged()) {
            $id = $this->data()->ID;
        }

        if(!$this->_db->insert('password', array(
            'IDUsers' => $id,
            'Password' => Hash::make($password, $created),
            'CratedAt' => $created,
            'ChangedAt' => $updated
        ))) {
            throw new Exception('There was a problem with saving password!');
        }
    }

    public function passwordRepeated($password, $last_set_password = 3, $id = null) {
        if(!$id && $this->isLogged()) {
            $id = $this->data()->ID;
        }

        $passwords = $this->_db->query('SELECT * FROM `password` WHERE IDUsers = ' . $id . ' ORDER BY ChangedAt DESC LIMIT ' . $last_set_password);
        $counter = ($passwords->count() < $last_set_password-1) ? $passwords->count() : $last_set_password-1;

        if($counter != 0) {
            //currently changed password
            if(Hash::make($password, $this->data()->Salt) === $this->data()->Password) {
                return false;
            }
            //last set passwords
            foreach($passwords->results() as $pass) {
                if(Hash::make($password, $pass->CratedAt) === $pass->Password) {
                    return false;
                }
            }
        }

        return true;
    }

    public function login($username = null, $password = null) {
        $user = $this->find($username);

        if($user) {
            if($this->data()->IsBlocked == 0 || $this->data()->BlockedTo < date('Y-m-d H:i:s')) {
                if ($this->data()->Password === Hash::make($password, $this->data()->Salt)) {
                    //Correct login attempt
                    $this->_db->update('users', $this->data()->ID, array(
                        'LastLoginAt' => date('Y-m-d H:i:s'),
                        'UpdatedAt' => date('Y-m-d H:i:s'),
                        'CounterCorrectLogin' => $this->data()->CounterCorrectLogin + 1,
                        'BlockedAt' => null,
                        'BlockedTo' => null,
                        'InvalidAttemptCounter' => 0,
                        'IsBlocked' => 0
                    ));
                    Session::put($this->_sessionName, $this->data()->ID);
                    return true;
                } else {
                    if($this->data()->BlockedTo == null || $this->data()->IsBlocked == 0) {
                        //Invalid attempt login
                        $this->_db->update('users', $this->data()->ID, array(
                            'UpdatedAt' => date('Y-m-d H:i:s'),
                            'CounterIncorretLogin' => $this->data()->CounterIncorretLogin + 1,
                            'InvalidAttemptCounter' => $this->data()->InvalidAttemptCounter + 1
                        ));

                        if ($this->data()->InvalidAttemptCounter >= Config::get('user/number_failed_login_attempts') - 1) {
                            // Blocked account user
                            $this->_db->update('users', $this->data()->ID, array(
                                'IsBlocked' => 1,
                                'BlockedAt' => date('Y-m-d H:i:s'),
                                'BlockedTo' => date('Y-m-d H:i:s', strtotime(Config::get('user/time_to_blocked_account'), strtotime(date("Y-m-d H:i:s"))))
                            ));
                        }
                    } else {
                        $blocked = new DateTime($this->data()->BlockedTo);
                        $now     = new DateTime('now');

                        if($blocked < $now) {
                            $this->_db->update('users', $this->data()->ID, array(
                                'UpdatedAt' => date('Y-m-d H:i:s'),
                                'IsBlocked' => 0,
                                'InvalidAttemptCounter' => 0
                            ));
                        }
                    }
                }
            }
        }
        return false;
    }

    public function hasPermission($key) {
        $group = $this->_db->get('Permission', array('ID', '=', $this->data()->Permission));

        if($group->count()) {
            $permissions = json_decode($group->firstResult()->KeyPermission);

            if($permissions->$key == true) {
                return true;
            }
        }
        return false;
    }

    public function data() {
        return $this->_data;
    }

    public function dataDetails() {
        return $this->_dataDetails;
    }

    public function usersAll($offset = 10, $row_count = 15) {
        $users = $this->_db->query('SELECT 
            u.ID, u.Email, u.LastLoginAt, 
            u.IsBlocked, u.BlockedAt, u.BlockedTo, 
            d.FirstName, d.LastName 
        FROM users u LEFT JOIN users_data d ON u.ID=d.IDUsers
        LIMIT '. ((int)$offset) .', '. $row_count);

        if(!$users) {
            throw new Exception('#121 Cant get users information');
        }

        return $users;
    }

    public function allNumerAccount() {
        return $this->_db->query('SELECT count(*) \'rows\' FROM users');
    }

    public function getUserGroup() {
        $nameGroup = $this->_db->get('Permission', array('ID', '=', $this->data()->Permission));

        return $nameGroup->firstResult()->Name;
    }

    public function isLogged() {
        return $this->_isLoggedIn;
    }

    public function logout() {
        Session::delete($this->_sessionName);
    }

    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }
}