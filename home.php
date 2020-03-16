<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Redirect::to('index.php');
    }

    echo '<a href="changepassword.php" style="margin-right: 20px;">Change password</a>';
    echo '<a href="logout.php">Log out</a> <hr/>';

    echo 'Hello! ' . $user->data()->Email;
