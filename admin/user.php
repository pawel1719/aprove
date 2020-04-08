<?php
require_once '../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Redirect::to('../index.php');
}

if(!Input::get('id')) {
    Session::flash('user_managment', 'Something went wrong!');
    Redirect::to('allusers.php');
}



?>


<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <h2>Managment user!</h2>
            <br>
            <br>

            <?php

                $users = new User(Input::get('id'));
                $single_user = $users->data();

                echo 'ID --- '. $single_user->ID .'<br/>';
                echo 'Email --- '. $single_user->Email .'<br/>';
                echo 'Permission --- '. $single_user->Permission .'<br/>';
                echo 'PasswordCreadtedAt --- '. $single_user->PasswordCreadtedAt .'<br/>';
                echo 'LastLoginAt --- '. $single_user->LastLoginAt .'<br/>';
                echo 'CreatedAt --- '. $single_user->CreatedAt .'<br/>';
                echo 'UpdatedAt --- '. $single_user->UpdatedAt .'<br/>';
                echo 'IsBlocked --- '. $single_user->IsBlocked .'<br/>';
                echo 'BlockedAt --- '. $single_user->BlockedAt .'<br/>';
                echo 'BlockedTo --- '. $single_user->BlockedTo .'<br/>';
                echo 'CounterCorrectLogin --- '. $single_user->CounterCorrectLogin .'<br/>';
                echo 'CounterIncorretLogin --- '. $single_user->CounterIncorretLogin .'<br/>';

            ?>






        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>