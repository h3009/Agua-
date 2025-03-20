<?php
session_start();
include '../config/bd.php';

// Verifica autenticação
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Busca solicitações do usuário com informações do motorista
$query = "SELECT p.id, p.data_pedido, p.quantidade, p.prioridade, p.status,m.id_motorista,
           m.nome AS motorista_nome, m.tipo_cisterna AS motorista_veiculo
    FROM pedidos p
    LEFT JOIN motoristas m ON p.id_motorista = m.id_motorista
    WHERE p.id_cliente = ? and status = 'andamento'
    ORDER BY p.data_pedido DESC";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Estilos gerais */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 2rem;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
}

h1 {
    color: #333;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2.5rem;
    font-weight: 600;
}

/* Lista de solicitações */
.solicitacoes-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.solicitacao-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.solicitacao-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.solicitacao-info h3 {
    margin: 0 0 1rem 0;
    color: #333;
    font-size: 1.5rem;
    font-weight: 600;
}

.solicitacao-info p {
    margin: 0.5rem 0;
    color: #666;
    font-size: 1rem;
}

/* Status da solicitação */
.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: capitalize;
}

.status-pendente { background: #fff3e0; color: #ff9100; }
.status-em_andamento { background: #e3f2fd; color: #2196f3; }
.status-concluido { background: #e8f5e9; color: #4caf50; }
.status-cancelado { background: #ffe5e5; color: #ff4444; }

/* Informações do motorista */
.motorista-info {
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: #f9f9f9;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.motorista-info:hover {
    background: #f1f1f1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.motorista-info h4 {
    margin: 0 0 0.5rem 0;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.motorista-info p {
    margin: 0.3rem 0;
    color: #555;
    font-size: 0.95rem;
}

/* Estado vazio */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #666;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    color: #f96969;
}

.empty-state p {
    font-size: 1.2rem;
    margin: 0;
}

/* Botões e interações */
.btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #f96969, #ff7b7b);
    color: white;
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(249, 105, 105, 0.3);
}

/* Responsividade */
@media (max-width: 768px) {
    .container {
        padding: 1rem;
    }

    .solicitacoes-list {
        grid-template-columns: 1fr;
    }

    .solicitacao-card {
        padding: 1rem;
    }

    .motorista-info {
        padding: 1rem;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Minhas Solicitações</h1>

        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="solicitacoes-list">
                <?php while($solicitacao = mysqli_fetch_assoc($result)): ?>
                    <div class="solicitacao-card">
                        <div class="solicitacao-info">
                            <h3>Solicitação #<?= $solicitacao['id'] ?></h3>
                            <p><strong>Quantidade:</strong> <?= $solicitacao['quantidade'] ?> litros</p>
                            <p><strong>Status:</strong> 
                                <span class="status-badge status-<?= $solicitacao['status'] ?>">
                                    <?= ucfirst($solicitacao['status']) ?>
                                </span>
                            </p>
                            <?php if($solicitacao['id_motorista']): ?>
                                <div class="motorista-info">
                                    <h4>Motorista Atribuído</h4>
                                    <p><strong>Nome:</strong> <?= htmlspecialchars($solicitacao['motorista_nome']) ?></p>
                                    <p><strong>Veículo:</strong> <?= htmlspecialchars($solicitacao['motorista_veiculo']) ?></p>
                                    <p><strong>Placa:</strong> <?= htmlspecialchars($solicitacao['placa_veiculo']) ?></p>
                                    <p><strong>Distância:</strong> <?= $solicitacao['distancia_km'] ?> km</p>
                                    <button class="btn btn-primary">Acompanhar Motorista</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-gas-pump"></i>
                <p>Nenhuma solicitação encontrada.</p>
            </div>
        <?php endif; ?>
    </div>    
</body>
</html>