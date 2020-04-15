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



?>


<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

    <script>
        // class='table-success'>
        function add_class() {
            prompt("Działa");
            const tr = this.parentElement.classList.add("table-success");
        }
    </script>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <h2>Managment - add users to approval!</h2>
            <br>
            <?php

                if(Session::exists('success')) {
                    echo '<div class="alert alert-success">'. Session::flash('success') .'</div>';
                }

                if(Input::exists()) {
                    if(Token::check(Input::get('token'))) {

                        $db = DBB::getInstance();
                        $user_hash = "'"; // values to select

                        //IDs with form
                        foreach ($_POST as $key => $item) {
                            if($key != 'token') {
                                $user_hash .= $key . "', '";
                            }
                        }

                        // IDs users to add
                        $user_hash = substr($user_hash, 0 ,-3); //DELETE LAST  THREE CHARS
                        $users_id = $db->query("SELECT ID, IDHash FROM users WHERE IDHash IN ({$user_hash}) ORDER BY ID ASC;")->results();
                        unset($user_hash);
                        // ID agreement
                        $id_hash = Input::get('id');
                        $agreement_id = $db->query("SELECT ID, Title FROM agreements_configuration WHERE AgreementGuid = '{$id_hash}';")->firstResult();

                        if(count($users_id) == ((int)count($_POST)-1)){
                            $index = 0;
                            $users_agreement = [];

                            foreach($users_id as $one_user) {
                                if(!$db->query('SELECT * FROM `agreements` WHERE `IDagreementsConfiguration` = '. (int)$agreement_id->ID .' AND `IDUsers` = '. (int)$one_user->ID)->count()) {
                                    $users_agreement[] = array(
                                        'IDUsers' => (int)$one_user->ID,
                                        'IDagreementsConfiguration' => (int)$agreement_id->ID,
                                        'AccessGuid' => hash('sha256', $agreement_id->Title . ' ' . $one_user->ID . ' ' . date('Y-m-d H:i:s')), //dodać tytuł i imie nazwisko usera oraz date
                                        'Password' => hash('sha256', '$tring1234'),
                                        'PasswordValidity' => '2020-04-13 18:12:11',
                                        'AddedBy' => $user->data()->ID,
                                        'AddedAt' => date('Y-m-d H:i:s')
                                    );
                                }
                            }

                            foreach($users_agreement as $user_agreement) {
                                try {
                                    $db->insert('agreements', $user_agreement);
                                }catch (Exception $e) {
                                    die('#2321: Error: '. $e->getMessage());
                                }
                            }

                            Session::flash('success','Added '. (count($_POST)-1) .' users!');
                            Redirect::to('approvusers.php?id='. Input::get('id') .'&page='. Input::get('page'));
                        }

                    }
                }

            ?>
            <br>

            <div class="table-responsive">

                <form action="" method="post">

                    <table class="table table-light table-striped table-hover">
                        <thead class="thead-dark table-sm">
                        <tr>
                            <th scope="col" class="text-center">Select</th>
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
                                echo "\n<tr>\n";
                                echo '<td class="text-center"><input type="checkbox" name="'. $u->IDHash .'" onCheck="add_class();"></td>';
                                echo '<td>'.$u->ID .'</td>';
                                echo '<td>'.$u->Email .'</td>';
                                echo '<td>'.$u->FirstName .'</td>';
                                echo '<td>'.$u->LastName .'</td>';
                                echo "\n</tr>";
                            }

                            ?>

                        </tbody>
                    </table>

                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Zapisz" class="btn btn-primary">

                </form>

                <nav>
                    <ul class="pagination justify-content-center pagination-sm">

                    <?php

                        $all_row = $users_ob->allNumberAccount()->firstResult();
                        $all_pages = ceil((int)$all_row->rows/20);


                        // previously page
                        echo '<li class="page-item'. ((Input::get('page') == 1) ? ' disabled': '' ).'"><a class="page-link" href="approvusers.php?id='. Input::get('id').'&page='. ((int)Input::get('page')-1) .'">&lt;&lt;</a></li>';

                        // button with numer of pages
                        $start = (((Input::get('page') - 2) > 0) ? (Input::get('page') - 2) : 1);
                        $end = (((Input::get('page') + 2) <= $all_pages) ? (((Input::get('page') + 2) <= 5) ? (($all_pages < 5) ? $all_pages : 5) : (Input::get('page') + 2)) : $all_pages);


                        for($i = $start; $i <= $end; $i++) {
                        echo '<li class="page-item'. ((Input::get('page')==$i) ? ' active' : '') .'"><a class="page-link" href="approvusers.php?id='. Input::get('id') .'&page='. $i .'">'. $i .'</a></li>';
                        }

                        // next pages
                        echo '<li class="page-item'. ((Input::get('page') == $all_pages) ? ' disabled': '') .'"><a class="page-link" href="approvusers.php?id='. Input::get('id').'&page='. ((int)Input::get('page')+1) .'">&gt;&gt;</a></li>';

                    ?>

                    </ul>
                </nav>

            </div>





        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>{