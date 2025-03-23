<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: log/login.php");
    exit();
}

$id_usuario = $_SESSION['user_id'] ; 
require_once '../config/bd.php';
$sql = mysqli_prepare($connect,"SELECT * FROM clientes WHERE id_cliente=?");
mysqli_stmt_bind_param($sql,"i",$id_usuario);
mysqli_stmt_execute($sql);
$pegar = mysqli_stmt_get_result($sql);

// Busca solicitações do usuário
$stmt = mysqli_prepare($connect, 
    "SELECT id, data_pedido, quantidade, prioridade, status 
     FROM pedidos 
     WHERE id_cliente = ?
     ORDER BY data_pedido DESC");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt-ao">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/formS.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="../icon/font-awesome/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=SUA_CHAVE_API_VALIDA&callback=initMap" async defer></script>
    <script>
        document.getElementById('form-abastecimento').addEventListener('submit', async (e) => {
            e.preventDefault();

            console.log('Formulário enviado'); // Verifica se o evento está sendo acionado

            // Captura o ID do cliente do atributo data-id_cliente
            const id_cliente = document.getElementById('form-abastecimento').getAttribute("data-id_cliente");

            // Captura as coordenadas do mapa
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;

            // Valida se as coordenadas foram selecionadas
            if (!latitude || !longitude) {
                alert('Por favor, selecione uma localização no mapa.');
                return;
            }

            // Monta o objeto do pedido
            const pedido = {
                id_cliente: id_cliente,
                quantidade: document.getElementById('quantidade').value,
                prioridade: document.getElementById('prioridade').value,
                latitude: latitude,
                longitude: longitude
            };

            console.log('Dados do pedido:', pedido); // Verifica os dados do pedido

            try {
                // Envia o pedido para o backend
                const resposta = await fetch('solicitar-abastecimento.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(pedido)
                });

                const resultado = await resposta.json();

                if (resposta.ok) {
                    // Limpa o formulário após o envio
                    document.getElementById('quantidade').value = '';
                    document.getElementById('prioridade').value = 'urgente'; // Reseta para o valor padrão
                    document.getElementById('latitude').value = '';
                    document.getElementById('longitude').value = '';

                    alert('Pedido enviado! Aguarde motoristas próximos.');
                } else {
                    alert('Erro ao enviar pedido: ' + resultado.mensagem);
                }
            } catch (error) {
                console.error('Erro ao enviar pedido:', error);
                alert('Erro ao enviar pedido. Verifique o console para mais detalhes.');
            }
        });
    </script>
    <script>
    let map;
    let marker;

    function initMap() {
        // Coordenadas iniciais (exemplo: centro de São Paulo)
        const initialPosition = { lat: -23.5505, lng: -46.6333 };

        // Cria o mapa
        map = new google.maps.Map(document.getElementById('map'), {
            center: initialPosition,
            zoom: 12,
            mapId: 'SUA_MAP_ID' // Adicione um Map ID (opcional, mas recomendado)
        });

        // Cria um marcador avançado
        marker = new google.maps.marker.AdvancedMarkerElement({
            map: map,
            position: initialPosition,
            gmpDraggable: true, // Permite arrastar o marcador
            title: 'Localização selecionada'
        });

        // Atualiza as coordenadas ao mover o marcador
        marker.addListener('dragend', (event) => {
            const position = event.target.position;
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Define as coordenadas iniciais no formulário
        document.getElementById('latitude').value = initialPosition.lat;
        document.getElementById('longitude').value = initialPosition.lng;
    }

    // Inicializa o mapa quando a página carregar
    window.onload = initMap;
    </script>
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

            <!-- Estrutura do Modal -->
            <div class="modalS" id="modal">
                <div class="modal-overlay"></div>
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <form id="form-abastecimento" class="form-abastecimento" data-id_cliente="<?php echo $id_usuario; ?>">
                        <!-- Campo para quantidade -->
                        <div class="input-group soft-ui">
                            <input type="number" id="quantidade" placeholder="Quantidade (litros)" required class="soft-input">
                        </div>

                        <!-- Campo para prioridade -->
                        <div class="input-group soft-ui">
                            <select id="prioridade" class="soft-select">
                                <option value="urgente">Urgente</option>
                                <option value="agendado">Agendado</option>
                            </select>
                        </div>

                        <!-- Mapa para seleção da localização -->
                        <div class="input-group">
                            <label for="map">Selecione a localização no mapa:</label>
                            <div id="map" style="height: 300px; width: 100%; border-radius: 12px; margin-top: 1rem;"></div>
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                        </div>

                        <!-- Botão de envio -->
                        <button type="submit" class="btn-soft">Confirmar</button>
                    </form>
                </div>
            </div>
            <div class="solicitacoes-container">
                <h2 style="margin-bottom: 2rem; color: #333;">Minhas Solicitações</h2>

                <?php if(isset($success)): ?>
                    <div class="success"><?= $success ?></div>
                <?php elseif(isset($error)): ?>
                    <div class="error"><?= $error ?></div>
                <?php endif; ?>

                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($solicitacao = mysqli_fetch_assoc($result)): ?>
                        <div class="solicitacao-card <?= $solicitacao['status'] === 'cancelado' ? 'status-cancelado' : '' ?>">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <span class="status-badge prioridade-<?= $solicitacao['prioridade'] ?>">
                                        <?= ucfirst($solicitacao['prioridade']) ?>
                                    </span>
                                    <span class="status-badge status-<?= $solicitacao['status'] ?>">
                                        <?= ucfirst($solicitacao['status']) ?>
                                    </span>
                                </div>
                                <small style="color: #666;">
                                    <?= date('d/m/Y H:i', strtotime($solicitacao['data_pedido'])) ?>
                                </small>
                            </div>

                            <div class="detalhes-solicitacao">
                                <div>
                                    <label>Quantidade</label>
                                    <p style="margin: 0.5rem 0; font-weight: 500;">
                                        <?= $solicitacao['quantidade'] ?> litros
                                    </p>
                                </div>
                                
                                <div>
                                    <label>Última Atualização</label>
                                    <p style="margin: 0.5rem 0; color: #666;">
                                        <?= date('d/m/Y H:i', strtotime($solicitacao['data_atualizacao'] ?? $solicitacao['data_pedido'])) ?>
                                    </p>
                                </div>
                            </div>

                            <?php if($solicitacao['status'] !== 'cancelado' && $solicitacao['status'] !== 'concluido'): ?>
                                <form method="POST" id="form-cancelamento" action="cancelar_pedido.php" style="margin-top: 1rem;">
                                    <input type="hidden" name="id" id="id" value="<?= $solicitacao['id'] ?>">
                                    <button type="submit" name="cancelar" class="btn-cancelar">
                                        Cancelar Solicitação
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-gas-pump" style="font-size: 3em; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>Nenhuma solicitação encontrada</p>
                    </div>
                <?php endif; ?>
            </div>
                <!--Solicitar Abastecimento este botão tem que ser flutuante --> <button class="solicitar fa fa-plus" id="solicitar"></button>


            <a href="log/logout.php">sair</a>
            <!--Aqui vai ter um botão "Solicitar Abastecimento" e quando clicado o usuario poderá fazer a solicitação e depois de ser feita o usuario poderá visualizá-la-->

        </div>
        <footer class="footer">
            <nav class="menu">
                <a href="index.php">
                    <i class="fa fa-truck"></i>
                    <span>Solicitação de Abastecimento</span> 
                </a>
                <a href="lista_pedidos.php">
                    <i class="far fa-list "></i>
                    <span>Lista de Pedidos Em Andamento</span> 
                </a>
                <a href="#">
                    <i class="far fa-users-medical"></i>
                    <span>Educação Hídrica</span> 
                </a>
                <a href="#">
                    <i class="far fa-map-pin"></i>
                    <span>Áreas Com Demanda de Água</span> 
                </a>
            </nav>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Abrir Modal
            const openBtn = document.getElementById('solicitar');
            const modal = document.getElementById('modal');
            const closeBtn = modal.querySelector('.close-modal');

            openBtn.addEventListener('click', () => {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Bloqueia scroll
            });

            // Fechar Modal
            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            closeBtn.addEventListener('click', closeModal);

            // Fechar clicando fora
            modal.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal-overlay')) {
                    closeModal();
                }
            });

            // Fechar com ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>