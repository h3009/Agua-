<?php
header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
ini_set('session.cookie_secure', 1); // Apenas em HTTPS
ini_set('session.cookie_httponly', 1); // Impede acesso via JavaScript
ini_set('session.cookie_samesite', 'Strict'); // Previne ataques CSRF
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

function enviarEmailVerificacao($emailDestinatario, $codigo) {
    // Carrega variáveis do .env
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    // Verifica credenciais
    if (!isset($_ENV['EMAIL_USUARIO']) || !isset($_ENV['EMAIL_SENHA'])) {
        throw new Exception("Configure EMAIL_USUARIO e EMAIL_SENHA no .env");
    }

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['EMAIL_USUARIO']; // E-mail do remetente (do .env)
        $mail->Password = $_ENV['EMAIL_SENHA'];   // Senha de aplicativo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Remetente e Destinatário
        $mail->setFrom($_ENV['EMAIL_USUARIO'], 'Água+');
        $mail->addAddress($emailDestinatario); // E-mail do usuário

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = 'Código de Verificação';
        $mail->Body = "
            <h1>Olá!</h1>
            <p>Seu código de verificação é: <strong>$codigo</strong></p>
            <p>Use-o para validar sua conta.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log(message: "Erro ao enviar e-mail: " . $e->getMessage());
        return false;
    }
}
?>