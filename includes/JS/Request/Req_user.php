<?php
require '../../../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Logs::addError("Unauthorization access!");
    Redirect::to('../../../index.php');
}

// permission to edit
if(!$user->hasPermission('admin_user_data', 'write')) {
    Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_user_data/write');
    Session::flash('warning', 'Nie masz uprawnień!');
    Redirect::to('home.php');
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
                if(Input::get('field') == 'Permission' || Input::get('field') == 'IsBlocked')
                {
                    // update data in table users
                    $fields = array(
                        Input::get('field') => Input::get('value')
                    );
                    $update = $user->update($fields, Input::get('user_id'));
                }else {
                    // update data in table users_data
                    $fields = array(
                        Input::get('field') => Input::get('value'),
                        Input::get('field') .'UpdatedAt' => date('Y-m-d H:i:s')
                    );
                    $update = $user->updateDetails($fields, Input::get('user_id'));
                }

                if(!$update) {
                    echo 'Błąd podczas aktualizacji danych!';
                    // echo var_dump($fields);
                    Logs::addError('Cant update informaton users. Field '. Input::get('field') .' value '. Input::get('value'));
                } else {
                    echo 'Dane zosatły zaktualizowane!';
                    Logs::addInformation('Success - data was updated.');
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