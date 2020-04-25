<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!Input::get('id')) {
        Session::flash('user_managment', 'Something went wrong!');
        Logs::addError("Incorrect address! Wrong ID.");
        Redirect::to('allusers.php');
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

    <style>
        .message_box{
            display: none;
        }

        .message_box-active{
            display: block;
        }
    </style>

</HEAD>
<BODY class="bg-secondary">

<div class="container">
    <div class="row mt-5">
        <div class="col-1 col-md-2 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-10 col-md-8 col-lg-8">
            <h2>Managment user!</h2>
            <hr/>
            <div class="alert alert-success message_box"></div>
            <br>

            <div id="element_id" class="table-responsive">
                <form action="" method="post">
                    <table class="table table-dark table-striped table-hover">
                        <tbody class="table-sm">
                        <?php

                            $users = new User(Input::get('id'));
                            $single_user = $users->data();
                            $user_details = $users->dataDetails();


                            echo '<tr>';
                            echo '<th scope="row">Email</th><td colspan="3">'. $single_user->Email .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Imię</th><td><input type="text" name="FirstName" value="'. $user_details->FirstName .'"></td>';
                            echo '<th scope="row">Nazwisko</th><td><input type="text" name="LastName" value="'. $user_details->LastName .'"></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Hasło utworzono</th><td>'. $single_user->PasswordCreadtedAt .'</td>';
                            echo '<th scope="row">Permission</th><td><select name="Permission" id="'. $single_user->Permission .'"><!-- options --></select></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Ostatnie logowanie</th><td>'. $single_user->LastLoginAt .'</td>';
                            echo '<th scope="row">Utworzono</th><td>'. $single_user->CreatedAt .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Zaktualizowano</th><td>'. $single_user->UpdatedAt .'</td>';
                            echo '<th scope="row">Zablokowane</th><td><select name="IsBlocked">'. (($single_user->IsBlocked == '1') ? '<option value="1" selected>Tak</option><option value="0">Nie</option>' : '<option value="0" selected>Nie</option><option value="1">Tak</option>').'</select></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Zablokowano o</th><td>'. $single_user->BlockedAt .'</td>';
                            echo '<th scope="row">Blokada do</th><td>'. $single_user->BlockedTo .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" colspan="4">Liczba logowań użytkownika</th>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Poprawne</th><td>'. $single_user->CounterCorrectLogin .'</td>';
                            echo '<th scope="row">Błędne</th><td>'. $single_user->CounterIncorretLogin .'</td>';
                            echo "</tr>\n<tr>";
                            // PERSONAL DATA
                            echo '<th scope="row" colspan="4">Dane osobowe</th>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Płeć</th><td colspan="3">'. (($user_details->Gender == '0') ? 'Kobieta' : 'Mężczyzna') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Data urodzenia</th><td><input type="date" name="DateOfBirth" value="'. $user_details->DateOfBirth .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeDateOfBirth == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Miasto urodzenia</th><td><input type="text" name="CityOfBirth" value="'. $user_details->CityOfBirth .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeCityOfBirth == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Drugie imię</th><td><input type="text" name="MiddleName" value="'. $user_details->MiddleName .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeMiddleName == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Nazwisko rodowe</th><td><input type="text" name="FamilyName" value="'. $user_details->FamilyName .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeFamilyName == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Telefon</th><td><input type="text" name="PhoneNumber" value="'. $user_details->PhoneNumber .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreePhoneNumber == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">PESEL</th><td><input type="text" name="PESEL" value="'. $user_details->PESEL .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreePESEL == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            // COMPANY AND WORK POSITION
                            echo '<th scope="row">Firma</th><td><input type="text" name="CompanyName" value="'. $user_details->CompanyName .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeCompanyName == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Stanowisko</th><td><input type="text" name="WorkPosition" value="'. $user_details->WorkPosition .'"></td>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeWorkPosition == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            // ID CARD
                            echo '<th scope="row" colspan="2">Dane dowodu osobistego</th>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeIdentificationCard == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Numer dowodu</th><td><input type="text" name="IdentificationCard" value="'. $user_details->IdentificationCard .'"></td>';
                            echo '<th scope="row">Data wyażności</th><td><input type="date" name="ExpirationDateNoPersonalCard" value="'. $user_details->ExpirationDateNoPersonalCard .'"></td>';
                            echo "</tr>\n<tr>";
                            // LIVING ADDRESS
                            echo '<th scope="row" colspan="2">Adres zamieszkania</th>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeDataLiving == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Miasto</th><td><input type="text" name="CityOfLiving" value="'. $user_details->CityOfLiving .'"></td>';
                            echo '<th scope="row">Ulica</th><td><input type="text" name="StreetOfLiving" value="'. $user_details->StreetOfLiving .'"></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Nr domu</th><td><input type="text" name="NoHouseOfLiving" value="'. $user_details->NoHouseOfLiving .'"></td>';
                            echo '<th scope="row">Nr mieszkania</th><td><input type="text" name="NoFlatOfLiving" value="'. $user_details->NoFlatOfLiving .'"></td>';
                            echo "</tr>\n<tr>";
                            // CORESPONDENCE ADDRESS
                            echo '<th scope="row" colspan="2">Adres korespondencyjny</th>';
                            echo '<th scope="row">Zgoda</th><td>'. (($user_details->AgreeDataCorrespondence == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Miasto</th><td><input type="text" name="CityOfCorrespondence" value="'. $user_details->CityOfCorrespondence .'"></td>';
                            echo '<th scope="row">Ulica</th><td><input type="text" name="StreetOfCorrespondence" value="'. $user_details->StreetOfCorrespondence .'"></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row">Nr domu</th><td><input type="text" name="NoHouseOfCorrespondence" value="'. $user_details->NoHouseOfCorrespondence .'"></td>';
                            echo '<th scope="row">Nr mieszkania</th><td><input type="text" name="NoFlatOfCorrespondence" value="'. $user_details->NoFlatOfCorrespondence .'"></td>';
                            echo "</tr>\n<tr>";
                            echo "</tr>\n";
                        ?>
                        </tbody>
                    </table>
                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                            <input type="hidden" name="user" value="<?php echo $single_user->ID; ?>">
                </form>
            </div>
        </div>
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

    <!-- Ajax function -->
    <script src="../includes/JS/ajax_user.js"></script>

</BODY>
</HTML>