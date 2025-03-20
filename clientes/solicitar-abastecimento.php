<?php
header('Content-Type: application/json'); // Define o cabeçalho para JSON
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado']);
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
require_once '../config/bd.php';

// Recebe os dados do formulário
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se todos os campos necessários foram enviados
if (!isset($dados['id_cliente']) || !isset($dados['quantidade']) || 
    !isset($dados['prioridade']) || !isset($dados['latitude']) || 
    !isset($dados['longitude'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos']);
    exit();
}

// Validação dos dados
$id_cliente = filter_var($dados['id_cliente'], FILTER_VALIDATE_INT);
$quantidade = filter_var($dados['quantidade'], FILTER_VALIDATE_FLOAT);
$prioridade = in_array($dados['prioridade'], ['urgente', 'agendado']) ? $dados['prioridade'] : null;
$latitude = filter_var($dados['latitude'], FILTER_VALIDATE_FLOAT);
$longitude = filter_var($dados['longitude'], FILTER_VALIDATE_FLOAT);

// Verifica se os dados são válidos
if (!$id_cliente || !$quantidade || !$prioridade || !$latitude || !$longitude) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
    exit();
}

// Prepara a query SQL
$stmt = mysqli_prepare($connect, 
    "INSERT INTO pedidos (id_cliente, quantidade, prioridade, latitude, longitude, status)
     VALUES (?, ?, ?, ?, ?, 'pendente')"
);

// Verifica se a preparação da query foi bem-sucedida
if (!$stmt) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar a query: ' . mysqli_error($connect)]);
    exit();
}

// Vincula os parâmetros
mysqli_stmt_bind_param(
    $stmt,
    "idsss", // Tipos dos parâmetros: i (inteiro), d (double), s (string)
    $id_cliente,
    $quantidade,
    $prioridade,
    $latitude,
    $longitude
);

// Executa a query
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pedido salvo com sucesso']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar pedido: ' . mysqli_stmt_error($stmt)]);
}

// Fecha a conexão
mysqli_stmt_close($stmt);
mysqli_close($connect);
?>