<?php
require_once '../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Redirect::to('../index.php');
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
        <div class="col-1 col-md-2 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-10 col-md-8 col-lg-8">

            <h2>Adding new agreements!</h2>

            <?php

                if(Session::exists('agreement')) {
                    echo Session::flash('agreement');
                }

                if(Input::exists()) {
                    if (Token::check(Input::get('token'))) {

                        $validation = new Validation();
                        $validation->check($_POST, array(
                            'title' => array(
                                'required' => true,
                                'min' => 5,
                                'max' => 150)
//                            ),
//                            'content' => array(
//                                'required' => true,
//                                'min' => 12
//                            ),
//                            'start' => array(
//                                'required' => true
//                            ),
//                            'end' => array(
//                                'required' => true
//                            )
                        ));

                        if ($validation->passed()) {

                            try {

                                $version = 0;
                                $db = DBB::getInstance();
                                $title = $db->query('SELECT * FROM agreements_configuration WHERE Title = "' . escape(Input::get('title')) . '" ORDER BY CreateAt DESC');

                                if($title->count()) {
                                    $version = (int)$title->firstResult()->Version;
                                }

                                $db->insert('agreements_configuration', array(
                                    'AgreementGuid' => md5(Input::get('title') . Input::get('title')),
                                    'Title' => escape(Input::get('title')),
                                    'Content' => escape(nl2br(Input::get('content'))),
                                    'Version' => 1 + $version,
                                    'DateStart' => Input::get('start'),
                                    'DateEnd' => Input::get('end'),
                                    'IsActived' => (Input::get('is_active') == 'on') ? 1 : 0,
                                    'CreatedBy' => $user->data()->ID,
                                    'CreateAt' => date('Y-m-d H:i:s')
                                ));

                            } catch(Exception $e) {
                                echo $e->getMessage();
                                die();
                            }

                            Session::flash('agreement', '<div class="alert alert-success" role="alert">Zgoda dodana!</div>');
                            Input::destroy('title', 'content', 'start', 'end');
                            Redirect::to('approvalsadd.php');

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

            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo escape(Input::get('title')); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-control" rows="9" placeholder="Wprowadź treść zgody   "><?php echo escape(Input::get('content')); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="start">Data start</label>
                    <input type="date" name="start" id="start" value="<?php echo escape(Input::get('start')); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-group">
                    <label for="end">Date end</label>
                    <input type="date" name="end" id="end" value="<?php echo escape(Input::get('end')); ?>" autocomplete="on" class="form-control">
                </div>
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input">
                    <label for="is_active" class="form-check-label">Is active</label>
                </div>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Dodaj zgodę" class="btn btn-primary float-right">

            </form>

        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>