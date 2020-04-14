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

//    echo '<a href="register.php">Register</a><hr />';

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

               $blocked = new DateTime($user->data()->BlockedTo);
               $now     = new DateTime('now');

               if($user->data()->InvalidAttemptCounter >= Config::get('user/number_failed_login_attempts')-1 &&
                   $user->data()->BlockedTo < date('Y-m-d H:m:s')) {
                   echo '<div class="alert alert-danger" role="alert">Account is blocked !</div>';
               } else {
                   if ($login) {
                       Redirect::to('home.php');
                   } else {
                       if($user->data()->IsBlocked == 0 || $blocked < $now) {
                           echo '<div class="alert alert-danger" role="alert">Sorry, login is faild!</div>';
                       } else {
                           echo '<div class="alert alert-danger" role="alert">Account is blocked! !</div>';
                       }
                   }
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
           }

       }
    }

?>

                <form action="" method="post" class="text-light mt-5">

                    <div class="form-group">
                        <label for="email">Username</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <input type="hidden" name="token" id="'token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Log in" class="btn btn-primary float-right">

                </form>

            </div>
            <div class="col-1 col-md-3 col-lg-4"></div>
        </div>
    </div>

</BODY>
</HTML>