<?php
require_once 'core/init.php';

$db = DBB::getInstance();
//$db->query('SELECT * FROM permission');
$db->get('permission', array('id', '>', '0'));

foreach($db->results() as $user) {
    echo $user->ID . ' ' . $user->Name . '<br/>';
}

echo '<hr/>';

echo $db->firstResult()->Name;