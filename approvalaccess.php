<?php
require_once 'core/init.php';

    $logged = new User();
    $approval = new Approval();
    $db = DBB::getInstance();

    if($logged->isLogged())
    {
        Logs::addError("Unauthorized access!");
//        Redirect::to('home.php');
    }

    if(Input::exists('get'))
    {
        $access = escape(Input::get('access'));
        $approval_data = $approval->userApproval("WHERE a.AccessGuid = '{$access}'");

        //when access hash dont exist in db
        if($approval_data == false)
        {
            Logs::addError('Unauthorized access - incorrect access variable! Access: '. Input::get('access'));
            Redirect::to('index.php');
        }
    } else {
        // errors
        Logs::addError("Unauthorized access - missing access guid!");
        Redirect::to('index.php');
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

            if(Session::exists('error'))
            {
                echo '<div class="alert alert-danger" role="alert">'. Session::flash('error') .'</div>';
            }

            if(Input::exists())
            {
                if(Token::check(Input::get('token')))
                {
                    $validation = new Validation();
                    $validate = $validation->check($_POST, array(
                        'login' => array('required' => true),
                        'password' => array('required' => true)
                    ));

                    if($validate->passed()) {
                        if($approval_data[0]->Email == Input::get('login') && $approval_data->Password == hash('sha256', Input::get('password')))
                        {
                            // success login
                            Cookie::put('token', Token::generate(), 5);
                            Redirect::to('approvalusers.php?id='. $approval_data->AccessGuid);

                        } else {
                            // incorrect login
                            Logs::addError('Incorrect login '. Input::get('login'));
                            Session::flash('error', 'Sorry, incorrect login or password!');
                            Redirect::to('approvalaccess.php?access='. Input::get('access'));
                        }
                    } else {
                        // Save information to file with logs
                        Logs::addWarning('Invalid attempt login! User: '. Input::get('email') .'. ');

                        // ERRORS FROM VALIDATION
                        echo '<div class="alert alert-danger" role="alert">';

                        foreach($validate->errors() as $error)
                        {
                            echo '<p>' . $error . '</p>';
                        }
                        echo '</div>';
                    }
                }
            }

            ?>

            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <label for="login">Username</label>
                    <input type="text" name="login" id="login" class="form-control">
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