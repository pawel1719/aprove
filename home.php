<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Redirect::to('index.php');
    }

    echo '<a href="logout.php">Log out</a> <hr/>';

    echo 'Hello! ' . $user->data()->Email;
