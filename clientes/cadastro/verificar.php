<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
ini_set('session.cookie_secure', 1); // Apenas em HTTPS
ini_set('session.cookie_httponly', 1); // Impede acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Previne ataques CSRF
session_start(); // Inicia a sessão
include '../../config/bd.php'; // Conexão com o banco de dados

// Verifica se o usuário tem um e-mail e um código de verificação armazenados na sessão
if (!isset($_SESSION['email']) || !isset($_SESSION['codigo_verificacao'])) {
    header("Location: cadastrado.php"); // Se não tiver, redireciona para a página de cadastro
    exit();
}

// Se o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = trim($_POST['codigo']); // Obtém o código digitado pelo usuário
    
    // Verifica se o código digitado corresponde ao código armazenado na sessão
    if ($codigo == $_SESSION['codigo_verificacao']) {
        $email = $_SESSION['email'];
        
        // Atualiza o usuário no banco de dados como verificado
        $stmt = mysqli_prepare($connect, "UPDATE clientes SET verificado = 1 WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        unset($_SESSION['codigo_verificacao']); // Remove o código da sessão após a verificação
        header("Location: ../log/login.php"); // Redireciona para a página de login
        exit();
    } else {
        $erro = "Código incorreto. Tente novamente."; // Mensagem de erro se o código estiver errado
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Código</title>
    <link rel="stylesheet" href="css/cadastro.css"> <!-- Mantendo o mesmo CSS -->
    <link rel="stylesheet" href="../../icon/font-awesome/css/all.min.css">
</head>
<body>
    <div class="main">
        <form method="POST">
            <p>Verificação de Código</p>
            <p>Digite o código enviado para seu e-mail</p>
            
            <?php if (isset($erro)) echo "<p class='error'>$erro</p>"; ?> <!-- Mensagem de erro -->
            
            <div class="input-group">
                <i class="fa fa-key icon"></i>
                <input type="text" name="codigo" placeholder="Código de Verificação" required>
            </div>

            <div class="buttons">
                <button type="submit">Verificar</button>
            </div>
        </form>
    </div>
</body>
</html>
