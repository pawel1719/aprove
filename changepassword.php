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
    <style>
        .set_center {
            margin-left: auto;
            margin-right: auto;
        }
    </style>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-2">
        <div class="col-1 col-md-3 col-lg-1"></div>
        <div class="col-10 col-md-6 col-lg-10">
            <!-- App menu -->
            <?php include_once Config::get('includes/main_menu'); ?>


<?php

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {

            $validate = new Validation();
            $validation = $validate->check($_POST, array(
                'password_current' => array(
                    'required' => true
                ),
                'password_new' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_new_again' => array(
                    'required' => true,
                    'min' => 6,
                    'matches' => 'password_new'
                )
            ));

            if($validate->passed()) {

                if(Hash::make(Input::get('password_current'), $user->data()->Salt) !== $user->data()->Password) {
                    echo '<div class="alert alert-danger" role="alert">Your current password is wrong!</div>';
                } else {
                    if($user->passwordRepeated(Input::get('password_new'), Config::get('user/can_not_use_last_passwords'))) {

                        $salt = Hash::slat();
                        $user->update(array(
                            'Password' => Hash::make(Input::get('password_new'), $salt),
                            'Salt' => $salt,
                            'PasswordCreadtedAt' => date('Y-m-d H:i:s'),
                            'UpdatedAt' => date('Y-m-d H:i:s')
                        ));
                        $user->passwordHistory(
                            Input::get('password_current'),
                            $user->data()->PasswordCreadtedAt,
                            date('Y-m-d H:i:s')
                        );

                        echo '<div class="alert alert-success" role="alert">Password changed!</div>';

                    } else {
                        echo '<div class="alert alert-danger" role="alert">Password must be diffrent than last three passwords!</div>';
                    }
                }

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


            <form action="" method="post" class="text-light mt-5 col-lg-5 set_center">

                <div class="form-group">
                    <label for="password_current">Current password</label>
                    <input type="password" name="password_current" id="password_current" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_new">New password</label>
                    <input type="password" name="password_new" id="password_new" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_new_again">New password again</label>
                    <input type="password" name="password_new_again" id="password_new_again" class="form-control">
                </div>

                <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Change" class="btn btn-primary float-right">

            </form>

        </div>
        <div class="col-1 col-md-3 col-lg-1"></div>
    </div>
</div>

</BODY>
</HTML>