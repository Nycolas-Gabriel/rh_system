<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$database = "rh_system";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Define o charset como UTF-8
$conn->set_charset("utf8mb4");
?>
