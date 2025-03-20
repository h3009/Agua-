<?php
session_start();
include '../config/bd.php';

// Verifica autenticação
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Coordenadas do usuário (exemplo)
$latitude_usuario = $_SESSION['latitude'] ?? -23.5505; // Latitude de São Paulo
$longitude_usuario = $_SESSION['longitude'] ?? -46.6333; // Longitude de São Paulo

// Busca solicitações do usuário com informações do motorista
$query = "SELECT p.id,p.id_cliente, p.data_pedido, p.quantidade, p.prioridade, p.status,m.id_motorista,m.latitude,m.longitude,
           m.nome AS motorista_nome, m.tipo_cisterna AS motorista_veiculo, m.placa_veiculo,
           ROUND(6371 * ACOS(
               COS(RADIANS(?)) * COS(RADIANS(m.latitude)) * COS(RADIANS(m.longitude) - RADIANS(?)) +
               SIN(RADIANS(?)) * SIN(RADIANS(m.latitude))
           ), 2) AS distancia_km
    FROM pedidos p
    LEFT JOIN motoristas m ON p.id_motorista = m.id_motorista
    WHERE p.id_cliente = ? and status='andamento'
    ORDER BY p.data_pedido DESC";
$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "dddi", $latitude_usuario, $longitude_usuario, $latitude_usuario, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verifica se a localização já foi salva
if (!isset($_SESSION['latitude']) || !isset($_SESSION['longitude'])) {
    echo '
    <script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                fetch("salvar_localizacao.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ latitude, longitude }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload(); // Recarrega a página após salvar a localização
                    } else {
                        alert("Erro ao salvar localização.");
                    }
                });
            },
            (error) => {
                alert("Erro ao obter localização: " + error.message);
            }
        );
    } else {
        alert("Geolocalização não suportada pelo navegador.");
    }
    </script>
    ';
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Solicitações</title>
    <link rel="stylesheet" href="css/lista_pedidos">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="../../icon/font-awesome/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=SUA_CHAVE_API"></script>
</head>
<body>
    
    <div class="main">
        <header class="header">
            <div class="title">
                <span>Água</span><i class="fal fa-map-marker-plus"></i>
            </div>
            <nav class="h">

                <a href="perfil/perfil.php" class="fotoU"><img src="../uploads/67d5a63ec33a3.jpg" alt=""></a>
            </nav>
        </header>
        <div class="content">
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
                                            <p><strong>Distância:</strong> 
                                                <span class="distancia-info">
                                                    <?= $solicitacao['distancia_km'] ?> km
                                                </span>
                                            </p>
                                            <button class="btn btn-primary" onclick="abrirMapa(<?= $latitude_usuario ?>, <?= $longitude_usuario ?>, <?= $solicitacao['latitude'] ?>, <?= $solicitacao['longitude'] ?>)">
                                                Acompanhar no Mapa
                                            </button>
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
                
            
                <!--Aqui vai ter um botão "Solicitar Abastecimento" e quando clicado o usuario poderá fazer a solicitação e depois de ser feita o usuario poderá visualizá-la-->
            </div>
            <div id="geolocation-warning" style="display: none; padding: 1rem; background: #fff3e0; border-radius: 8px; margin-bottom: 1rem;">
                <p>Por favor, permita o acesso à sua localização para usar esta funcionalidade.</p>
            </div>
            <a href="log/logout.php">sair</a>
        </div>
        <footer class="footer">
            <nav class="menu">
                <a href="index.php">
                    <i class="fa fa-truck"></i>
                    <span>Solicitação de Abastecimento</span> 
                </a>
                <a href="motoristas">
                    <i class="fa fa-users"></i>
                    <span>Lista de Motoristas Próximos</span> 
                </a>
                <a href="#">
                    <i class="fa fa-map"></i>
                    <span>Acompanhamento em Tempo Real</span> 
                </a>
                <a href="#">
                    <i class="fa fa-users-medical"></i>
                    <span>Educação Hídrica</span> 
                </a>
                <a href="#">
                    <i class="fa fa-map-pin"></i>
                    <span>Áreas Com Demanda de Água</span> 
                </a>
            </nav>
        </footer>
    </div>

<script>
        // Exibe o aviso se a localização não for permitida
        navigator.geolocation.getCurrentPosition(
            () => {}, // Sucesso (não faz nada)
            (error) => {
                if (error.code === error.PERMISSION_DENIED) {
                    document.getElementById('geolocation-warning').style.display = 'block';
                }
            }
        );
        
        // Função para abrir o Google Maps com o trajeto
        function abrirMapa(latUsuario, lngUsuario, latMotorista, lngMotorista) {
            const url = `https://www.google.com/maps/dir/?api=1&origin=${latUsuario},${lngUsuario}&destination=${latMotorista},${lngMotorista}&travelmode=driving`;
            window.open(url, '_blank');
        }

        // Atualização em tempo real da distância
        setInterval(() => {
            fetch('atualizar_distancia.php')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.distancia-info').textContent = `${data.distancia} km`;
                });
        }, 5000); // Atualiza a cada 5 segundos
    </script>
</body>
</html>