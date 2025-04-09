<?php
include_once "base_de_datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger los datos del formulario
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // Consulta para verificar si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":correo" => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validar si el usuario existe y la contraseña es correcta
    if ($usuario && password_verify($password, $usuario['password'])) {
        session_start(); // Inicia la sesión
        $_SESSION['usuario'] = $usuario;

        // Redirigir dependiendo del rol
        if ($usuario['admin']) {
            header("Location: bienvenida.php");
        } else {
            header("Location: bienvenida.php");
        }
        exit();
    } else {
        echo "Credenciales incorrectas";
    }
}
?>
