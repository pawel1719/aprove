<?php
class Validation {
    private $_passed = false,
            $_errors = array(),
            $_db = null;

    public function __construct() {
        $this->_db = DBB::getInstance();
    }

    public function check($source, $items = array()) {
        foreach($items as $item => $rules) {
            foreach($rules as $rule => $rule_value) {

                $value = trim($source[$item]);
                $item = escape($item);

                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} jest wymagane!");
                } else if(!empty($value)) {
                    switch($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} musi być minimalnie {$rule_value} znaków.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} musi być maksymalnie {$rule_value} znaków.");
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} musi pasować {$item}");
                            }
                        break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$item} już istnieje.");
                            }
                        break;
                    }
                }
            }
        }

        if(empty($this->_errors)) {
            $this->_passed = true;
        }

        return $this;
    }

    public function addError($error) {
        $this->_errors[] = str_replace('_', ' ',$error);
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}