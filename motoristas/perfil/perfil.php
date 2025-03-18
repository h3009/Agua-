<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: log/login.php");
    exit();
}
$id_usuario = $_SESSION['user_id'] ; 
require_once '../../config/bd.php';
$sql = mysqli_query($connect,"SELECT * FROM motoristas WHERE id_motorista='$id_usuario'");
$pegar = mysqli_fetch_assoc($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil De Usuário</title>
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="../../icon/font-awesome/css/all.min.css">
</head>
<body>
    <div class="main">
        <header class="header">
            <div class="title">
                <span>Água</span><i class="fal fa-map-marker-plus"></i>
            </div>
            <nav class="h">
                <a href="#" class="fotoU"><img src="../../uploads/<?php echo htmlspecialchars($pegar['foto_motorista']) ?>" alt=""></a>
            </nav>
        </header>
        <div class="content">            
            <div class="section section-1">
                <div class="foto_perfil">
                    <img src="../../uploads/<?php echo htmlspecialchars($pegar['foto_motorista']) ?>" alt="">
                </div>
                <div class="u_v">
                    <a href="#" class="hide"><i class="fa fa-times-circle"></i> Verficar</a>
                    <a href="#"><i class="fa fa-check-circle"></i> Verficado</a>
                </div>
                <div class="nome_usuario">
                    <span><?php echo htmlspecialchars($pegar['nome']) ?></span>
                    &nbsp; 
                    <span><?php echo htmlspecialchars($pegar['sobrenome']) ?></span>
                </div>
            </div>
            <div class="section section-2">
                <h3>Dados Pessoais</h3>
                <div class="dados_usuario">
                    <?php
                    echo'
                        <div class="d_email"><i class="fal fa-envelope"></i>'.htmlspecialchars($pegar['email']).'</div>
                        <div class="d_num"><i class="fal fa-phone-alt"></i>'.htmlspecialchars($pegar['telefone']).'</div>
                        <div class="d_tc"><i class="fal fa-truck"></i>'.htmlspecialchars($pegar['tipo_cisterna']).'</div>
                        <div class="d_fc"><img src="../../uploads/'.htmlspecialchars($pegar['foto_cisterna']).'" alt=""></div>
                        <div class="d_loc"><i class="fal fa-map"></i>'.htmlspecialchars($pegar['endereco'].",".$pegar['bairro']).'</div>
                    ';
                    ?>
                </div>
            </div>
            <div class="section section-3">
                <div class="s-3">
                    <div class="cls">
                        <div class="cls-1">
                            <div class="cls-1-c">1</div>
                        </div>
                        <div class="cls-2">
                            <div class="cls-2-d">Abastecimento Concluído</div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <nav class="menu">
                <a href="../index.php">
                    <i class="fa fa-tint"></i>
                    <span>Solicitação de Abastecimento</span> 
                </a>
                <a href="#">
                    <i class="fa fa-list-alt"></i>
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
</body>
</html>