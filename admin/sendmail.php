<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('index.php');
    }
    if(!$user->hasPermission('admin_send_mail', 'write')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_send_mail/write');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
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

            <!-- App menu -->
            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">

            <h2 class="text-warning text-center text-lg-left">Wyślij maila!</h2>
            <hr>

            <?php

            if(Session::exists('success')) {
                echo '<div class="alert alert-success" role="alert">'. Session::flash('success') .'</div>';
            }
            if(Session::exists('error')) {
                echo '<div class="alert alert-error" role="alert">'. Session::flash('success') .'</div>';
            }

            if(Input::exists()) {
                if(Token::check(Input::get('token'))) {

                    $validate = new Validation();
                    $validation = $validate->check($_POST, array(
                        'mail_to' => array(
                            'required' => true
                        )
                    ));

                    if($validate->passed()) {

                       $HTML_MESSAGE = '
                       <HTML>
                       <HEAD></HEAD>
                       <BODY>
                            <p>' . nl2br(Input::get('mail_body')) . '</p>
                            <br>
                            <p>Best wishes<br/>Aproval app!<br/>Created by Paweł Szóstkiewicz</p>
                       </BODY>
                       </HTML>
                       ';

                        try {
                            //send mail
                            $mail = new Mail(true);
                            $mail->createMessage(Input::get('mail_to'), 'Recipiest', Input::get('subject'), $HTML_MESSAGE);
                        }catch(Exception $e) {
                            Logs::addError('Cant send mail from admin panel!');
                            Session::flash('error', 'Nie można wysłać wiadomości!');
                            Redirect::to('sendmail.php');
                        }

                        Input::destroy('mail_to,subject,mail_body');
                        Session::flash('success', 'Wiadomość wysłana!');
                        Logs::addInformation('Wiadomość została wysłana.');
                        Redirect::to('sendmail.php');

                    } else {
                        // ERRORS FROM VALIDATION
                        echo '<div class="alert alert-danger" role="alert">';

                        foreach($validate->errors() as $error) {
                            echo '<p>' . $error . '</p>';
                        }

                        echo '</div>';
                    }
                }
            }

            ?>


            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <label for="mail_to">Do:</label>
                    <input type="email" name="mail_to" id="mail_to" class="form-control" value="<?php echo escape(Input::get('mail_to')); ?>">
                </div>
                <div class="form-group">
                    <label for="subject">Temat</label>
                    <input type="text" name="subject" id="subject" class="form-control" value="<?php echo escape(Input::get('subject')); ?>">
                </div>
                <div class="form-group">
                    <label for="mail_body">Treść</label>
                    <textarea name="mail_body" id="mail_body" class="form-control" rows="7" placeholder="Wprowadź tekst"><?php echo escape(Input::get('mail_body')); ?></textarea>
                </div>

                <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Wyślij" class="btn btn-primary float-right">

            </form>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>