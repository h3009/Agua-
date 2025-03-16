<?php
require 'envia_email.php';

$destino = 'agua.mais.as@gmail.com'; // Use um e-mail não-Gmail
$codigo = rand(10000, 99999);

if (enviarEmailVerificacao($destino, $codigo)) {
    echo "E-mail enviado! Verifique a caixa de spam.";
} else {
    echo "Falha no envio. Veja os logs de erro do PHP.";
}
?>