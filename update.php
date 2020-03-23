<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLogged()) {
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
    <div class="row">
        <div class="col-1 col-md-3 col-lg-4"></div>
        <div class="col-10 col-md-6 col-lg-4">

<?php

if(Session::exists('update')) {
    echo '<div class="alert alert-success" role="alert">' . Session::flash('update') . '</div>';
}


if(Input::exists()) {
    if(Token::check(Input::get('token'))) {

        $validation = new Validation();
        $validate = $validation->check($_POST, array(
            'First_Name' => array(
                'min' => 2
            )
        ));

        if($validation->passed()) {

            try {
                $user->updateDetails(array(
                    'FirstName'             => Input::get('First_Name'),
                    'LastName'              => Input::get('Last_Name'),
                    'CityOfBirth'           => Input::get('City_Of_Birth'),
                    'CityOfBirthUpdatedAt'  => date('Y-m-d H:i:s')
                ));

                Session::flash('update', 'Data updating!');
                Redirect::to('update.php');

            } catch(Exception $e) {
                die($e->getMessage());
            }

        } else {
            // ERRORS FROM VALIDATION
            echo '<div class="alert alert-danger" role="alert">';

            foreach($validation->errors() as $error) {
                echo '<p>' . $error . '</p>';
            }

            echo '</div>';
        }
    }
}

?>

            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <label for="First_Name">First name</label>
                    <input type="text" name="First_Name" id="First_Name" value="<?php echo escape($user->dataDetails()->FirstName); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Last_Name">Last name</label>
                    <input type="text" name="Last_Name" id="Last_Name" value="<?php echo escape($user->dataDetails()->LastName); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Date_Of_Birth">Date of birth</label>
                    <input type="date" name="Date_Of_Birth" id="Date_Of_Birth" value="<?php echo escape($user->dataDetails()->DateOfBirth); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="City_Of_Birth">City of birth</label>
                    <input type="text" name="City_Of_Birth" id="City_Of_Birth" value="<?php echo escape($user->dataDetails()->CityOfBirth); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Phone_Number">Phone number</label>
                    <input type="tel" name="Phone_Number" id="Phone_Number" value="<?php echo escape($user->dataDetails()->PhoneNumber); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="PESEL">PESEL</label>
                    <input type="text" name="PESEL" id="PESEL" value="<?php echo escape($user->dataDetails()->PESEL); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Identification_Card">Identification card</label>
                    <input type="text" name="Identification_Card" id="Identification_Card" value="<?php echo escape($user->dataDetails()->IdentificationCard); ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="City_Of_Living">City of living</label>
                    <input type="text" name="City_Of_Living" id="City_Of_Living" value="<?php echo escape($user->dataDetails()->CityOfLiving); ?>" class="form-control">
                </div>

                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" value="Zmień" class="btn btn-primary float-right">

            </form>


        </div>
        <div class="col-1 col-md-3 col-lg-4"></div>
    </div>
</div>

</BODY>
</HTML>
