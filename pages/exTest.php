<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
echo $_POST['shop'];
echo '<br/>';
print_r(json_decode($_POST['shops']));
print_r(json_decode($_POST['products']));
?>