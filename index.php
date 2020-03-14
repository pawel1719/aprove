<?php
require_once 'core/init.php';

    echo '<a href="register.php">Register</a><hr />';

    if(Session::exists('registed')) {
        echo Session::flash('registed');
    }

    $logged = new User();
    if($logged->isLogged()) {
        $logged = null;
        Redirect::to('home.php');
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

               if($user->data()->IsBlocked == 1) {
                   echo 'Account is blocked!';
               } else {
                   if ($login) {
                       Redirect::to('home.php');
                   } else {
                       echo '<p>Sorry, login is faild!</p>';
                   }
               }
           } else {
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