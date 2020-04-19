<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!Input::get('id')) {
        Session::flash('approvalmanag', 'Something went wrong!');
        Logs::addError("Incorrect address! Wrong ID.");
        Redirect::to('approvalsmanag.php');
    }

    $approvals = new Approval();
    $approval = $approvals->getApproval(array('AgreementGuid', '=', Input::get('id')));

    if(!$approval) {
        Session::flash('approvalmanag', 'Something went wrong!');
        Logs::addError("Incorrect address! Agreement dont exists.");
        Redirect::to('approvalsmanag.php');
    }

    $db = DBB::getInstance();

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

            <h2>Managment users to <?php echo $approval->Title .' v'. $approval->Version; ?>.0!</h2>
            <br>
            <div class="spinner-border" role="status" style="display: none;">
                <span class="sr-only">Loading...</span>
            </div>
            <br>
            <?php

                if(Session::exists('success')) {
                    echo '<div class="alert alert-success">'. Session::flash('success') .'</div>';
                }

            ?>
            <br>

            <div class="table-responsive" id="table_users">

                <form action="" method="post" name="managment_users">

                    <table class="table table-light table-striped table-hover">
                        <thead class="thead-dark table-sm">
                        <tr>
                            <th scope="col" class="text-center"><input type="checkbox" name="select_all">Select</th>
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
                                echo '<td class="text-center'. (($u->IDagreementsConfiguration != NULL) ? " table-success" : "") .'"><input type="checkbox" name="'. $u->IDHash .'" value="'. $u->ID .'"'. (($u->IDagreementsConfiguration != NULL) ? " checked" : "") . (($u->AcceptAgreement != NULL) ? " disabled" : "") .'></td>';
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
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>
    <script src="../includes/JS/ajax.js"></script>
</BODY>
</HTML>