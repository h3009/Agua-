<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
ini_set('session.cookie_secure', 1); // Apenas em HTTPS
ini_set('session.cookie_httponly', 1); // Impede acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Previne ataques CSRF
session_start(); // Inicia a sessão
include '../../config/bd.php';

// Verificação Honeypot
if (!empty($_POST['honeypot'])) {
    die("Atividade suspeita detectada!");
}

// Sanitização dos dados
$identificador = filter_input(INPUT_POST, 'identificador', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$senha = $_POST['senha'];

// Busca o usuário no banco de dados
$stmt = mysqli_prepare($connect, 
    "SELECT id_cliente, senha 
     FROM clientes 
     WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $identificador);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    // Delay para evitar enumeração de usuários
    sleep(2);
    die("Credenciais inválidas!");
}

// Verificação de senha
if (password_verify($senha, $user['senha'])) {
    // Regenerar ID da sessão
    session_regenerate_id(true);
    
    // Armazenar dados do usuário na sessão
    $_SESSION['user_id'] = $user['id_cliente'];
    $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    
    // Redirecionar para o painel
    header("Location: ../index.php");
    exit();
} else {
    // Atraso intencional para evitar brute force
    sleep(2);
    die("Credenciais inválidas!");
}
?>