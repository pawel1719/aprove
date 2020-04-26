<?php
require_once 'core/init.php';


    $user = new User();

    if(!$user->isLogged() && !Token::check(Cookie::get('token')))
    {
        Logs::addError("Unauthorization access! User is not logged or is invalid token!");
        Redirect::to('index.php');
    }



    if(!Input::exists('get'))
    {
        Logs::addError("Incorrect address! Wrong approval");
        if($user->isLogged()) {
            Redirect::to('home.php');
        } else {
            Redirect::to('index.php');
        }
    }

    $approval = new Approval();
    $id = Input::get('id');
    $approval_data = $approval->userApproval("WHERE a.AccessGuid = '{$id}' ");

    if(!$approval_data) {
        Logs::addError("Incorrect address! Approval dont exist.");
        if($user->isLogged()) {
            Redirect::to('home.php');
        } else {
            Redirect::to('index.php');
        }
    }

    // data approval
    $title = $approval_data[0]->Title;
    $body = $approval_data[0]->Content;

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/main_index'); ?>

</HEAD>
<BODY class="bg-secondary">
<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2">

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <?php

            if(Input::exists()) {
                if (Token::check(Input::get('token'))) {

                    $validation = new Validation();
                    $validation->check($_POST, array(
                        'approval_1' => array(
                            'required' => true
                        )
                    ));


                    if ($validation->passed()) {

                        $to_hash = 'Tytuł: '. Input::get('title') .".\n";
                        $to_hash = 'Treść: '. Input::get('content') .".\n";
                        $to_hash .= 'Dokument przygotowany dla: '. Input::get('name') .' '. Input::get('surname') ."\n";
                        $to_hash .= 'Zaznaczone '. (Input::get('approval_1') == 'yes' ? 'Akceptuję' : 'Nie zgadzam się') .'. Data: '. date('Y-m-d H:i:s');

                        try {
                            // saving answer user
                            $hash = hash('sha512', $to_hash);
                            $update = $approval->updateAgreement($approval_data[0]->ID_a, array(
                                'AcceptAgreement' => (Input::get('approval_1') == 'yes' ? 1 : 0),
                                'HashToAgrremnetForUser' => $hash
                            ));
                        } catch(Exception $e) {
                            Logs::addError('Cant save approval answer! Message: '. $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile());
                            echo $e->getMessage();
                        }

                        if(!$update) {

                            // data to message
                            $address = $approval_data[0]->Email;
                            $name = $approval_data[0]->FirstName .' '. $approval_data[0]->LastName;
                            $subject = 'Gratulacje! Twoja odpowiedź została zapamiętana!';
                            $body = "<HTML>
                                <HEAD>
                                    <meta charset=\"utf-8\">
                                </HEAD>
                                <BODY>
                                    <p>
                                        Dzień dobry,<br><br>
                                        Dziekujęmy za udzielnie informacji dotyczącaj \"". $approval_data[0]->Title ."\" - wersji ". $approval_data[0]->Version .".0.<br>
                                        Zawartość dokumentu jest dostępna z poziomu panelu apliakcji.
                                    </p>
                                    <p>
                                        Pozdrawaimy,<br>
                                        Approval app!
                                    </p>
                                </BODY>
                                </HTML>";

                            // create and send message
                            $mail = new Mail();
                            $mail->createMessage($address, $name, $subject, $body);
                            unset($address, $name, $subject, $body);

                            Session::flash('agreement_accept', 'Twoja decyzja została zapisana!');
                            Logs::addInformation('Save answer user!');

                            if($user->isLogged()) {
                                Redirect::to('home.php');
                            } else {
                                Redirect::to('index.php');
                            }
                        }

                    } else {
                        // ERRORS FROM VALIDATION
                        echo '<div class="alert alert-danger" role="alert">';

                        foreach ($validation->errors() as $error) {
                            echo '<p>' . $error . '</p>';
                        }

                        echo '</div>';
                    }

                }
            }

            ?>

            <form action="" method="post" class="text-light mt-1">

                <div class="form-group form-row">
                    <label for="title">Tytuł</label>
                    <input type="text" name="title" id="title" value="<?php echo $title; ?>" autocomplete="on" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="content">Treść</label>
                    <textarea name="content" id="content" class="form-control" rows="<?php echo ceil((strlen($body)/95)+(substr_count($body, "\n" ))); ?>" readonly><?php echo $body; ?></textarea>
                </div>
                <div class="form-group">
                    <input type="text" name="desc" id="desc" value="Dokument przygotowany dla" class="form-control" readonly>
                </div>
                <div class="form-group form-row">
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Imie</div>
                            </div>
                                <input type="text" name="name" id="name" value="<?php echo $approval_data[0]->FirstName; ?>" autocomplete="on" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Nazwisko</div>
                            </div>
                        <input type="text" name="surname" id="surname" value="<?php echo $approval_data[0]->LastName; ?>" autocomplete="on" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-md-4"></div>
                    <div class="col-md-5">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="approval_1" name="approval_1" value="yes" class="custom-control-input">
                            <label class="custom-control-label" for="approval_1">Akceptuję</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="approval_2" name="approval_1" value="no" class="custom-control-input" required>
                            <label class="custom-control-label" for="approval_2">Nie zgadzam się</label>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="form-group form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Zapisz" class="btn btn-primary" style="margin-left: auto; margin-right: auto;">
                </div>

            </form>


        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>
</BODY>
</HTML>