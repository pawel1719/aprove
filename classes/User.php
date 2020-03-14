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
            if($this->data()->Password === Hash::make($password, $this->data()->Salt)) {
                Session::put($this->_sessionName, $this->data()->ID);
                return true;
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