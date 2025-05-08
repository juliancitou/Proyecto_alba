<?php
session_start();
require 'conexion.php'; // Conexión a la BD

if (!isset($_SESSION['correo'])) {
    die("Debes iniciar sesión para contactar con el vendedor.");
}

$correo_comprador = $_SESSION['usuario']['correo'];
$id_propiedad = $_POST['id_propiedad'];
$mensaje = $_POST['mensaje'];

// Obtener correo del vendedor según id_propiedad
$sql = "SELECT u.correo AS correo_vendedor 
        FROM propiedades p
        JOIN usuarios u ON p.id_vendedor = u.id_usuario 
        WHERE p.id_propiedad = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_propiedad);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No se encontró al vendedor.");
}

$row = $result->fetch_assoc();
$correo_vendedor = $row['correo_vendedor'];

// Enviar el correo (usamos mail(), puedes usar PHPMailer si deseas algo más robusto)
$asunto = "Interesado en tu propiedad publicada";
$cuerpo = "Hola, un usuario está interesado en tu propiedad.\n\n";
$cuerpo .= "Correo del interesado: $correo_comprador\n";
$cuerpo .= "Mensaje: $mensaje";

$headers = "From: $correo_comprador";

if (mail($correo_vendedor, $asunto, $cuerpo, $headers)) {
    echo "Correo enviado correctamente.";
} else {
    echo "Error al enviar el correo.";
}
?>
