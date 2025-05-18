<?php
// Configuración del correo
$to = "22690208@tecvalles.mx"; // Reemplaza con el correo al que deseas enviar
$subject = "Probando correo desde PHP";
$message = "Este es un mensaje enviado desde tu página web.";
$headers = "From: julianrios405@gmail.com" . "\r\n" .
           "Reply-To: julianrios405@gmail.com" . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

// Enviar el correo
if (mail($to, $subject, $message, $headers)) {
    echo "Correo enviado exitosamente.";
} else {
    echo "Error al enviar el correo.";
}
?>

