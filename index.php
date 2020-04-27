<?php
require_once 'core/init.php';

    $logged = new User();
    if($logged->isLogged()) {
        $logged = null;
        Logs::addError("Unauthorization access!");
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

<?php

    Logs::addInformation('Visited page!');

    if(Session::exists('registed')) {
        echo Session::flash('registed');
    }

    if(Input::exists()) {
       if(Token::check(Input::get('token'))) {

           $validation = new Validation();
           $validate = $validation->check($_POST, array(
                   'email' => array('required' => true),
                   'password' => array('required' => true)
           ));

           if($validate->passed()) {
               $user = new User();
               $login = $user->login(Input::get('email'), Input::get('password'));

               if((int)$user->data()->IsBlocked != 1) {
                   if($login) {
                       echo '<div class="alert alert-success" role="alert">zalogowane</div>';
                       Logs::addInformation('Success login!');
                       // header( "refresh: 3; url=logout.php" );
                       Redirect::to('home.php');
                   } else {
                       if(((int)$user->data()->InvalidAttemptCounter + 1) >= Config::get('user/number_failed_login_attempts')) {
                            echo '<div class="alert alert-danger" role="alert">Konto zostało zablokowane!</div>';
                            Logs::addWarning('Account is blocking.');
                       } else {
                            echo '<div class="alert alert-danger" role="alert">Niepoprawny login lub hasło</div>';
                            Logs::addWarning('Incorrect login or password! Account is not blocked.');
                       }
                   }
               } else {
                   // when account was blocked
                   if($login) {
                       Logs::addInformation('Unlocked account and success login!');
                       Redirect::to('home.php');
                   }
                   echo '<div class="alert alert-danger" role="alert">Konto jest zablokowane!</div>';
               }

           } else {
               // Save information to file with logs
               Logs::addWarning('Invalid attempt login! User: '. Input::get('email') .'. ');

               // ERRORS FROM VALIDATION
               echo '<div class="alert alert-danger" role="alert">';

               foreach($validate->errors() as $error) {
                   echo '<p>' . $error . '</p>';
               }

               echo '</div>';
               Logs::addWarning('Incorect login or password.');
           }

       }
    }

?>


                <form action="" method="post" class="text-light mt-5">

                    <div class="form-group">
                        <label for="email">Login</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <input type="hidden" name="token" id="'token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Zaloguj" class="btn btn-primary float-right">

                </form>

            </div>
            <div class="col-1 col-md-3 col-lg-4"></div>
        </div>
    </div>

</BODY>
</HTML>