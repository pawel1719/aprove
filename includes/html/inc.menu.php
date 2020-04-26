<?php
require_once 'core/init.php';

    $user = new User();

    if($user->hasPermission('user_home', 'read')) {
        echo '<a href="home.php">
            <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: auto;">
                Home
            </button>
        </a>';
    }

    if($user->hasPermission('user_approval', 'read')) {
        echo '<a href="hometable.php">
            <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: auto;">
                Zgody
            </button>
        </a>';
    }

    if($user->hasPermission('user_change_password', 'write')) {
        echo '<a href="changepassword.php">
            <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: auto;">
                Zmiana hasła
            </button>
        </a>';
    }

    if($user->hasPermission('user_user_data', 'write')) {
        echo '<a href="update.php">
            <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: auto;">
                Moje dane
            </button>
        </a>';
    }

    if($user->hasPermission('admin_panel', 'read')) {
        echo '<a href="admin/adminpanel.php">
            <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: auto;">
                Admin panel
            </button>
        </a>';
    }

     echo '<a href="logout.php">
         <button type="button" class="btn btn-light  mb-1 btn-outline-primary" style="width: auto;">
             Wyloguj
         </button>
     </a>';

    echo '<hr/>';

?>