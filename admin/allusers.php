<?php
require_once '../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Logs::addError("Unauthorization access!");
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

            <?php

                if(Session::exists('user_managment')) {
                    echo '<div class="alert alert-warning">'. Session::flash('user_managment') .'</div>';
                }

            ?>

            <div class="table-responsive">
                <table class="table table-light table-striped table-hover">
                    <thead class="thead-dark table-sm">
                    <tr>
                        <th scope="col">Nr</th>
                        <th scope="col">Login</th>
                        <th scope="col">Nazwisko</th>
                        <th scope="col">Imię</th>
                        <th scope="col">Ostatnie logowanie</th>
                        <th scope="col">Blokada</th>
                        <th scope="col">Blokada do</th>
                    </tr>
                    </thead>
                    <tbody class="table-sm">

                    <?php

                        //Set default value if variables are wrong type
                        if(Input::exists('get')) {
                            if(!is_numeric(Input::get('page')) || Input::get('page') <= 0) {
                                Input::set('page', 1, 'get');
                            }
                            if(!is_numeric(Input::get('row')) || Input::get('row') <= 0) {
                                Input::set('row', 15, 'get');
                            }
                        }

                        $no_row = ((int)Input::get('page')-1) * Input::get('row');

                        $data = $user->usersAll($no_row, Input::get('row'));
                        $users = $data->results();

                        foreach($users as $u) {
                            $no_row++;
                            echo "\n<tr>";
                            echo '<td>'. $no_row .'</td>';
                            echo '<td><a href="user.php?id='. $u->IDHash .'">'. $u->Email  .'</a></td>';
                            echo '<td>' . $u->LastName  . '</td>';
                            echo '<td>'. $u->FirstName .'</td>';
                            echo '<td class="text-center">'. $u->LastLoginAt .'</td>';
                            echo '<td class="text-sm-center">'. ((($u->IsBlocked) == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo '<td>'. $u->BlockedTo .'</td>';
                            echo '</tr>';
                        }

                    ?>

                    </tbody>
                </table>

                <nav>
                    <ul class="pagination justify-content-center pagination-sm">
                    <?php

                        $all_row = $user->allNumerAccount()->firstResult();
                        $all_pages = ceil((int)$all_row->rows/(int)Input::get('row'));


                        // previously page
                        echo '<li class="page-item'. ((Input::get('page') == 1) ? ' disabled': '' ).'"><a class="page-link" href="allusers.php?row='. Input::get('row').'&page='. ((int)Input::get('page')-1) .'">&lt;&lt;</a></li>';

                        // button with numer of pages
                        // $start = (((Input::get('page') - 2) > 0) ? (Input::get('page') - 2) : 1);

                        if((Input::get('page') - 2) > 0) {
                            if(Input::get('page') == $all_pages) {
                                $start = Input::get('page') - 4;
                            } else if(Input::get('page') == ($all_pages-1)) {
                                $start = Input::get('page') - 3;
                            } else if($all_pages <= 5 && Input::get('page') == ($all_pages-2)) {
                                $start = Input::get('page') - 2;
                            } else {
                                $start = Input::get('page') - 2;
                            }
                            if($start <= 0) {
                                $start = 1;
                            }
                        } else {
                            $start = 1;
                        }
                        $end = (((Input::get('page') + 2) <= $all_pages) ? (((Input::get('page') + 2) <= 5) ? (($all_pages < 5) ? $all_pages : 5) : (Input::get('page') + 2)) : $all_pages);


                        for($i = $start; $i <= $end; $i++) {
                            echo '<li class="page-item'. ((Input::get('page')==$i) ? ' active' : '') .'"><a class="page-link" href="allusers.php?row='. Input::get('row') .'&page='. $i .'">'. $i .'</a></li>';
                        }

                        // next pages
                        echo '<li class="page-item'. ((Input::get('page') == $all_pages) ? ' disabled': '') .'"><a class="page-link" href="allusers.php?row='. Input::get('row').'&page='. ((int)Input::get('page')+1) .'">&gt;&gt;</a></li>';

                    ?>
                    </ul>
                </nav>

            </div>
        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>