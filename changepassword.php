<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Redirect::to('index.php');
    }

    echo '<a href="home.php">Home</a><hr/>';


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
                    echo 'Your current password is wrong!';
                } else {
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

                    echo '<p>Password changed!</p>';
                }

            } else {
                foreach($validate->errors() as $error) {
                    echo $error . '<br/>';
                }
                echo '<br/>';
            }
        }
    }

?>


<form action="" method="post">
    <div class="field">
        <label for="password_current">Current password</label>
        <input type="password" name="password_current" id="password_current">
    </div>
    <div class="field">
        <label for="password_new">New password</label>
        <input type="password" name="password_new" id="password_new">
    </div>
    <div class="field">
        <label for="password_new_again">New password again</label>
        <input type="password" name="password_new_again" id="password_new_again">
    </div>

    <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Change">
</form>
