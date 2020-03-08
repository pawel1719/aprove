<?php
require_once 'core/init.php';

$db = DBB::getInstance();
//$result = $db->insert('logs', array(
//    'IPAddress' => '192.168.0.1',
//    'CreatedAt' => date("Y-m-d H:i:s"),
//    'Action' => 'Log in - test',
//    'Devices' => 'Samsung S10'
//));

$result = $db->update('connestions', '5', array(
    'IPAddress' => '10.10.10.124',
    'CreatedAt' => date("Y-m-d H:i:s"),
    'LastAttempts' => date("Y-m-d H:i:s")
));

echo $result;
//$db->query('SELECT * FROM permission');
//$db->get('permission', array('id', '>', '0'));
//
//foreach($db->results() as $user) {
//    echo $user->ID . ' ' . $user->Name . '<br/>';
//}
//
//echo '<hr/>';
//
//echo $db->firstResult()->Name;