<?php
require_once '../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Redirect::to('../index.php');
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

            <h2>Welcome in managment approvals!</h2>
            <br>
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
                    </tr>
                    </thead>
                    <tbody class="table-sm">

                    <?php

                        $approvals = new Approval();
                        $approvals = $approvals->data();
                        $no = 1;

                        foreach($approvals as $approval) {
                            echo '<tr>';
                            echo '<td>'. $no .'</td>';
                            echo '<td>'. $approval->Title .'</td>';
                            echo '<td>' . $approval->Version .'.0 </td>';
                            echo '<td>'. ((($approval->IsActived) == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo '<td>'. $approval->DateStart .'</td>';
                            echo '<td>'. $approval->DateEnd .'</td>';
                            echo '</tr>';
                            $no++;
                        }

                    ?>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>