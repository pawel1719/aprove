<?php
require '../../../core/init.php';

$user = new User();

if(!$user->isLogged()) {
    Logs::addError("Unauthorization access!");
    Redirect::to('../../../index.php');
}
    if(Input::exists()) {
        if (Token::check(Input::get('token'))) {
            if (Input::get('case') == '1') {

                //connect do db
                $db = DBB::getInstance();

                //user to agreement
                $users = new Users();
                $user_to_agree = $users->usersAll(0, 10, 'WHERE u.ID = '. (int)Input::get('user_id') .' ')->results()[0];


                //array data to insert
                $users_agreement_add = array(
                    'IDUsers' => (int)Input::get('user_id'),
                    'IDagreementsConfiguration' => (int)Input::get('agreement'),
                    'AccessGuid' => hash('sha256',Input::get('hash_agree') .' '. $user_to_agree->ID. ' '. $user_to_agree->FirstName .' '. $user_to_agree->LastName .' '. date('Y-m-d H:i:s')),
                    'Password' => hash('sha256', '$tring1234'),
                    'PasswordValidity' => date( 'Y-m-d H:i:s', strtotime( date( 'Y-m-d H:i:s' ) .' +1 day' )),
                    'AddedBy' => $user->data()->ID,
                    'AddedAt' => date('Y-m-d H:i:s')
                );

                if(Input::get('status') == 'true') {
                    //adding data agreement in db
                    try {
                        $db->insert('agreements', $users_agreement_add);
                        sleep(0.1);

                        echo 'Success adding user'. Input::get('user_id') .'!';
                    }catch (Exception $e) {
                        Logs::addError("Cant add new agreemet. Message: ". $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile() .' Array:'. strval(var_dump($users_agreement_add)));
                        echo('#2321: Error: '. $e->getMessage());
                    }

                    try {
                        // MAIL TO SEND
                        $NAME_TO = $user_to_agree->FirstName .' '. $user_to_agree->LastName;
                        $SUBJECT = 'Odpowiedz na nowe zasady!';
                        $HTML_MESSAGE = '
                       <HTML>
                       <HEAD></HEAD>
                       <BODY>
                            <p>
                                Dzień doby,<br/><br/>
                                Przesyłam dane do logowania apliakcji!<br>
                                Dostępne pod adresem <a href="link.php?access='. $users_agreement_add['AccessGuid'] .'"><b>Kliknij tu</b></a>, dostępne do '. $users_agreement_add['PasswordValidity'] .'<br>
                                <b>Login:</b> '. $user_to_agree->Email .'<br>
                                <b>Hasło:</b> $tring1234<br>
                            </p>
                            <p>
                                Best wishes<br/>
                                Aproval app!<br/>
                                Created by Paweł Szóstkiewicz
                           </p>
                       </BODY>
                       </HTML>
                       ';

                        $mail = new Mail(true);
                        $mail->createMessage($user_to_agree->Email, $NAME_TO, $SUBJECT, $HTML_MESSAGE);

                        unset($NAME_TO, $SUBJECT, $HTML_MESSAGE);

                    }catch (Exception $e) {
                        Logs::addError('#2323: Cant send mail to user with agreement! Message: '. $e->getMessage() .' Line: '. $e->getLine() .' File: '. $e->getFile() .' Array:'. var_dump($users_agreement_add));
                        echo('#2323: Error: '. $e->getMessage());
                    }

                } else {
                    //delete data agreement in db
//                    if(Input::get('agree_accept') == 'NULL') {
                        try {
                            $db->query('DELETE FROM agreements WHERE IDUsers = ? AND IDagreementsConfiguration = ?', array(
                                (int)Input::get('user_id'),
                                (int)Input::get('agreement')
                            ));
                            echo 'Success deleting ' . Input::get('user_id') . '!';
                        } catch (Exception $e) {
                            Logs::addError("Cant delete agreemet. Message: " . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile() . ' Variable:' . Input::get('user_id') . ' ' . Input::get('agreement'));
                            echo('#2322: Error: ' . $e->getMessage());
                        }
//                    }
                    echo var_dump($_POST);
                }

                // token for this same form
                Session::put('token', Input::get('token'));
                // cleared variables
                unset($_POST);
                unset($users_agreement_add);
            }
        } else {
            // error for incorrect token
            echo 'Error: incorrect token';
            Logs::addError('Incorrect token! Session=' . Session::get('token') . ' == input_token=' . Input::get('token'));
        }

    } else {
        // error for empty $_POST or $_GET
        echo 'Error: input variable dont exists!';
        Logs::addError('Input variable dont exists!');

    }