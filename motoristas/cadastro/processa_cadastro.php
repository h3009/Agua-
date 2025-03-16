<?php
session_start();
include '../../config/bd.php';

if (!$connect) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Função de sanitização melhorada
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    // Verificar campos obrigatórios de texto
    $requiredPost = ['nome', 'sobrenome', 'telefone', 'email', 'senha'];
    foreach ($requiredPost as $field) {
        if (empty($_POST[$field])) {
            echo '<a href="cadastro.html">Voltar</a><br>';
            die("O campo $field é obrigatório.");
        }
    }

    // Verificar uploads obrigatórios
    $requiredFiles = ['foto_cisterna', 'foto_motorista'];
    foreach ($requiredFiles as $fileField) {
        if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] === UPLOAD_ERR_NO_FILE) {
            echo '<a href="cadastro.html">Voltar</a><br>';
            die("O campo $fileField é obrigatório.");
        }
    }

    // Sanitização dos dados
    $nome = clean_input($_POST['nome']);
    $sobrenome = clean_input($_POST['sobrenome']);
    $telefone = clean_input($_POST['telefone']);
    
    // Validação de e-mail
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        echo '<a href="cadastro.html">Voltar</a><br>';
        die("Formato de e-mail inválido.");
    }

    $endereco = clean_input($_POST['endereco']);
    $bairro = clean_input($_POST['bairro']);
    $tipo_cisterna = clean_input($_POST['tipo_cisterna']);
    $placa_veiculo = clean_input($_POST['placa_veiculo']);
    $cpf_nif = !empty($_POST['nif']) ? clean_input($_POST['nif']) : null;
    $conta_bancaria = !empty($_POST['conta_bancaria']) ? clean_input($_POST['conta_bancaria']) : null;
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $codigo_verificacao = rand(100000, 999999);

    // Configurações de upload
    $uploadDir = '../../uploads/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            die("Falha ao criar diretório de uploads");
        }
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/avif', 'image/webp'];
    $maxSize = 10 * 1024 * 1024;

    // Função de upload aprimorada
    function processUpload($fileInput, $uploadDir, $allowedTypes, $maxSize) {
        if (!isset($_FILES[$fileInput])) {
            return ['error' => 'Nenhum arquivo enviado'];
        }

        $file = $_FILES[$fileInput];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'Erro no upload: ' . $file['error']];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return ['error' => 'Tipo de arquivo não permitido: ' . $mimeType];
        }

        if ($file['size'] > $maxSize) {
            return ['error' => 'Arquivo excede o tamanho máximo de 10MB'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid() . '.' . $extension;
        $destination = $uploadDir . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['error' => 'Falha ao mover arquivo'];
        }

        return ['success' => $newFilename];
    }

    // Processar uploads
    $foto_cisterna = processUpload('foto_cisterna', $uploadDir, $allowedTypes, $maxSize);
    $foto_motorista = processUpload('foto_motorista', $uploadDir, $allowedTypes, $maxSize);

    // Verificar erros de upload
    if (isset($foto_cisterna['error'])) {
        echo '<a href="cadastro.html">Voltar</a><br>';
        die("Erro na foto da cisterna: " . $foto_cisterna['error']);
    }
    if (isset($foto_motorista['error'])) {
        echo '<a href="cadastro.html">Voltar</a><br>';
        die("Erro na foto do motorista: " . $foto_motorista['error']);
    }

    // Verificar e-mail existente
    $stmt = mysqli_prepare($connect, "SELECT id_motorista FROM motoristas WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<a href="cadastro.html">Voltar</a><br>';
        die("Este e-mail já está cadastrado.");
    }
    mysqli_stmt_close($stmt);

    // Query corrigida
    $stmt = mysqli_prepare($connect, 
        "INSERT INTO motoristas 
        (nome, sobrenome, telefone, email, endereco, bairro, tipo_cisterna, 
        placa_veiculo, cpf_nif, conta_bancaria, foto_motorista, foto_cisterna, 
        senha, codigo_verificacao, data_cadastro, verificado) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0)"
    );

    if (!$stmt) {
        die("Erro na preparação da query: " . mysqli_error($connect));
    }

    // Bind parameters com tipos corretos
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssssssi",
        $nome,
        $sobrenome,
        $telefone,
        $email,
        $endereco,
        $bairro,
        $tipo_cisterna,
        $placa_veiculo,
        $cpf_nif,
        $conta_bancaria,
        $foto_motorista['success'],
        $foto_cisterna['success'],
        $senha,
        $codigo_verificacao
    );

    // Executar inserção
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['email'] = $email;
        $_SESSION['codigo_verificacao'] = $codigo_verificacao;

        // Enviar e-mail de verificação
        require 'envia_email.php';
        if (enviarEmailVerificacao($email, $codigo_verificacao)) {
            header("Location: verificar.php");
            exit();
        } else {
            echo '<a href="cadastro.html">Voltar</a><br>';
            die("Cadastro realizado, mas falha no envio do e-mail. Contate o suporte.");
        }
    } else {
        echo '<a href="cadastro.html">Voltar</a><br>';
        die("Erro no cadastro: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}
?>