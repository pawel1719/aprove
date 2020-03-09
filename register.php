<?php
require_once 'core/init.php';

if(Input::exists()) {
    $valitation = new Validation();
    $valitation->check($_POST, array(
        'username' => array(
            'required' => true,
            'min' => 2,
            'max' => 10,
            'matches' => 'name',
        ),
        'password' => array(
            'required' => true
        ),
        'password_again' => array(
            'required' => true
        ),
        'name' => array(
            'required' => true
        )
    ));

    if($valitation->passed()) {
        echo 'Passed!';
    } else {
        foreach($valitation->errors() as $error) {
            echo $error .'<br/>';
        }
    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>
    <div class="field">
        <label for="password_again">Password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>
    <div class="field">
        <label for="name">Username</label>
        <input type="text" name="name" id="name" value="<?php echo escape(Input::get('name')); ?>">
    </div>

    <input type="submit" value="Register">
</form>