<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
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
            <div class="col-1 col-md-3 col-lg-4"></div>
            <div class="col-10 col-md-6 col-lg-4">

                <button type="button" class="btn btn-light">
                    <a href="changepassword.php" style="margin-right: 20px;">Change password</a>
                </button>
                <button type="button" class="btn btn-light">
                    <a href="logout.php">Log out</a>
                </button>

                <hr/>

                Hello <?php  echo $user->data()->Email; ?>! <br/><br/>

                <?php
                    echo $user->getUserGroup() . '<br/>';
                    echo $user->hasPermission('admin');

                ?>

            </div>
            <div class="col-1 col-md-3 col-lg-4"></div>
        </div>
    </div>

</BODY>
</HTML>