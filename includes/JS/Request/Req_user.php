<?php
require '../../../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Logs::addError("Unauthorization access!");
    Redirect::to('../../../index.php');
}
if(Input::exists()) {
        // connect to db
        $db = DBB::getInstance();

        switch((int)Input::get('case'))
        {
            case 1:
                $permission = $db->query('SELECT ID, Name FROM permission ORDER BY ID ASC')->results();
                $option_list = '';

                foreach($permission as $role) {
                    $option_list .= '<option value="'. (($role->ID == Input::get('perm')) ? ($role->ID .'" selected') : $role->ID. '"') .'>'. $role->Name ."</option>\n";
                }

                echo  $option_list;

            break;
            case 2:
                $fields = array(
                    Input::get('field') => Input::get('value'),
                    Input::get('field') .'UpdatedAt' => date('Y-m-d H:i:s')
                );
                $update = $user->updateDetails($fields, Input::get('user_id'));

                if(!$update) {
                    echo 'Error: Cant update data!';
                    echo var_dump($fields);
                    Logs::addError('Cant update informaton users. Field '. Input::get('field') .' value '. Input::get('value'));
                } else {
                    echo 'Success: Data updated!';
                }
            break;
            default:
                echo 'Error: Incorrect action';
                Logs::addError('Incorrect action!');
            break;
        }
//    } else {
//        // error for incorrect token
//        echo 'Error: incorrect token';
//        Logs::addError('Incorrect token! Session=' . Session::get('token') . ' == input_token=' . Input::get('token'));
//    }

} else {
    // error for empty $_POST or $_GET
    echo 'Error: input variable dont exists!';
    Logs::addError('Input variable dont exists!');

}