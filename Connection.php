<?php
/*
$hostname = "127.0.0.1";
$port = 3306;
$database = "projectDB";
$username = "root";
$password = "";
*/


$hostname = "msh.caxerx.com";
$port = 8886;
$database = "php_ass";
$username = "caxerx_dev";
$password = "pIJ00Ws2G9ff3gkY";

$conn = new mysqli("$hostname:$port", $username, $password, $database) or die($conn->error);

?>