<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {

        $valdaion = new Validation();
        $validate = $valdaion->check($_POST, array(
            'min' => 2
        ));
    }
}

echo var_dump($user->dataDetails());

echo $user->dataDetails()->FirstName .'<br/>';
echo $user->dataDetails()->LastName .'<br/>';