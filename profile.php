<?php
require_once 'core/init.php';

if(!$username = Input::get('user')) {
    Logs::addError("Unauthorization access!");
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
}