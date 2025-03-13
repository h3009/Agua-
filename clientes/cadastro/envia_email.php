<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

function enviarEmailVerificacao($email, $codigo_verificacao) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (exemplo: Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'seuemail@gmail.com'; // Seu e-mail SMTP
        $mail->Password = 'suasenha'; // Sua senha ou senha de aplicativo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remetente e destinatário
        $mail->setFrom('seuemail@gmail.com', 'Nome do Sistema');
        $mail->addAddress($email);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Código de Verificação';
        $mail->Body    = "
            <h3>Seu código de verificação é:</h3>
            <p style='font-size: 18px; font-weight: bold;'>$codigo_verificacao</p>
            <p>Insira este código no site para ativar sua conta.</p>
        ";

        // Envia o e-mail
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
