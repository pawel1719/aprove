<?php
require_once 'core/init.php';

    $logged = new User();

    if($logged->isLogged()) {
        $logged = null;
        Redirect::to('home.php');
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
        <div class="col-1 col-md-3 col-lg-4"></div>
        <div class="col-10 col-md-6 col-lg-4">

            <button type="button" class="btn btn-light">
                <a href="index.php">Home</a>
            </button>

            <hr />

<?php

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {

            $valitation = new Validation();
            $valitation->check($_POST, array(
                'email' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 20,
                ),
                'password' => array(
                    'required' => true
                ),
                'password_again' => array(
                    'required' => true
                )
            ));

            if ($valitation->passed()) {
                $user = new User();
                try {

                    $salt = Hash::slat();
                    $user->create(array(
                        'Email' => Input::get('email'),
                        'Password' => Hash::make(Input::get('password'), $salt),
                        'Salt' => $salt,
                        'Permission' => '{"user": 1}',
                        'PasswordCreadtedAt' => date('Y-m-d H:i:s'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'UpdatedAt' => date('Y-m-d H:i:s'),
                        'IsBlocked' => 0
                    ));

                    Session::flash('registed', 'You are registed!<br/>');
                    Redirect::to('index.php');

                } catch(Exception $e) {
                    die($e->getMessage());
                }
            } else {
                // ERRORS FROM VALIDATION
                echo '<div class="alert alert-danger" role="alert">';

                foreach ($valitation->errors() as $error) {
                    echo '<p>' . $error . '</p>';
                }

                echo '</div>';
            }

        }
    }

?>


            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_again">Password again</label>
                    <input type="password" name="password_again" id="password_again" class="form-control">
                </div>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Register" class="btn btn-primary float-right">

            </form>

        </div>
        <div class="col-1 col-md-3 col-lg-4"></div>
    </div>
</div>

</BODY>
</HTML>