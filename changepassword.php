<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Redirect::to('index.php');
    }

    if(!$user->hasPermission('user_change_password', 'write')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission user_change_password/write');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
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
<BODY style="background-color: #59B39A">
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
                'nowe_haslo' => array(
                    'required' => true,
                    'min' => 6,
                    'strongPassword' => true
                ),
                'ponownie_nowe_haslo' => array(
                    'required' => true,
                    'min' => 6,
                    'matches' => 'nowe_haslo'
                )
            ));

            if($validate->passed()) {

                if(Hash::make(Input::get('password_current'), $user->data()->Salt) !== $user->data()->Password) {
                    echo '<div class="alert alert-danger" role="alert">Aktualne hasło jest nie poprawne!</div>';
                    Logs::addWarning('Current password is wrong.');
                } else {
                    if($user->passwordRepeated(Input::get('nowe_haslo'), Config::get('user/can_not_use_last_passwords'))) {

                        $salt = Hash::slat();
                        $user->update(array(
                            'Password' => Hash::make(Input::get('nowe_haslo'), $salt),
                            'Salt' => $salt,
                            'PasswordCreadtedAt' => date('Y-m-d H:i:s'),
                            'UpdatedAt' => date('Y-m-d H:i:s')
                        ));
                        $user->passwordHistory(
                            Input::get('password_current'),
                            $user->data()->PasswordCreadtedAt,
                            date('Y-m-d H:i:s')
                        );

                        echo '<div class="alert alert-success" role="alert">Hasło zmienione!</div>';
                        Logs::addInformation('Password was changed.');

                    } else {
                        echo '<div class="alert alert-danger" role="alert">Hasło musi się różnić od trzech ostanich!</div>';
                        Logs::addWarning('Password have to be different than last three.');
                    }
                }

            } else {
                // ERRORS FROM VALIDATION
                echo '<div class="alert alert-danger" role="alert">';

                foreach($validate->errors() as $error) {
                    echo '<p>' . $error . '</p>';
                }

                echo '</div>';
                Logs::addWarning('Incorrect passwords.');
            }
        }
    }

?>
            <form action="" method="post" class="text-light mt-5 col-lg-5 set_center">

                <div class="form-group">
                    <label for="password_current">Aktualne hasło</label>
                    <input type="password" name="password_current" id="password_current" class="form-control">
                </div>
                <div class="form-group">
                    <label for="nowe_haslo">Nowe hasło</label>
                    <input type="password" name="nowe_haslo" id="nowe_haslo" class="form-control">
                </div>
                <div class="form-group">
                    <label for="ponownie_nowe_haslo">Powtórz nowe hasło</label>
                    <input type="password" name="ponownie_nowe_haslo" id="ponownie_nowe_haslo" class="form-control">
                </div>

                <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Zmień" class="btn btn-primary float-right">

            </form>

        </div>
        <div class="col-1 col-md-3 col-lg-1"></div>
    </div>
</div>
</BODY>
</HTML>