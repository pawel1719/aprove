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
        Redirect::to('../home.php');
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

</HEAD>
<BODY style="background-color: #59B39A">

<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">

            <h2 class="text-warning text-center text-lg-left">Witaj w panelu administratora!</h2>
            <hr>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>