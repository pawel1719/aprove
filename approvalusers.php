<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged() && !Token::check(Cookie::get('token')))
    {
        Logs::addError("Unauthorization access! User is not logged or is invalid token!");
        Redirect::to('index.php');
    }



    if(!Input::get('id'))
    {
        Logs::addError("Incorrect address! Wrong approval");
        if($user->isLogged()) {
            Redirect::to('home.php');
        } else {
            Redirect::to('index.php');
        }
    }

    $approval = new Approval();
    $approval_data = $approval->userApproval("WHERE a.AccessGuid = '". Input::get('id') ."' ");
//    echo var_dump($approval_data);

    if(!$approval) {
        Logs::addError("Incorrect address! Approval dont exist.");
        if($user->isLogged()) {
            Redirect::to('home.php');
        } else {
            Redirect::to('index.php');
        }
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
        <div class="col-1 col-md-2 col-lg-2">

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <?php

            if(Session::exists('agreement_update')) {
                echo '<div class="alert alert-success" role="alert">'. Session::flash('agreement_update') .'</div>';
            }

            if(Input::exists()) {
                if (Token::check(Input::get('token'))) {

                    $validation = new Validation();
                    $validation->check($_POST, array(
                        'title' => array(
                            'required' => true,
                            'min' => 5,
                            'max' => 150
                        ),
                        'content' => array(
                            'required' => true,
                            'min' => 12
                        ),
                        'start' => array(
                            'required' => true
                        ),
                        'end' => array(
                            'required' => true
                        )
                    ));


                    if ($validation->passed()) {

                        try {
                            $hash = md5(Input::get('title') . Input::get('content'));

                            $approvals->update($approval->ID, array(
                                'AgreementGuid' => $hash,
                                'Title'         => Input::get('title'),
                                'Content'       => Input::get('content'),
                                'DateStart'     => Input::get('start'),
                                'DateEnd'       => Input::get('end'),
                                'IsActived'     => Input::get('is_active'),
                                'UpdatedBy'     => $user->data()->ID
                            ));

                            //Updating HASH to agreement which one is watched
                            Input::set('approval', $hash, 'get');


                        } catch(Exception $e) {
                            echo $e->getMessage();
                            die();
                        }

                        Session::flash('agreement_update', 'Zgoda zaktualizowana!');
                        Input::destroy('title', 'content', 'start', 'end');
                        Redirect::to('approvmanag.php?approval='. $hash);

                    } else {
                        // ERRORS FROM VALIDATION
                        echo '<div class="alert alert-danger" role="alert">';

                        foreach ($validation->errors() as $error) {
                            echo '<p>' . $error . '</p>';
                        }

                        echo '</div>';
                    }

                }
            }

            ?>

            <form action="" method="post" class="text-light mt-1">

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo $approval_data[0]->Title; ?>" autocomplete="on" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-control" rows="14" readonly><?php echo $approval_data[0]->Content; ?></textarea>
                </div>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Aktualizuj zgodę" class="btn btn-primary float-right">

            </form>


        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>