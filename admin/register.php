<?php
require_once '../core/init.php';

    $logged = new User();

    if(!$logged->isLogged()) {
        Logs::addError("Unauthorization access!");
        Redirect::to('../index.php');
    }

    if(!$logged->hasPermission('admin_add_users', 'write')) {
        Logs::addError('User '. $logged->data()->ID .' dont have permission to this page! Permission admin_add_users/write');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('../home.php');
    }

?>
<!DOCTYPE html>
<HTML>
<HEAD>

    <?php include_once Config::get('includes/second_index'); ?>

</HEAD>
<BODY style="background-color: #59B39A">

<div class="container">
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-2">
            <?php include_once Config::get('includes/second_admin_menu'); ?>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-8">

        <h3 class="text-warning text-center text-lg-left">Dodaj nowego użytkownika!</h3>
        <hr/>

<?php

    if(Session::exists('error')) {
        echo '<div class="alert alert-danger" role="alert">'. Session::flash('error') .'</div>';
    }elseif(Session::exists('success')) {
        echo '<div class="alert alert-success" role="alert">'.Session::flash('success') .'</div>';
    }

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {

            $valitation = new Validation();
            $valitation->check($_POST, array(
                'Email' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 20,
                    'unique' => 'users'
                ),
                'password' => array(
                    'required' => true,
                    'min' => 6,
                ),
                'password_again' => array(
                    'required' => true,
                    'matches' => 'password'
                ),
                'name' => array(
                    'required' => true,
                    'min' => 3,
                    'max' => 35
                ),
                'surname' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 35
                ),
                'plec' => array(
                    'required' => true
                ),
                'grupa' => array(
                    'required' => true
                )
            ));

            if ($valitation->passed()) {

                $user = new User();
                $db = DBB::getInstance();

                try {

                    $salt = Hash::slat();
                    $user->create(array(
                        'Email' => Input::get('Email'),
                        'IDHash' => md5(Input::get('Email')),
                        'Password' => Hash::make(Input::get('password'), $salt),
                        'Salt' => $salt,
                        'Permission' => Input::get('grupa'),
                        'PasswordCreadtedAt' => date('Y-m-d H:i:s'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'UpdatedAt' => date('Y-m-d H:i:s'),
                        'IsBlocked' => 0
                    ));

                    $id_account = $db->query("SELECT `ID` FROM `users` WHERE Email = '". Input::get('Email') ."'")->firstResult()->ID;

                    $account_details = $db->insert('users_data', array(
                        'IDUsers'               => (int)$id_account,
                        'FirstName'             => Input::get('name'),
                        'FirstNameUpdatedAt'    => date('Y-m-d H:i:s'),
                        'LastName'              => Input::get('surname'),
                        'LastNameUpdatedAt'     => date('Y-m-d H:i:s'),
                        'DateOfBirth'           => Input::get('birth'),
                        'DateOfBirthUpdatedAt'  => date('Y-m-d H:i:s'),
                        'Gender'                => (Input::get('plec') == 1 ? 1 : 0),
                        'PESEL'                 => Input::get('pesel'),
                        'PESELCreatedAt'        => date('Y-m-d H:i:s'),
                        'PESELUpdatedAt'        => date('Y-m-d H:i:s'),
                        'IdentificationCard'            => Input::get('id_card'),
                        'IdentificationCardCreatedAt'   => date('Y-m-d H:i:s'),
                        'IdentificationCardUpdatedAt'   => date('Y-m-d H:i:s'),
                        'ExpirationDateNoPersonalCard'              => Input::get('date_expired'),
                        'ExpirationDateNoPersonalCardCreatedAt'     => date('Y-m-d H:i:s'),
                        'ExpirationDateNoPersonalCardUpdatedAt'     => date('Y-m-d H:i:s'),
                        'CompanyName'           => Input::get('company'),
                        'CompanyNameCreatedAt'  => date('Y-m-d H:i:s'),
                        'CompanyNameUpdatedAt'  => date('Y-m-d H:i:s'),
                        'WorkPosition'          => Input::get('work_position'),
                        'WorkPositionCreatedAt' => date('Y-m-d H:i:s'),
                        'WorkPositionUpdatedAt' => date('Y-m-d H:i:s'),
                        'DateCreatedRecord'     => date('Y-m-d H:i:s'),
                    ));

                } catch(Exception $e) {
                    Logs::addError('Cant create new user! Message: '. $e->getMessage() .' File '. $e->getFile() .' Line '. $e->getLine());
                    Session::flash('error', 'Błąd - nie można utworzyć konta!');
                    Redirect::to('register.php');
                }

                // success for creating
                Session::flash('success', 'Użytkownik został utworzony!');
                Logs::addInformation('Użytkownik został utworzony!');
                Redirect::to('register.php');

            } else {
                // ERRORS FROM VALIDATION
                echo '<div class="alert alert-danger" role="alert">';

                foreach ($valitation->errors() as $error) {
                    echo '<p>' . $error . '</p>';
                }

                echo '</div>';
                Logs::addWarning('Error from validation while was create new account.');
            }

        }
    }

?>


            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <div class="">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Email</div>
                            </div>
                            <input type="text" class="form-control" id="Email" name="Email" value="<?php echo escape(Input::get('Email')); ?>" placeholder="Podaj adres email...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Hasło</div>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Podaj hasło...">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Powtórz hasło</div>
                            </div>
                            <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Powtórz hasło...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Imię</div>
                            </div>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo escape(Input::get('name')); ?>" placeholder="Podaj imię...">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Nazwisko</div>
                            </div>
                            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo escape(Input::get('surname')); ?>" placeholder="Podaj imię...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Płeć</div>
                            </div>
                            <select class="form-control" id="plec" name="plec" required>
                                <option value="">Wybierz</option>
                                <option value="2">Kobieta</option>
                                <option value="1">Mężczyzna</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">*Grupa</div>
                            </div>
                            <select class="form-control"  id="grupa" name="grupa">
                                <?php

                                    $db = DBB::getInstance();
                                    $options = $db->query('SELECT ID, Name FROM permission')->results();

                                    // option for select
                                    foreach($options as $option) {
                                        echo '<option value="'. (($option->ID == '3') ? $option->ID.'" selected>' : $option->ID.'">') . $option->Name .'</option>';
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Data urodzenia</div>
                            </div>
                            <input type="date" class="form-control" id="birth" name="birth" value="<?php echo escape(Input::get('birth')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Pesel</div>
                            </div>
                            <input type="text" class="form-control" id="pesel" name="pesel" value="<?php echo escape(Input::get('pesel')); ?>" placeholder="Podaj pesel...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Numer dowodu</div>
                            </div>
                            <input type="text" class="form-control" id="id_card" name="id_card" value="<?php echo escape(Input::get('id_card')); ?>" placeholder="Podaj numer dowodu...">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Termin ważności</div>
                            </div>
                            <input type="date" class="form-control" id="date_expired" name="date_expired" value="<?php echo escape(Input::get('date_expired')); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Firma</div>
                            </div>
                            <input type="text" class="form-control" id="company" name="company" value="<?php echo escape(Input::get('company')); ?>" placeholder="Podaj nazwę firmy...">
                        </div>
                    </div>
                    <div class="col-lg-6 mt-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Stanowisko</div>
                            </div>
                            <input type="text" class="form-control" id="work_position" name="work_position" value="<?php echo escape(Input::get('work_position')); ?>" placeholder="Podaj stanowisko...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Utwórz konto" class="btn btn-primary" style="margin-left: auto; margin-right: auto;">
                </div>

            </form>

        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-2"></div>
    </div>
</div>
</BODY>
</HTML>