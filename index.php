<?php
require_once 'core/init.php';

if(Session::exists('registed')) {
    echo Session::flash('registed');
}

?>

<a href="register.php">Register</a>
<hr />
