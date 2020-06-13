<?php
require_once 'core/init.php';

    $user = new User();

    if(!$user->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('index.php');
    }

    if(!$user->hasPermission('user_user_data', 'write')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission user_user_data/write');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/main_index'); ?>

    <style>
        *, ::before, ::after {
            box-sizing: border-box;
        }
        @media all and (min-width:1200px) {
            .field-size {
                width: 14vw;
                box-sizing: border-box;
            }
        }
        @media all and (min-width:960px) and (max-width: 1199px) {
            .field-size {
                width: 24vw;
                box-sizing: border-box;
            }
        }
        .set_center {
            margin-left: auto;
            margin-right: auto;
        }
    </style>

</HEAD>
<BODY style="background-color: #59B39A">
<div class="container">
    <div class="row mt-2">
        <div class="col-1 col-md-3 col-lg-1"></div>
        <div class="col-10 col-md-6 col-lg-10">

            <!-- App menu -->
            <?php include_once Config::get('includes/main_menu'); ?>

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
                $fields = [];

                    $fields['AgreeDateOfBirth'] = (Input::get('Agreement_Date_Of_Birth') == 'on') ? 1 : 0;
                if(Input::get('Date_Of_Birth') != $user->dataDetails()->DateOfBirth) {
                    $fields['DateOfBirth']      = Input::get('Date_Of_Birth');
                }
                    $fields['AgreeCityOfBirth'] = (Input::get('Agreement_City_Of_Birth') == 'on') ? 1 : 0;
                if(Input::get('City_Of_Birth') != $user->dataDetails()->CityOfBirth) {
                    $fields['CityOfBirth']          = Input::get('City_Of_Birth');
                    $fields['CityOfBirthUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreeFirstName'] = (Input::get('Agreement_First_Name') == 'on') ? 1 : 0;
                if(Input::get('First_Name') != $user->dataDetails()->FirstName) {
                    $fields['FirstName']      = Input::get('First_Name');
                }
                    $fields['AgreeMiddleName'] = (Input::get('Agreement_Middle_Name') == 'on') ? 1 : 0;
                if(Input::get('Middle_Name') != $user->dataDetails()->MiddleName) {
                    $fields['MiddleName'] = Input::get('Middle_Name');
                }
                    $fields['AgreeLastName'] = (Input::get('Agreement_Last_Name') == 'on') ? 1 : 0;
                if(Input::get('Last_Name') != $user->dataDetails()->LastName) {
                    $fields['LastName'] = Input::get('Last_Name');
                }
                    $fields['AgreeFamilyName'] = (Input::get('Agreement_Family_Name') == 'on') ? 1 : 0;
                if(Input::get('Family_Name') != $user->dataDetails()->FamilyName) {
                    $fields['FamilyName'] = Input::get('Family_Name');
                }
                    $fields['AgreePhoneNumber'] = (Input::get('Agreement_Phone_Number') == 'on') ? 1 : 0;
                if(Input::get('Phone_Number') != $user->dataDetails()->PhoneNumber) {
                    $fields['PhoneNumber'] = Input::get('Phone_Number');
                    $fields['PhoneUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreePESEL'] = (Input::get('Agreement_PESEL') == 'on') ? 1 : 0;
                if(Input::get('PESEL') != $user->dataDetails()->PESEL) {
                    $fields['PESEL'] = Input::get('PESEL');
                    $fields['PESELUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreeIdentificationCard'] = (Input::get('Agreement_Identification_Card') == 'on') ? 1 : 0;
                if(Input::get('Identification_Card') != $user->dataDetails()->IdentificationCard) {
                    $fields['IdentificationCard'] = Input::get('Identification_Card');
                    $fields['IdentificationCardUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('Expiration_Date_Identification_Card') != $user->dataDetails()->ExpirationDateNoPersonalCard) {
                    $fields['ExpirationDateNoPersonalCard'] = Input::get('Expiration_Date_Identification_Card');
                    $fields['ExpirationDateNoPersonalCardUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreeDataLiving'] = (Input::get('Agreement_City_Of_Living') == 'on') ? 1 : 0;
                if(Input::get('City_Of_Living') != $user->dataDetails()->CityOfLiving) {
                    $fields['CityOfLiving'] = Input::get('City_Of_Living');
                    $fields['CityOfLivingUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('Street_Of_Living') != $user->dataDetails()->StreetOfLiving) {
                    $fields['StreetOfLiving'] = Input::get('Street_Of_Living');
                    $fields['StreetOfLivingUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('No_House_Of_Living') != $user->dataDetails()->NoHouseOfLiving) {
                    $fields['NoHouseOfLiving'] = Input::get('No_House_Of_Living');
                    $fields['NoHouseOfLivingUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('No_Flat_Of_Living') != $user->dataDetails()->NoFlatOfLiving) {
                    $fields['NoFlatOfLiving'] = Input::get('No_Flat_Of_Living');
                    $fields['NoFlatOfLivingUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreeDataCorrespondence'] = (Input::get('Agreement_City_Of_Correspondence') == 'on') ? 1 : 0;
                if(Input::get('City_Of_Correspondence') != $user->dataDetails()->CityOfCorrespondence) {
                    $fields['CityOfCorrespondence'] = Input::get('City_Of_Correspondence');
                    $fields['CityOfCorrespondenceUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('Street_Of_Correspondence') != $user->dataDetails()->StreetOfCorrespondence) {
                    $fields['StreetOfCorrespondence'] = Input::get('Street_Of_Correspondence');
                    $fields['StreetOfCorrespondenceUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('No_House_Of_Correspondence') != $user->dataDetails()->NoHouseOfCorrespondence) {
                    $fields['NoHouseOfCorrespondence'] = Input::get('No_House_Of_Correspondence');
                    $fields['NoHouseOfCorrespondenceUpdatedAt'] = date('Y-m-d H:i:s');
                }
                if(Input::get('No_Flat_Of_Correspondence') != $user->dataDetails()->NoFlatOfCorrespondence) {
                    $fields['NoFlatOfCorrespondence'] = Input::get('No_Flat_Of_Correspondence');
                    $fields['NoFlatOfCorrespondenceUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreeCompanyName'] = (Input::get('Agreement_Company_Name') == 'on') ? 1 : 0;
                if(Input::get('Company_Name') != $user->dataDetails()->CompanyName) {
                    $fields['CompanyName'] = Input::get('Company_Name');
                    $fields['CompanyNameUpdatedAt'] = date('Y-m-d H:i:s');
                }
                    $fields['AgreeWorkPosition'] = (Input::get('Agreement_Work_Position') == 'on') ? 1 : 0;
                if(Input::get('Work_Position') != $user->dataDetails()->WorkPosition) {
                    $fields['WorkPosition'] = Input::get('Work_Position');
                    $fields['WorkPositionUpdatedAt'] = date('Y-m-d H:i:s');
                }

                //UPDATE DATA IN DB
                $user->updateDetails($fields);

                Session::flash('update', 'Dane zaktualizowane!');
                Redirect::to('update.php');

            } catch(Exception $e) {
                Logs::addError('Cant update data detail user. Message '. $e->getMessage() .' File '. $e->getFile() .' Line ', $e->getLine());
                echo 'Nie udało sie zaktualizować danych!';
                die();
            }

        } else {
            // ERRORS FROM VALIDATION
            echo '<div class="alert alert-danger" role="alert">';

            foreach($validation->errors() as $error) {
                echo '<p>' . $error . '</p>';
            }

            echo '</div>';
            Logs::addWarning('Incorrect data to update details user.');
        }
    }
}

?>

            <form action="" method="post" class="text-light mt-5">

                <div class="form-row">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="First_Name">Imię</span>
                        </div>
                        <input type="text" name="First_Name" id="First_Name" value="<?php echo escape($user->dataDetails()->FirstName); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" name="Agreement_First_Name" id="Agreement_First_Name"<?php echo ($user->dataDetails()->AgreeFirstName == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_First_Name">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="First_Name">Drugie imię</span>
                        </div>
                        <input type="text" name="Middle_Name" id="Middle_Name" value="<?php echo escape($user->dataDetails()->MiddleName); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Middle_Name" name="Agreement_Middle_Name"<?php echo ($user->dataDetails()->AgreeMiddleName == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Middle_Name">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Last_Name">Nazwisko</span>
                        </div>
                        <input type="text" name="Last_Name" id="Last_Name" value="<?php echo escape($user->dataDetails()->LastName); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Last_Name" name="Agreement_Last_Name"<?php echo ($user->dataDetails()->AgreeLastName == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Last_Name">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="First_Name">Nazwisko rodowe</span>
                        </div>
                        <input type="text" name="Family_Name" id="Family_Name" value="<?php echo escape($user->dataDetails()->FamilyName); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Family_Name" name="Agreement_Family_Name"<?php echo ($user->dataDetails()->AgreeFamilyName == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Family_Name">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Date_Of_Birth">Data urodzenia</span>
                        </div>
                        <input type="date" name="Date_Of_Birth" id="Date_Of_Birth" value="<?php echo escape($user->dataDetails()->DateOfBirth); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Date_Of_Birth" name="Agreement_Date_Of_Birth"<?php echo ($user->dataDetails()->AgreeDateOfBirth == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Date_Of_Birth">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="City_Of_Birth">Miasto urodzenia</span>
                        </div>
                        <input type="text" name="City_Of_Birth" id="City_Of_Birth" value="<?php echo escape($user->dataDetails()->CityOfBirth); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_City_Of_Birth" name="Agreement_City_Of_Birth"<?php echo ($user->dataDetails()->AgreeCityOfBirth == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_City_Of_Birth">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Phone_Number">Telefon</span>
                        </div>
                        <input type="tel" name="Phone_Number" id="Phone_Number" value="<?php echo escape($user->dataDetails()->PhoneNumber); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Phone_Number" name="Agreement_Phone_Number"<?php echo ($user->dataDetails()->AgreePhoneNumber == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Phone_Number">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="PESEL">PESEL</span>
                        </div>
                        <input type="text" name="PESEL" id="PESEL" value="<?php echo escape($user->dataDetails()->PESEL); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_PESEL" name="Agreement_PESEL"<?php echo ($user->dataDetails()->AgreePESEL == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_PESEL">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Company_Name">Nazwa firmy</span>
                        </div>
                        <input type="text" name="Company_Name" id="Company_Name" value="<?php echo escape($user->dataDetails()->CompanyName); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Company_Name" name="Agreement_Company_Name"<?php echo ($user->dataDetails()->AgreeCompanyName == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Company_Name">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Work_Position">Stanowisko</span>
                        </div>
                        <input type="text" name="Work_Position" id="Work_Position" value="<?php echo escape($user->dataDetails()->WorkPosition); ?>" class="form-control">
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Work_Position" name="Agreement_Work_Position"<?php echo ($user->dataDetails()->AgreeWorkPosition == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Work_Position">Zgoda przetwarzania danych</label>
                    </div>
                </div>

                <!-- Identyficator card -->
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Identification_Card">Dowód osobisty</span>
                        </div>
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_Identification_Card" name="Agreement_Identification_Card"<?php echo ($user->dataDetails()->AgreeIdentificationCard == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_Identification_Card">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Identification_Card">Numer dowodu</span>
                        </div>
                        <input type="text" name="Identification_Card" id="Identification_Card" value="<?php echo escape($user->dataDetails()->IdentificationCard); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Expiration_Date_Identification_Card">Data ważności</span>
                        </div>
                        <input type="date" name="Expiration_Date_Identification_Card" id="Expiration_Date_Identification_Card" value="<?php echo escape($user->dataDetails()->ExpirationDateNoPersonalCard); ?>" class="form-control">
                    </div>
                </div>

                <!-- Data living -->
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="City_Of_Living">Adres zamieszkania</span>
                        </div>
                   </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_City_Of_Living" name="Agreement_City_Of_Living"<?php echo ($user->dataDetails()->AgreeDataLiving == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_City_Of_Living">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="City_Of_Living">Miasto</span>
                        </div>
                        <input type="text" name="City_Of_Living" id="City_Of_Living" value="<?php echo escape($user->dataDetails()->CityOfLiving); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Street_Of_Living">Ulica</span>
                        </div>
                        <input type="text" name="Street_Of_Living" id="Street_Of_Living" value="<?php echo escape($user->dataDetails()->StreetOfLiving); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="No_House_Of_Living">Numer domu</span>
                        </div>
                        <input type="text" name="No_House_Of_Living" id="No_House_Of_Living" value="<?php echo escape($user->dataDetails()->NoHouseOfLiving); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="No_Flat_Of_Living">Numer mieszkania</span>
                        </div>
                        <input type="text" name="No_Flat_Of_Living" id="No_Flat_Of_Living" value="<?php echo escape($user->dataDetails()->NoFlatOfLiving); ?>" class="form-control">
                    </div>
                </div>

                <!-- Data correspondence -->
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="City_Of_Correspondence">Adres korespondencyjny</span>
                        </div>
                    </div>
                    <div class="custom-control custom-switch col-lg-3 ml-5">
                        <input type="checkbox" class="custom-control-input" id="Agreement_City_Of_Correspondence" name="Agreement_City_Of_Correspondence"<?php echo ($user->dataDetails()->AgreeDataCorrespondence == 1) ? ' checked' : ''; ?>>
                        <label class="custom-control-label" for="Agreement_City_Of_Correspondence">Zgoda przetwarzania danych</label>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="City_Of_Correspondence">Miasto</span>
                        </div>
                        <input type="text" name="City_Of_Correspondence" id="City_Of_Correspondence" value="<?php echo escape($user->dataDetails()->CityOfCorrespondence); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="Street_Of_Correspondence">Ulica</span>
                        </div>
                        <input type="text" name="Street_Of_Correspondence" id="Street_Of_Correspondence" value="<?php echo escape($user->dataDetails()->StreetOfCorrespondence); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="No_House_Of_Correspondence">Numer domu</span>
                        </div>
                        <input type="text" name="No_House_Of_Correspondence" id="No_House_Of_Correspondence" value="<?php echo escape($user->dataDetails()->NoHouseOfCorrespondence); ?>" class="form-control">
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="input-group col-lg-7">
                        <div class="input-group-prepend">
                            <span class="input-group-text field-size" id="NoFlat_Of_Correspondence">Numer mieszkania</span>
                        </div>
                        <input type="text" name="NoFlat_Of_Correspondence" id="NoFlat_Of_Correspondence" value="<?php echo escape($user->dataDetails()->NoFlatOfCorrespondence); ?>" class="form-control">
                    </div>
                </div>

                <div class="form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Zmień" class="btn btn-primary mt-3 mb-5 set_center">
                </div>

            </form>

        </div>
        <div class="col-1 col-md-3 col-lg-1"></div>
    </div>
</div>
</BODY>
</HTML>