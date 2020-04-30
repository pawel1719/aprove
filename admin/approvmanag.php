<?php
    require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_list_approval', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_list_approval/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('../home.php');
    }

    if(!Input::get('approval')) {
        Session::flash('approvalmanag', 'Upss... Coś poszło nie tak!');
        Logs::addError("Incorrect address! Wrong approval");
        Redirect::to('approvalsmanag.php');
    }

    $approvals = new Approval();
    $approval = $approvals->getApproval(array('AgreementGuid', '=', Input::get('approval')));

    if(!$approval) {
        // when dont find approval
        Session::flash('approvalmanag', 'Upss... Coś poszło nie tak!');
        Logs::addError("Incorrect address! Approval dont exist.");
        Redirect::to('approvalsmanag.php');
    }

    // permission to edit
    if(!$user->hasPermission('admin_edit_approval', 'write')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_edit_approval/write');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('../home.php');
    }

    if(!Input::exists()) {
        Input::set('title', $approval->Title);
        Input::set('start', $approval->DateStart);
        Input::set('end', $approval->DateEnd);
        Input::set('content', $approval->Content);
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">

            <h2 class="text-warning text-center text-lg-left">Zmiana zgody!</h2>
            <hr>
            <br>

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
                        Logs::addWarning('Error in validation data.');
                    }

                }
            }

            ?>

            <form action="" method="post" class="text-light mt-1">

                <div class="form-group">
                    <label for="title">Tytuł</label>
                    <input type="text" name="title" id="title" value="<?php echo Input::get('title'); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-group">
                    <label for="content">Treść</label>
                    <textarea name="content" id="content" class="form-control" rows="11" placeholder="Wprowadź treść zgody   "><?php echo escape(Input::get('content')); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="start">Początek</label>
                    <input type="date" name="start" id="start" value="<?php echo escape(Input::get('start')); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-group">
                    <label for="end">Koniec</label>
                    <input type="date" name="end" id="end" value="<?php echo escape(Input::get('end')); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?php echo (($approval->IsActived) == 1) ? 'checked' : ''; ?>>
                    <label for="is_active" class="form-check-label">Aktywna</label>
                </div>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Aktualizuj zgodę" class="btn btn-primary float-right">

            </form>


        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>