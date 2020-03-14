<?php
require_once 'core/init.php';

    $logged = new User();

    if($logged->isLogged()) {
        $logged = null;
        Redirect::to('home.php');
    }


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
                foreach ($valitation->errors() as $error) {
                    echo $error . '<br/>';
                }
            }
        }
    }
?>


<a href="index.php">Home</a>
<hr />

<form action="" method="post">
    <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php echo escape(Input::get('email')); ?>" autocomplete="on">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
        <label for="password_again">Password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

    <input type="submit" value="Register">
</form>