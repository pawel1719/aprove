<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_manage_members_approval', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_manage_members_approval/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('../home.php');
    }

    if(!Input::get('id')) {
        Session::flash('approvalmanag', 'Upss.. coś poszło nie tak!');
        Logs::addError("Incorrect address! Wrong ID.");
        Redirect::to('approvalsmanag.php');
    }

    $approvals = new Approval();
    $approval = $approvals->getApproval(array('AgreementGuid', '=', Input::get('id')));

    if(!$approval) {
        Session::flash('approvalmanag', 'Ups.. coś poszło nie tak!');
        Logs::addError("Incorrect address! Agreement dont exists.");
        Redirect::to('approvalsmanag.php');
    }

    $db = DBB::getInstance();

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

    <style>
        .spinner_container{
            width: 100vw;
            height: 100vh;
            background-color: rgba(255,255,255,0.7);
            position: absolute;
            z-index: 100;
            display: none;
        }
        .spinner_container-active{
            display: flex;
            align-items: center;
            justify-content: center;
            top: 0;
            pointer-events: none;
        }
        .spinner_container.spinner_container-active + .container{
            pointer-events: none;
        }
    </style>

</HEAD>
<BODY style="background-color: #59B39A">

    <div class="spinner_container">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">

            <h2 class="text-warning text-center text-lg-left">Zarządzaj użytkownikami do<br><u><?php echo $approval->Title .' v'. $approval->Version; ?>.0!</u></h2>
            <hr>
            <br>

            <?php

                if(Session::exists('success')) {
                    echo '<div class="alert alert-success">'. Session::flash('success') .'</div>';
                }

            ?>
            <div class="table-responsive" id="table_users">

                <form action="" method="post" name="managment_users">
                    <table class="table table-light table-striped table-hover">
                        <thead class="thead-dark table-sm">
                        <tr>
                            <?php
                                if($user->hasPermission('admin_manage_members_approval', 'write')) {
                                    echo '<th scope="col" class="text-center"><input type="checkbox" name="select_all"> Zaznacz</th>';
                                } else {
                                    echo '<th scope="col" class="text-center">Dodany</th>';
                                }
                            ?>

                            <th scope="col">Lp.</th>
                            <th scope="col">Login</th>
                            <th scope="col">Imię</th>
                            <th scope="col">Nazwisko</th>
                        </tr>
                        </thead>
                        <tbody class="table-sm small">
                            <?php

                            //Set default value if variables are wrong type
                            if(Input::exists('get')) {
                                if(!is_numeric(Input::get('page')) || Input::get('page') <= 0) {
                                    Input::set('page', 1, 'get');
                                }
                            }

                            $no_row = ((int)Input::get('page')-1) * 20;

                            $users_ob = new Users();
                            $data = $users_ob->usersToAgreements($approval->ID, $no_row, 20);
                            $users = $data->results();

                            foreach($users as $u) {
                                echo "\n<tr>\t";
                                if($user->hasPermission('admin_manage_members_approval', 'write')) {
                                    echo '<td class="text-center' . (($u->IDagreementsConfiguration != NULL) ? " table-success" : "") . '"><input type="checkbox" name="' . $u->IDHash . '" value="' . $u->ID . '"' . (($u->IDagreementsConfiguration != NULL) ? " checked" : "") . (($u->AcceptAgreement != NULL) ? " disabled" : "") . '></td>';
                                } else {
                                    echo '<td class="text-center' . (($u->IDagreementsConfiguration != NULL) ? " table-success" : "") . '">'. (($u->IDagreementsConfiguration != NULL) ? "Tak" : "Nie") .'</td>';
                                }
                                echo '<td>'.$u->ID .'</td>';
                                echo '<td>'.$u->Email .'</td>';
                                echo '<td>'.$u->FirstName .'</td>';
                                echo '<td>'.$u->LastName .'</td>';
                                echo "\t</tr>";
                            }

                            ?>

                        </tbody>
                    </table>

                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="hidden" name="agreemet" value="<?php echo $approval->ID; ?>">

                </form>

                <nav>
                    <ul class="pagination justify-content-center pagination-sm">

                    <?php

                        $all_row = $users_ob->allNumberAccount()->firstResult();
                        $all_pages = ceil((int)$all_row->rows/20);


                        // previously page
                        echo '<li class="page-item'. ((Input::get('page') == 1) ? ' disabled': '' ).'"><a class="page-link" href="approvusers.php?id='. Input::get('id') . escape('&') .'page='. ((int)Input::get('page')-1) .'">'. escape('<<') ."</a></li>\n";

                        // button with numer of pages
                        $start = (((Input::get('page') - 2) > 0) ? (Input::get('page') - 2) : 1);
                        $end = (((Input::get('page') + 2) <= $all_pages) ? (((Input::get('page') + 2) <= 5) ? (($all_pages < 5) ? $all_pages : 5) : (Input::get('page') + 2)) : $all_pages);


                        for($i = $start; $i <= $end; $i++) {
                        echo '<li class="page-item'. ((Input::get('page')==$i) ? ' active' : '') .'"><a class="page-link" href="approvusers.php?id='. Input::get('id') . escape('&page=') . $i .'">'. $i ."</a></li>\n";
                        }

                        // next pages
                        echo '<li class="page-item'. ((Input::get('page') == $all_pages) ? ' disabled': '') .'"><a class="page-link" href="approvusers.php?id='. Input::get('id') . escape('&') .'page='. ((int)Input::get('page')+1) .'">'. escape('>>') .'</a></li>';

                    ?>

                    </ul>
                </nav>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>
    <script src="../includes/JS/ajax.js"></script>
</BODY>
</HTML>