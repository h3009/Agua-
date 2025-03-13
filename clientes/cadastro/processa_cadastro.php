<?php
session_start(); // Inicia a sessão
include 'bd.php'; // Inclui a conexão com o banco de dados

if (!$connect) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $bairro = trim($_POST['bairro']);
    $capacidade_tanque = intval($_POST['cap_tanque']);
    $cpf_nif = !empty($_POST['cpf_nif']) ? trim($_POST['cpf_nif']) : NULL;
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografa a senha
    $email = trim($_POST['email']);
    $codigo_verificacao = rand(100000, 999999); // Gera um código de verificação de 6 dígitos

    // Verifica se o e-mail já está cadastrado
    $stmt = mysqli_prepare($connect, "SELECT id_cliente FROM clientes WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "E-mail já cadastrado.";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Insere o novo usuário no banco de dados
    $stmt = mysqli_prepare($connect, "INSERT INTO clientes (nome, telefone, endereco, bairro, capacidade_tanque, cpf_nif, senha, data_cadastro, verificado, email, codigo_verificacao) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssisssi", $nome, $telefone, $endereco, $bairro, $capacidade_tanque, $cpf_nif, $senha, $email, $codigo_verificacao);
    
    if (mysqli_stmt_execute($stmt)) {
        // Armazena o e-mail e o código na sessão para a verificação
        $_SESSION['email'] = $email;
        $_SESSION['codigo_verificacao'] = $codigo_verificacao;

        // Envio de e-mail
        require 'envia_email.php'; // Arquivo separado para enviar e-mails

        header("Location: verificar.php");
        exit();
    } else {
        echo "Erro ao cadastrar usuário.";
    }

    mysqli_stmt_close($stmt);
}
?>
