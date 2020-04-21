<?php
require_once '../core/init.php';

    $logged = new User();

    if(!$logged->isLogged()) {
        Logs::addError("Unauthorization access!");
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

        <h3>Dodaj nowego użytkownika!</h3>
        <hr/>

<?php

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {

            $valitation = new Validation();
            $valitation->check($_POST, array(
                'email' => array(
                    'required' => true,
                    'min' => 5,
                    'max' => 20,
                ),
                'password' => array(
                    'required' => true
                ),
                'password_again' => array(
                    'required' => true
                )
            ));

            if ($valitation->passed()) {
                $user = new User();
                try {

                    $salt = Hash::slat();
                    $user->create(array(
                        'Email' => Input::get('email'),
                        'Password' => Hash::make(Input::get('password'), $salt),
                        'Salt' => $salt,
                        'Permission' => 3,
                        'PasswordCreadtedAt' => date('Y-m-d H:i:s'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'UpdatedAt' => date('Y-m-d H:i:s'),
                        'IsBlocked' => 0
                    ));

                    Session::flash('registed', 'You are registed!<br/>');
                    Redirect::to('index.php');

                } catch(Exception $e) {
                    die($e->getMessage());
                }
            } else {
                // ERRORS FROM VALIDATION
                echo '<div class="alert alert-danger" role="alert">';

                foreach ($valitation->errors() as $error) {
                    echo '<p>' . $error . '</p>';
                }

                echo '</div>';
            }

        }
    }

?>


            <form action="" method="post" class="text-light mt-5">

                <div class="form-group">
                    <div class="">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Email</div>
                            </div>
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo escape(Input::get('email')); ?>" placeholder="Podaj adres email...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Hasło</div>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Podaj hasło...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Powtórz hasło</div>
                            </div>
                            <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Powtórz hasło...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Imię</div>
                            </div>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo escape(Input::get('name')); ?>" placeholder="Podaj imię...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Nazwisko</div>
                            </div>
                            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo escape(Input::get('surname')); ?>" placeholder="Podaj imię...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Data urodzenia</div>
                            </div>
                            <input type="date" class="form-control" id="birth" name="birth" value="<?php echo escape(Input::get('birth')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Pesel</div>
                            </div>
                            <input type="text" class="form-control" id="pesel" name="pesel" value="<?php echo escape(Input::get('pesel')); ?>" placeholder="Podaj pesel...">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Numer dowodu</div>
                            </div>
                            <input type="text" class="form-control" id="id_card" name="id_card" value="<?php echo escape(Input::get('id_card')); ?>" placeholder="Podaj numer dowodu...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Termin ważności</div>
                            </div>
                            <input type="date" class="form-control" id="date_expired" name="date_expired" value="<?php echo escape(Input::get('date_expired')); ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group form-row">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Firma</div>
                            </div>
                            <input type="text" class="form-control" id="company" name="company" value="<?php echo escape(Input::get('company')); ?>" placeholder="Podaj nazwę firmy...">
                        </div>
                    </div>
                    <div class="col-lg-6">
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
        <div class="col-1 col-md-2 col-lg-2"></div>
    </div>
</div>

</BODY>
</HTML>