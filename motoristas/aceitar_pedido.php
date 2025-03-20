<?php
session_start();
header('Content-Type: application/json');
require_once('../config/bd.php');

// Verifica se o motorista está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Não autorizado']);
    exit();
}

// Recebe os dados do pedido
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se os dados necessários foram recebidos
if (!isset($dados['id_pedido'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos']);
    exit();
}

$id_pedido = $dados['id_pedido'];
$id_motorista = $_SESSION['user_id'];

// Prepara a query para atualizar o pedido
$stmt = mysqli_prepare($connect, "UPDATE pedidos 
    SET id_motorista = ?, status = 'andamento' 
    WHERE id = ? AND status = 'pendente'");

if (!$stmt) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar a query']);
    exit();
}

// Vincula os parâmetros corretamente
mysqli_stmt_bind_param($stmt, "ii", $id_motorista, $id_pedido);

// Executa a query
if (mysqli_stmt_execute($stmt)) {
    // Verifica se alguma linha foi afetada
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Pedido já foi aceito ou não existe']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao executar a query']);
}

// Fecha o statement e a conexão
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>