<?php
// database.php
$host = 'localhost';
$user = 'root';
$dbname = 'bd_agua_mais';
$password = '';

$connect = mysqli_init();
mysqli_options($connect, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

if (!mysqli_real_connect($connect, $host, $user, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Definir charset para evitar problemas de segurança com caracteres especiais
mysqli_set_charset($connect, 'utf8mb4');
?>
