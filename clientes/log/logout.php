<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
ini_set('session.cookie_secure', 1); // Apenas em HTTPS
ini_set('session.cookie_httponly', 1); // Impede acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Previne ataques CSRF

session_start(); // Inicia a sessão
session_regenerate_id(true); // Regenera o ID da sessão

// Destrói todos os dados da sessão
$_SESSION = array(); // Limpa os dados da sessão

session_destroy(); // Destrói a sessão

// Redireciona para a página de login
header("Location: login.php");
exit();
?>