<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
ini_set('session.cookie_secure', 1); // Apenas em HTTPS
ini_set('session.cookie_httponly', 1); // Impede acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Previne ataques CSRF
session_start();
include '../../config/bd.php';

if (!$connect) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $bairro = trim($_POST['bairro']);
    $capacidade_tanque = intval($_POST['cap_tanque']);
    $cpf_nif = !empty($_POST['cpf_nif']) ? trim($_POST['cpf_nif']) : null;
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $codigo_verificacao = rand(100000, 999999);

    // Verificar se o e-mail já existe
    $stmt = mysqli_prepare($connect, "SELECT id_cliente FROM clientes WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<a href="cadastrado.html">Voltar</a>';
        echo "E-mail já cadastrado.";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Inserir novo cliente
    $stmt = mysqli_prepare($connect, 
        "INSERT INTO clientes (nome, sobrenome, telefone, endereco, bairro, capacidade_tanque, cpf_nif, senha, data_cadastro, verificado, email, codigo_verificacao) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?, ?)"
    );

    if ($stmt) {
        // Corrigido o tipo para 10 parâmetros: s s s s s i s s s s
        mysqli_stmt_bind_param($stmt, "sssssissss", 
            $nome, 
            $sobrenome, 
            $telefone, 
            $endereco, 
            $bairro, 
            $capacidade_tanque, 
            $cpf_nif, 
            $senha, 
            $email, 
            $codigo_verificacao
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['email'] = $email;
            $_SESSION['codigo_verificacao'] = $codigo_verificacao;

            require_once 'envia_email.php';    
            if (enviarEmailVerificacao($email, $codigo_verificacao)) {
                header("Location: verificar.php");
                exit();
            } else {
                echo '<a href="cadastrado.html">Voltar</a>';
                die("Erro ao enviar o e-mail de verificação. Tente novamente.");
            }
        } else {
            echo '<a href="cadastrado.html">Voltar</a>';
            echo "Erro ao cadastrar usuário: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo '<a href="cadastrado.html">Voltar</a>';
        echo "Erro ao preparar a consulta: " . mysqli_error($connect);
    }
}
?>