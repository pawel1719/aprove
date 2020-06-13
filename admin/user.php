<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$user->hasPermission('admin_user_data', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_user_data/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('../home.php');
    }

    if(!Input::get('id')) {
        Session::flash('user_managment', 'Upss... coś poszło nie tak!');
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
<BODY style="background-color: #59B39A">

<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-2">

            <?php include_once Config::get('includes/second_admin_menu'); ?>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">
            <h2 class="text-warning text-center text-lg-left">Zarządzaj użytkownikiem!</h2>
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
                            echo '<th scope="row" class="small">Imię</th><td class="small"><input type="text" name="FirstName" value="'. $user_details->FirstName .'"></td>';
                            echo '<th scope="row" class="small">Nazwisko</th><td class="small"><input type="text" name="LastName" value="'. $user_details->LastName .'"></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Hasło utworzono</th><td class="small">'. $single_user->PasswordCreadtedAt .'</td>';
                            echo '<th scope="row" class="small">Permission</th><td class="small"><select name="Permission" id="'. $single_user->Permission .'"><!-- options --></select></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Ostatnie logowanie</th><td class="small">'. $single_user->LastLoginAt .'</td>';
                            echo '<th scope="row" class="small">Utworzono</th><td class="small">'. $single_user->CreatedAt .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Zaktualizowano</th><td class="small">'. $single_user->UpdatedAt .'</td>';
                            echo '<th scope="row" class="small">Zablokowane</th><td class="small"><select name="IsBlocked">'. (($single_user->IsBlocked == '1') ? '<option value="1" selected>Tak</option><option value="0">Nie</option>' : '<option value="0" selected>Nie</option><option value="1">Tak</option>').'</select></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Zablokowano o</th><td class="small">'. $single_user->BlockedAt .'</td>';
                            echo '<th scope="row" class="small">Blokada do</th><td class="small">'. $single_user->BlockedTo .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" colspan="4">Liczba logowań użytkownika</th>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Poprawne</th><td class="small">'. $single_user->CounterCorrectLogin .'</td>';
                            echo '<th scope="row" class="small">Błędne</th><td class="small">'. $single_user->CounterIncorretLogin .'</td>';
                            echo "</tr>\n<tr>";
                            // PERSONAL DATA
                            echo '<th scope="row" colspan="4">Dane osobowe</th>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Płeć</th><td class="small" colspan="3">'. (($user_details->Gender == '0') ? 'Kobieta' : 'Mężczyzna') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Data urodzenia</th><td class="small"><input type="date" name="DateOfBirth" value="'. $user_details->DateOfBirth .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeDateOfBirth == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Miasto urodzenia</th><td class="small"><input type="text" name="CityOfBirth" value="'. $user_details->CityOfBirth .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeCityOfBirth == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Drugie imię</th><td class="small"><input type="text" name="MiddleName" value="'. $user_details->MiddleName .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeMiddleName == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Nazwisko rodowe</th><td class="small"><input type="text" name="FamilyName" value="'. $user_details->FamilyName .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeFamilyName == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Telefon</th><td class="small"><input type="text" name="PhoneNumber" value="'. $user_details->PhoneNumber .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreePhoneNumber == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">PESEL</th><td class="small"><input type="text" name="PESEL" value="'. $user_details->PESEL .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreePESEL == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            // COMPANY AND WORK POSITION
                            echo '<th scope="row" class="small">Firma</th><td class="small"><input type="text" name="CompanyName" value="'. $user_details->CompanyName .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeCompanyName == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Stanowisko</th><td class="small"><input type="text" name="WorkPosition" value="'. $user_details->WorkPosition .'"></td>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeWorkPosition == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            // ID CARD
                            echo '<th scope="row" colspan="2">Dane dowodu osobistego</th>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeIdentificationCard == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Numer dowodu</th><td class="small"><input type="text" name="IdentificationCard" value="'. $user_details->IdentificationCard .'"></td>';
                            echo '<th scope="row" class="small">Data wyażności</th><td class="small"><input type="date" name="ExpirationDateNoPersonalCard" value="'. $user_details->ExpirationDateNoPersonalCard .'"></td>';
                            echo "</tr>\n<tr>";
                            // LIVING ADDRESS
                            echo '<th scope="row" colspan="2">Adres zamieszkania</th>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeDataLiving == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Miasto</th><td class="small"><input type="text" name="CityOfLiving" value="'. $user_details->CityOfLiving .'"></td>';
                            echo '<th scope="row" class="small">Ulica</th><td class="small"><input type="text" name="StreetOfLiving" value="'. $user_details->StreetOfLiving .'"></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Nr domu</th><td class="small"><input type="text" name="NoHouseOfLiving" value="'. $user_details->NoHouseOfLiving .'"></td>';
                            echo '<th scope="row" class="small">Nr mieszkania</th><td class="small"><input type="text" name="NoFlatOfLiving" value="'. $user_details->NoFlatOfLiving .'"></td>';
                            echo "</tr>\n<tr>";
                            // CORESPONDENCE ADDRESS
                            echo '<th scope="row" colspan="2">Adres korespondencyjny</th>';
                            echo '<th scope="row" class="small">Zgoda</th><td class="small">'. (($user_details->AgreeDataCorrespondence == 1) ? 'Tak' : 'Nie') .'</td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Miasto</th><td class="small"><input type="text" name="CityOfCorrespondence" value="'. $user_details->CityOfCorrespondence .'"></td>';
                            echo '<th scope="row" class="small">Ulica</th><td class="small"><input type="text" name="StreetOfCorrespondence" value="'. $user_details->StreetOfCorrespondence .'"></td>';
                            echo "</tr>\n<tr>";
                            echo '<th scope="row" class="small">Nr domu</th><td class="small"><input type="text" name="NoHouseOfCorrespondence" value="'. $user_details->NoHouseOfCorrespondence .'"></td>';
                            echo '<th scope="row" class="small">Nr mieszkania</th><td class="small"><input type="text" name="NoFlatOfCorrespondence" value="'. $user_details->NoFlatOfCorrespondence .'"></td>';
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
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>

    <!-- Ajax function -->
    <script src="../includes/JS/ajax_user.js"></script>

</BODY>
</HTML>