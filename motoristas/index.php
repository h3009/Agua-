<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: log/login.php");
    exit();
}
$id_usuario = $_SESSION['user_id'] ; 
require_once '../config/bd.php';
$sql = mysqli_query($connect,"SELECT * FROM motoristas WHERE id_motorista='$id_usuario'");
$pegar = mysqli_fetch_assoc($sql);
?>

<!DOCTYPE html>
<html lang="pt-ao">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/estilo.css">
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
</body>
</html>