<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('index.php');
    }
    if(!$user->hasPermission('user_approval', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission user_approval/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/main_index'); ?>

</HEAD>
<BODY class="bg-secondary">

    <div class="container">
        <div class="row mt-2">
            <div class="col-1 col-md-2 col-lg-1 col-xl-1"></div>
            <div class="col-10 col-md-8 col-lg-10 col-xl-10">

                <!-- App menu -->
                <?php include_once Config::get('includes/main_menu'); ?>

                <div class="table-responsive">
                    <table class="table table-light table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nr</th>
                            <th scope="col">Tytuł</th>
                            <th scope="col" class="text-center">Wersja</th>
                            <th scope="col" class="text-center">Odpowiedź</th>
                            <th scope="col" class="text-center">Zobacz</th>
                        </tr>
                        </thead>
                        <tbody class="table-sm">

                <?php

                    if(Session::exists('agreement_accept')) {
                        echo '<div class="alert alert-success" role="alert">'. Session::flash('agreement_accept') .'</div>';
                        Logs::addInformation('Approval accepted.');
                    }

                    $approval = new Approval();
                    $approval_data = $approval->userApproval('WHERE a.IDUsers = '. $user->data()->ID .' ORDER BY a.ID DESC');
                    $no = 1; //counter to table

                    if($approval_data != false) {
                        foreach ($approval_data as $a) {
                            echo "\n<tr>\t";
                            echo '<td>'. $no .'</td>';
                            echo '<td>'. $a->Title . '</td>';
                            echo '<td class="text-center">'. $a->Version . '.0</td>';
                            echo '<td class="text-center'. (($a->AcceptAgreement == '1') ? '">TAK' : (($a->AcceptAgreement === '0') ? '">NIE' : ' bg-danger">BRAK')) .'</td>';
                            echo '<td class="text-center">'. '<a href="approvalusers.php?id=' . $a->AccessGuid . '">Pokaż</a>';
                            echo "\t</tr>";
                            $no++;
                        }
                    }

                ?>

            </div>
            <div class="col-1 col-md-2 col-lg-1 col-xl-1"></div>
        </div>
    </div>
</BODY>
</HTML>