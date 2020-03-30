<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Redirect::to('index.php');
}

?>


<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/main_index'); ?>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2"></div>
        <div class="col-10 col-md-8 col-lg-8">

            <!-- App menu -->
            <?php include_once Config::get('includes/main_menu'); ?>

            <?php

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
                            <p>Best wishes<br/>Aproval app!<br/>Created by Paweł Szóstkiewicz</p>
                       </BODY>
                       </HTML>
                       ';

                       $mail = new Mail(true);
                       $mail->createMessage(Input::get('mail_to'), 'Recipiest', Input::get('subject'), $HTML_MESSAGE);

                       Input::destroy('mail_to,subject,mail_body');

                       echo '<div class="alert alert-success" role="alert">Send message</div>';

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
                    <label for="mail_to">To:</label>
                    <input type="email" name="mail_to" id="mail_to" class="form-control" value="<?php echo escape(Input::get('mail_to')); ?>">
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" value="<?php echo escape(Input::get('subject')); ?>">
                </div>
                <div class="form-group">
                    <label for="mail_body">Body</label>
                    <textarea name="mail_body" id="mail_body" class="form-control" rows="7" placeholder="Wprowadź tekst"><?php echo escape(Input::get('mail_body')); ?></textarea>
                </div>

                <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Send" class="btn btn-primary float-right">

            </form>

        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>