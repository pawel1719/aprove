<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_all_users', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_all_users/read');
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

            <h2 class="text-warning text-center text-lg-left">Wszyscy użytkownicy!</h2>
            <hr/>
            <br>

            <?php

                if(Session::exists('user_managment')) {
                    echo '<div class="alert alert-warning">'. Session::flash('user_managment') .'</div>';
                }

            ?>

            <div class="table-responsive">
                <table class="table table-light table-striped table-hover">
                    <thead class="thead-dark table-sm">
                    <tr>
                        <th scope="col" class="small">Nr</th>
                        <th scope="col" class="small">Login</th>
                        <th scope="col" class="small">Nazwisko</th>
                        <th scope="col" class="small">Imię</th>
                        <th scope="col" class="small">Ostatnie logowanie</th>
                        <th scope="col" class="small">Blokada</th>
                        <th scope="col" class="small">Blokada do</th>
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

                        $users_ob = new Users();
                        $data = $users_ob->usersAll($no_row, Input::get('row'));
                        $users = $data->results();

                        foreach($users as $u) {
                            $no_row++;
                            echo "\n<tr>";
                            echo '<td class="small">'. $no_row .'</td>';
                            echo '<td class="small"><a href="user.php?id='. $u->IDHash .'">'. $u->Email  .'</a></td>';
                            echo '<td class="small">' . $u->LastName  . '</td>';
                            echo '<td class="small">'. $u->FirstName .'</td>';
                            echo '<td class="text-center small">'. $u->LastLoginAt .'</td>';
                            echo '<td class="text-sm-center small">'. ((($u->IsBlocked) == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo '<td class="small">'. $u->BlockedTo .'</td>';
                            echo '</tr>';
                        }

                    ?>

                    </tbody>
                </table>

                <nav>
                    <ul class="pagination justify-content-center pagination-sm">
                    <?php

                        $all_row = $users_ob->allNumberAccount()->firstResult();
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
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>