<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('index.php');
    }

?>

<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/main_index'); ?>

</HEAD>
<BODY class="bg-secondary">

    <div class="container">
        <div class="row">
            <div class="col-1 col-md-2 col-lg-1 col-xl-1"></div>
            <div class="col-10 col-md-8 col-lg-10 col-xl-10">

                <!-- App menu -->
                <?php include_once Config::get('includes/main_menu'); ?>

                Hello <?php  echo $user->data()->Email; ?>! <br/><br/>

                <?php
                    echo $user->getUserGroup() . '<br/>';
                    echo $user->hasPermission('admin');

                ?>

            </div>
            <div class="col-1 col-md-2 col-lg-1 col-xl-1"></div>
        </div>
    </div>

</BODY>
</HTML>