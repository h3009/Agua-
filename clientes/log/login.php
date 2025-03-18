<?php
session_start(); // Inicia a sessão
$error = isset($_GET['error'])?$_GET['error']:0;
$showError = '';
switch ($error) {
    case '1':
        $showError = '<div  class="error">Email Inválido</div>';
    break;
    case '2':
        $showError = '<div  class="error">Senha Inválida</div>';
    break;
    
    default:
        # code...
    break;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Seguro</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="../../icon/font-awesome/css/all.min.css">
</head>
<body>
    <div class="main">
        <form id="loginForm" method="POST" action="processa_login.php">
            <p>Acessar Conta</p>
            
            <!-- Campo honeypot para bots -->
            <div style="display: none;">
                <input type="text" name="honeypot" id="honeypot">
            </div>

            <div class="input-group">
                <i class="fa fa-user-secret icon"></i>
                <input type="text" name="identificador" id="identificador" 
                       placeholder="Email" required
                       autocomplete="username" minlength="3" maxlength="50">
            </div>

            <div class="input-group">
                <i class="fa fa-lock icon"></i>
                <input type="password" name="senha" id="senha" 
                       placeholder="Senha" required
                       autocomplete="current-password" minlength="8" maxlength="100">
                <i class="fa fa-eye toggle-password icon" onclick="togglePassword('senha')"></i>
            </div>

            <!-- Verificação em 2 etapas -->
            <div class="input-group" style="border: none; justify-content: flex-start; gap: 10px;">
                <input type="checkbox" name="2fa" id="2fa" style="width: auto;">
                <label for="2fa" style="color: #666; font-size: 0.9em;">
                    Usar Verificação em Duas Etapas
                </label>
            </div>

            <div class="buttons">
                <button type="submit" id="loginBtn">Acessar</button>
            </div>

            <?php
                if (isset($_GET['error'])>0) {
                    echo $showError;
                }
            ?>

            <div style="margin-top: 20px; text-align: center;">
                <a href="recuperar_senha.php" style="color: #f96969; text-decoration: none; font-size: 0.9em;">
                    Esqueceu a senha?
                </a>
                <br>
                <span style="color: #666; font-size: 0.9em;">
                    Não tem conta? <a href="../cadastro/cadastro.html" style="color: #f96969;">Registre-se</a>
                </span>
            </div>
        </form>
    </div>

    <script>
        // Prevenção de autocomplete em campos sensíveis
        document.getElementById('senha').autocomplete = 'new-password';

        // Verificação de honeypot
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const honeypot = document.getElementById('honeypot').value;
            if (honeypot !== '') {
                e.preventDefault();
                alert('Atividade suspeita detectada!');
                return;
            }
        });

        // Alternar visibilidade da senha
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>