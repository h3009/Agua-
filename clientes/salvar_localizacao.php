<?php
session_start();

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['latitude']) && isset($data['longitude'])) {
        // Salva as coordenadas na sessão
        $_SESSION['latitude'] = $data['latitude'];
        $_SESSION['longitude'] = $data['longitude'];

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>