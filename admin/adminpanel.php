<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_panel', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_panel/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
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

            <h2 class="text-warning">Witaj w panelu administratora!</h2>

        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>