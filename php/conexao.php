<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "jogo_memoria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>