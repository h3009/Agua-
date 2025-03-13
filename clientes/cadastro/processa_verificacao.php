<?php
session_start(); // Inicia a sessão
include 'database.php'; // Inclui a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $bairro = trim($_POST['bairro']);
    $capacidade_tanque = intval($_POST['capacidade_tanque']);
    $cpf_nif = !empty($_POST['cpf_nif']) ? trim($_POST['cpf_nif']) : NULL;
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Criptografa a senha
    $email = trim($_POST['email']);
    $codigo_verificacao = rand(100000, 999999); // Gera um código de verificação de 6 dígitos

    // Verifica se o e-mail já está cadastrado
    $stmt = mysqli_prepare($conn, "SELECT id_cliente FROM clientes WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "E-mail já cadastrado.";
        exit();
    }
    mysqli_stmt_close($stmt);

    // Insere o novo usuário no banco de dados
    $stmt = mysqli_prepare($conn, "INSERT INTO clientes (nome, telefone, endereco, bairro, capacidade_tanque, cpf_nif, senha, data_cadastro, verificado, email, codigo_verificacao) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 0, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssisssi", $nome, $telefone, $endereco, $bairro, $capacidade_tanque, $cpf_nif, $senha, $email, $codigo_verificacao);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Armazena o e-mail e o código na sessão para a verificação
    $_SESSION['email'] = $email;
    $_SESSION['codigo_verificacao'] = $codigo_verificacao;

    // Simulação de envio de e-mail (substitua por um sistema real de envio de e-mails)
    echo "Código de verificação: " . $codigo_verificacao;

    header("Location: verificar.php");
    exit();
}
?>
