<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_list_approval', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_list_approval/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('../home.php');
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
        <div class="col-12 col-sm-12 col-md-12 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">

            <h2 class="text-warning text-center text-lg-left">Zarządzanie zgodami!</h2>
            <hr>
            <?php

                // Warning when link doesnt work or dont exist variable - aproval
                if(Session::exists('approvalmanag')){
                    echo '<div class="alert alert-warning">'. Session::flash('approvalmanag') .'</div>';
                }

            ?>
            <br>

            <div class="table-responsive">
                <table class="table table-light table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nr</th>
                        <th scope="col">Tytuł</th>
                        <th scope="col">Wersja</th>
                        <th scope="col">Aktywna</th>
                        <th scope="col">Początek</th>
                        <th scope="col">Koniec</th>
                        <th scope="col">Członkowie</th>
                        <th scope="col">Zgoda</th>
                    </tr>
                    </thead>
                    <tbody class="table-sm">
                    <?php

                        $approvals = new Approval();
                        $approvals = $approvals->data();
                        $no = 1;

                        foreach($approvals as $approval) {
                            echo "\n<tr>\t";
                            echo '<td class="small">'. $no .'</td>';
                            echo '<td class="small">'. $approval->Title .'</td>';
                            echo '<td class="small">'. $approval->Version .'.0</td>';
                            echo '<td class="small">'. ((($approval->IsActived) == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo '<td class="small">'. $approval->DateStart .'</td>';
                            echo '<td class="small">'. $approval->DateEnd .'</td>';
                            echo '<td class="text-center small"><a href="approvusers.php?id='. $approval->AgreementGuid . escape('&page=1') .'">Zarządzaj</a></td>';
                            echo '<td class="text-center small"><a href="approvmanag.php?approval='. $approval->AgreementGuid .'">Edytuj</a></td>';
                            echo "\t</tr>";
                            $no++;
                        }

                    ?>

                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>