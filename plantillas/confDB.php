<?php
$server = 'localhost';
$user = 'mgarcia';
$pass = 'garcorma';
$db = 'gestorArticulos_db';

$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>