<?php
require '../../../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Logs::addError("Unauthorization access!");
    Redirect::to('../../../index.php');
}
    if(Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (Input::get('case') == '1') {

                //connect do db
                $db = DBB::getInstance();

                //array data to insert
                $users_agreement_add = array(
                    'IDUsers' => (int)Input::get('user_id'),
                    'IDagreementsConfiguration' => (int)Input::get('agreement'),
                    'AccessGuid' => hash('sha256',Input::get('hash_agree') .' '. $user->data()->ID. ' '. $user->dataDetails()->FirstName .' '. $user->dataDetails()->LastName .' '. date('Y-m-d H:i:s')), //dodać tytuł i imie nazwisko usera oraz date
                    'Password' => hash('sha256', '$tring1234'),
                    'PasswordValidity' => date( 'Y-m-d H:i:s', strtotime( date( 'Y-m-d H:i:s' ) .' +1 day' )),
                    'AddedBy' => $user->data()->ID,
                    'AddedAt' => date('Y-m-d H:i:s')
                );
//                echo var_dump($_POST);
//                echo var_dump($users_agreement_add);

                if(Input::get('status') == 'true') {
                    //adding data agreement
                    try {
                        $db->insert('agreements', $users_agreement_add);
                        echo 'Success adding!';
                    }catch (Exception $e) {
                        Logs::addError("Cant add new agreemet. Message: ". $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile() .' Array:'. strval(var_dump($users_agreement_add)));
                        die('#2321: Error: '. $e->getMessage());
                    }
                } else {
                    //delete data agreement
                    try {
                        $db->query('DELETE FROM agreements WHERE IDUsers = ? AND IDagreementsConfiguration = ?', array(
                            (int)Input::get('user_id'),
                            (int)Input::get('agreement')
                        ));
                        echo 'Success deleting!';
                    }catch(Exception $e) {
                        Logs::addError("Cant delete agreemet. Message: ". $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile() .' Variable:'. Input::get('user_id') .' '. Input::get('agreement'));
                        die('#2322: Error: '. $e->getMessage());
                    }

                }

                // token for this same form
                Session::put('token', Input::get('token'));
                // cleared variables
                unset($_POST);
                unset($users_agreement_add);
            }
        } else {
            // error for incorrect token
            echo 'Error: incorrect token';
            Logs::addError('Incorrect token! Session=' . Session::get('token') . ' == input_token=' . Input::get('token'));
        }

    } else {
        // error for empty $_POST or $_GET
        echo 'Error: input variable dont exists!';
        Logs::addError('Input variable dont exists!');

    }