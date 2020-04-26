<?php
require_once '../core/init.php';

    $user = new User();

    if(!$user->hasPermission('admin_panel', 'read')) {
        Logs::addError('User '. $user->data()->ID .' dont have permission to this page! Permission admin_panel/read');
        Session::flash('warning', 'Nie masz uprawnień!');
        Redirect::to('home.php');
    }

    if($user->hasPermission('user_panel', 'read')) {
        echo '<a href="../home.php">
                <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: 130px;">
                    Home
                </button>
            </a>';
    }

    if($user->hasPermission('admin_add_approval', 'write')) {
        echo '<a href="approvalsadd.php">
                <button type="button" class="btn btn-light mb-1 btn-outline-primary"  style="width: 130px;">
                    Dodaj zgodę
                </button>
            </a>';
    }

    if($user->hasPermission('admin_list_approval', 'read')) {
        echo '<a href="approvalsmanag.php">
                <button type="button" class="btn btn-light  mb-1 btn-outline-primary"  style="width: 130px;">
                    Zgody
                </button>
            </a>';
    }

    if($user->hasPermission('admin_charts_approval', 'read')) {
        echo '<a href="approvalscharts.php">
                <button type="button" class="btn btn-light  mb-1 btn-outline-primary"  style="width: 130px;">
                    Wykresy
                </button>
            </a>';
    }

    if($user->hasPermission('admin_all_users', 'read')) {
        echo '<a href="allusers.php?row=15&page=1">
                <button type="button" class="btn btn-light  mb-1 btn-outline-primary"  style="width: 130px;">
                    Użytkownicy
                </button>
            </a>';
    }

    if($user->hasPermission('admin_add_users', 'write')) {
        echo '<a href="register.php">
                <button type="button" class="btn btn-light  mb-1 btn-outline-primary"  style="width: 130px;">
                    Dodaj konto
                </button>
            </a>';
    }

    if($user->hasPermission('admin_send_mail', 'write')) {
        echo '<a href="sendmail.php">
                <button type="button" class="btn btn-light mb-1 btn-outline-primary"  style="width: 130px;">
                    Mail
                </button>
            </a>';
    }

    echo '<a href="../logout.php">
               <button type="button" class="btn btn-light mb-1 btn-outline-primary"  style="width: 130px;">
                   Wyloguj
               </button>
           </a>';

?>