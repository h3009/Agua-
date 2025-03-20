<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: log/login.php");
    exit();
}
$id_usuario = $_SESSION['user_id'] ; 
require_once '../config/bd.php';
$sql = mysqli_prepare($connect,"SELECT * FROM motoristas WHERE id_motorista=?");
mysqli_stmt_bind_param($sql,"i",$id_usuario);
mysqli_stmt_execute($sql);
$pegarRes = mysqli_stmt_get_result($sql);
$pegar = mysqli_fetch_assoc($pegarRes);
$stmt = mysqli_prepare($connect,"SELECT p.id, p.quantidade, p.prioridade, p.data_pedido, c.nome AS cliente_nome, c.endereco 
    FROM pedidos p
    JOIN clientes as c ON p.id_cliente = c.id_cliente
    WHERE p.status = 'pendente'
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_assoc($result)

?>

<!DOCTYPE html>
<html lang="pt-ao">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/lista_pedidos.css">
    <link rel="stylesheet" href="../icon/font-awesome/css/all.min.css">
</head>
<body>
    <div class="main">
        <header class="header">
            <div class="title">
                <span>Água</span><i class="fal fa-map-marker-plus"></i>
            </div>
            <nav class="h">

                <a href="perfil/perfil.php" class="fotoU"><img src="../uploads/<?php echo htmlspecialchars($pegar['foto_motorista']) ?>" alt=""></a>
            </nav>
        </header>
        <div class="content">
        <div class="container">
        <h2>Pedidos Pendentes</h2>
        
        <div class="lista-pedidos">
            <?php if (empty($pedidos)) : ?>
                <p>Nenhum pedido pendente no momento.</p>
            <?php else : ?>
                <?php while ($pedidos = mysqli_fetch_assoc($result)) : ?>
                    <div class="pedido-card">
                        <div class="pedido-header">
                            <h3>Pedido #<?= $pedidos['id'] ?></h3>
                            <span class="prioridade <?= $pedidos['prioridade'] ?>">
                                <?= ucfirst($pedidos['prioridade']) ?>
                            </span>
                        </div>
                        
                        <div class="pedido-info">
                            <p><strong>Cliente:</strong> <?= htmlspecialchars($pedidos['cliente_nome']) ?></p>
                            <p><strong>Endereço:</strong> <?= htmlspecialchars($pedidos['endereco']) ?></p>
                            <p><strong>Quantidade:</strong> <?= $pedidos['quantidade'] ?> litros</p>
                            <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedidos['data_pedido'])) ?></p>
                        </div>

                        <button class="btn-aceitar" data-id-pedido="<?= $pedidos['id'] ?>">
                            Aceitar Pedido
                        </button>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
            <a href="log/logout.php">sair</a>
            Aqui aparecerá os Abastecimentos que o Motorista terá de fazer
        </div>
        <footer class="footer">
            <nav class="menu">
                <a href="#">
                    <i class="fa fa-tint"></i>
                    <span>Abastecimentos</span> 
                </a>
                <a href="#">
                    <i class="fa fa-list-alt"></i>
                    <span>Lista de Pedidos</span> 
                </a>
                <a href="#">
                    <i class="fa fa-map"></i>
                    <span>Mapa em Tempo Real até ao destino</span> 
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
        // JavaScript para aceitar pedidos
        document.querySelectorAll('.btn-aceitar').forEach(btn => {
            btn.addEventListener('click', async () => {
                const pedidoId = btn.getAttribute('data-id-pedido');
                alert(pedidoId)
                const resposta = await fetch('aceitar_pedido.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_pedido: pedidoId })
                });

                const resultado = await resposta.json();
                if (resposta.ok) {
                    alert('Pedido aceito com sucesso!');
                    window.location.reload(); // Atualiza a página
                } else {
                    alert('Erro ao aceitar o pedido: ' + resultado.mensagem);
                }
            });
        });
        </script>
</body>
</html>