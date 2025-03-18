<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: log/login.php");
    exit();
}
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

                <a href="#" class="fotoU"><img src="../../uploads/67d5a63ec33a3.jpg" alt=""></a>
            </nav>
        </header>
        <div class="content">            
            <div class="section section-1">
                <div class="foto_perfil">
                    <img src="../../uploads/67d5a63ec2f80.jpg" alt="">
                </div>
                <div class="u_v">
                    <a href="#" class="hide"><i class="fa fa-times-circle"></i> Verficar</a>
                    <a href="#"><i class="fa fa-check-circle"></i> Verficado</a>
                </div>
                <div class="nome_usuario">
                    <span>Harry Mário</span>
                </div>
            </div>
            <div class="section section-2">
                <h3>Dados Pessoais</h3>
                <div class="dados_usuario">
                    <div class="d_cap_tanque"><i class="fal fa-tint"></i> A capacidade do Meu Tanque é De 15.000 Listros</div>
                    <div class="d_email"><i class="fal fa-envelope"></i> harrymario30@gmail.com</div>
                    <div class="d_num"><i class="fal fa-phone-alt"></i> +244 999888777</div>
                    <div class="d_loc"><i class="fal fa-map"></i> Luanda, Cazenga,mabor</div>
                </div>
            </div>
            <div class="section section-3">
                <div class="s-3">
                    <div class="cls">
                        <div class="cls-1">
                            <div class="cls-1-c">1</div>
                        </div>
                        <div class="cls-2">
                            <div class="cls-2-d">Abastecimento Solicitado Concluído</div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <nav class="menu">
                <a href="../index.php">
                    <i class="fa fa-car-alt"></i>
                    <span>Solicitação de Abastecimento</span> 
                </a>
                <a href="#">
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
</body>
</html>