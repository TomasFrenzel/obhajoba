<?php
error_reporting(E_ALL);
$servername = 'innodb.endora.cz';
$username = 'logindat';
$password = 'LoginD@t321'; // Mění se podle hesla localhostu
$db = 'bezpecnenanetu'; //zmenit az jakoby nebudu

$con = mysqli_connect($servername, $username, $password, $db);

if (!$con) {
    die('Connection error: ' . mysqli_connect_error());
}
?>