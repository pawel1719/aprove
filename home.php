<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('index.php');
    }

    if(!$user->hasPermission('user_home', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission user_home/read');
        Session::put('error', 'Nie masz uprawnień do tej strony');
        Redirect::to('logout.php');
    }

    $approval = new Approval();

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/main_index'); ?>

</HEAD>
<BODY style="background-color: #59B39A">

    <div class="container">
        <div class="row mt-2">
            <div class="col-1 col-md-2 col-lg-1 col-xl-1"></div>
            <div class="col-10 col-md-8 col-lg-10 col-xl-10">
                <?php
                    // App menu
                    include_once Config::get('includes/main_menu');

                    if(Session::exists('agreement_accept')) {
                        echo '<div class="alert alert-success" role="alert">'. Session::flash('agreement_accept') .'</div>';
                    }
                    if(Session::exists('warning')) {
                        echo '<div class="alert alert-warning" role="alert">'. Session::flash('warning') .'</div>';
                    }

                ?>

            <div class="row">
                <div class="card border-primary mb-3 shadow rounded" style="min-width: 15rem; max-width: 22rem; margin-right: auto; margin-left: auto;">
                    <div class="card-header text-primary">Witaj w apliakcji!</div>
                    <div class="card-body text-primary">
                        <h5 class="card-title"><?php  echo $user->dataDetails()->FirstName .' '. $user->dataDetails()->LastName; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted small"><?php echo $user->data()->Email; ?></h6>
                        <p class="card-text text-muted small">Grupa <?php echo $user->getUserGroup(); ?></p>
                    </div>
                </div>
                <?php

                    $message = $approval->userApproval('WHERE a.IDUsers = '. $user->data()->ID .' AND a.AcceptAgreement IS NULL');

                    if($message != false) {
                        if((int)$message[0]->IsActived == 1) {
                            if ($message[0]->DateStart <= date('Y-m-d')) {
                                echo '<div class="card text-white bg-danger mb-3" style="min-width: 15rem; max-width: 27rem; margin-right: auto; margin-left: auto;">
                                    <div class="card-header">Ważne!!!
                                        <span class="badge badge-dark badge-pill float-right">' . count($message) . '</span>
                                    </div>
                                    <div class="card-body text-white">
                                        <h6 class="card-title">Użytkowniku <b>' . $user->dataDetails()->FirstName . ' ' . $user->dataDetails()->LastName . '</b></h6>
                                        <p class="card-text">Udziel odpowiedzi na <u>wszystkie</u> regulaminy i zgody!<br>Liczba nieudzielonych odpowiedzi <b>' . count($message) . '</b> !!!</p>
                                        <a href="hometable.php" class="btn btn-outline-light float-right">Przejdź</a>
                                        <p class="card-subtitle text-dark small">Automation message.</p>
                                    </div>
                                </div>';
                            }
                        }
                    }
                ?>
            </div>
            <div class="col-1 col-md-2 col-lg-1 col-xl-1"></div>
        </div>
    </div>
</BODY>
</HTML>