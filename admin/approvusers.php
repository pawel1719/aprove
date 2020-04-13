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

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <h2>Managment user in approval!</h2>
            <br>
            <?php

                if(Input::exists()) {
                    if(Token::check(Input::get('token'))) {

                        $db = DBB::getInstance();
                        $user_hash = "'"; // values to select

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
//                            echo var_dump($users_id);
//                            echo "<br><br>";
//                            echo var_dump($agreement_id);

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

                            echo 'Users added!<hr/>';
//                            echo var_dump($users_agreement);
                        }

                    }
                }

            ?>
            <br>

            <form action="" method="post">
                <?php

                $data = $user->usersAll(0, 20);
                $users = $data->results();
                foreach($users as $u) {
                    echo '<input type="checkbox" name="'. $u->IDHash .'"> ';
                    echo $u->ID .' | ';
                    echo $u->Email .' | ';
                    echo $u->FirstName .' | ';
                    echo $u->LastName .'<br/>';
                    echo "\n";
                }

                ?>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Zapisz" class="btn btn-primary">

            </form>





        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>{