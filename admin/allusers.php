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

            <h2>Welcome in All users!</h2>

            <div class="table-responsive">
                <table class="table table-light table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Nr</th>
                        <th scope="col">Login</th>
                        <th scope="col">Nazwisko</th>
                        <th scope="col">Imię</th>
                        <th scope="col">Ostatnie logowanie</th>
                        <th scope="col">Zablokowany</th>
                        <th scope="col">Blokada od</th>
                        <th scope="col">Blokada do</th>
                    </tr>
                    </thead>
                    <tbody class="table-sm">

                    <?php

                        $data = $user->usersAll();
                        $users = $data->results();
                        $no = 1;

                        foreach($users as $u) {
                            echo "\n<tr>";
                            echo '<td>'. $no .'</td>';
                            echo '<td>'. $u->Email  .'</td>';
                            echo '<td>'. $u->LastName  .'</td>';
                            echo '<td>'. $u->FirstName .'</td>';
                            echo '<td>'. $u->LastLoginAt .'</td>';
                            echo '<td>'. ((($u->IsBlocked) == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo '<td>'. $u->BlockedAt .'</td>';
                            echo '<td>'. $u->BlockedTo .'</td>';
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