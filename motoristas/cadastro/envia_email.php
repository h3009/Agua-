<?php
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Carregar variáveis do .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Verificar variáveis de ambiente
if (!isset($_ENV['EMAIL_USUARIO']) || !isset($_ENV['EMAIL_SENHA'])) {
    die("Erro: Configure EMAIL_USUARIO e EMAIL_SENHA no .env");
}

function enviarEmailVerificacao($emailDestinatario, $codigo) {
    // Validação rigorosa do e-mail
    if (!filter_var($emailDestinatario, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("E-mail inválido: $emailDestinatario");
    }

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USUARIO']; // E-mail do remetente (do .env)
        $mail->Password = $_ENV['EMAIL_SENHA'];    // Senha de app do remetente
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Remetente e destinatário
        $mail->setFrom($_ENV['EMAIL_USUARIO'], 'Água+'); // E-mail do .env como remetente
        $mail->addAddress($emailDestinatario); // E-mail do usuário como DESTINATÁRIO

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Código de Verificação';
        $mail->Body = "
            <h1>Olá!</h1>
            <p>Seu código de verificação é: <strong>$codigo</strong></p>
            <p>Use este código para validar sua conta.</p>
        ";

        // Remova o sleep(2) - não é necessário
        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail: " . $e->getMessage());
        return false;
    }
}
?>