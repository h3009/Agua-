<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
ini_set('session.cookie_secure', 1); // Apenas em HTTPS
ini_set('session.cookie_httponly', 1); // Impede acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Previne ataques CSRF
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: log/login.php");
    exit();
}

$id_usuario = $_SESSION['user_id'] ; 
require_once '../config/bd.php';
// Processar cancelamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar'])) {
    $id_solicitacao = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    if ($id_solicitacao) {
        $stmt = mysqli_prepare($connect, 
            "UPDATE pedidos 
             SET status = 'cancelado', data_atualizacao = NOW()
             WHERE id = ? AND id_cliente = ?");
        mysqli_stmt_bind_param($stmt, "ii", $id_solicitacao, $id_usuario);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_affected_rows($connect) > 0) {
            $success = "Solicitação cancelada com sucesso!";
            header('Location: index.php?success');
            exit;
        } else {
            $error = "Erro ao cancelar solicitação.";
            header('Location: index.php?error');
            exit;
        }
    }
}

// Busca solicitações do usuário
/*
$stmt = mysqli_prepare($connect, 
    "SELECT id, data_solicitacao, quantidade, prioridade, status 
     FROM solicitacoes 
     WHERE id_usuario = ?
     ORDER BY data_solicitacao DESC");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
*/
?>