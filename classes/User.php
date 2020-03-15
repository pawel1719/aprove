<?php
class User {
    private $_db,
            $_data,
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
                return true;
            }
        }
        return false;
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

    public function data() {
        return $this->_data;
    }

    public function isLogged() {
        return $this->_isLoggedIn;
    }

    public function logout() {
        Session::delete($this->_sessionName);
    }
}