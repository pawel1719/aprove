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
        <!-- PLACE ON HEADER -->
        <?php include_once Config::get('includes/main_index'); ?>
    </HEAD>
    <BODY>
<?php

    if(Session::exists('registed')) {
        echo Session::flash('registed');
    }

    echo '<a href="register.php">Register</a><hr />';

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
                   echo '<p>Account is blocked ' . $user->data()->BlockedTo .'!</p>';
               } else {
                   if ($login) {
                       Redirect::to('home.php');
                   } else {
                       if($user->data()->IsBlocked == 0 || $blocked < $now) {
                           echo '<p>Sorry, login is faild!</p>';
                       } else {
                           echo '<p>Account is blocked! ' . $user->data()->BlockedTo .'!</p>';
                       }
                   }
               }
           } else {
               // ERRORS FROM VALIDATION
               foreach($validate->errors() as $error) {
                   echo $error . '<br/>';
               }
           }
       }
    }

?>

    <form action="" method="post">
        <div class="filed">
            <label for="email">Username</label>
            <input type="text" name="email" id="email">
        </div>
        <div class="filed">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <input type="hidden" name="token" id="'token" value="<?php echo Token::generate(); ?>">
        <input type="submit" value="Log in">
    </form>

</BODY>
</HTML>