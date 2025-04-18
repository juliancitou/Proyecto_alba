<?php
session_start();
include_once "base_de_datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["contraseña"];  // Cambié el nombre a 'contraseña' para que coincida con el formulario.

    // Buscar en usuarios
    $sqlUsuarios = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmtUsuarios = $pdo->prepare($sqlUsuarios);
    $stmtUsuarios->execute([":correo" => $correo]);
    $usuario = $stmtUsuarios->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        // Si no se encuentra el usuario, buscar en empresas
        $sqlEmpresas = "SELECT rfc, nombre, correo, direccion, contrasena FROM empresas WHERE correo = :correo";
        $stmtEmpresas = $pdo->prepare($sqlEmpresas);
        $stmtEmpresas->execute([":correo" => $correo]);
        $usuario = $stmtEmpresas->fetch(PDO::FETCH_ASSOC);
        $tipo = "empresa";
    } else {
        $tipo = "usuario";
    }

    // Validar contraseña
    if ($usuario && password_verify($password, $usuario["contrasena"])) {
        $_SESSION["usuario"] = $usuario;
        $_SESSION["tipo"] = $tipo;

        // Redirigir a index.php después de iniciar sesión
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='error'>Credenciales incorrectas.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body>
    <header>
        <a href="index.php" class="btn-regresar-header">Regresar</a> <!-- Botón de regresar -->
    </header>

    

    <main>
        <div class="container">
            <h1>Iniciar sesión</h1>
            <form action="login.php" method="POST">
                <input type="email" name="correo" placeholder="Correo electrónico" required>
                <input type="password" name="contraseña" placeholder="Contraseña" required>
                <input type="submit" value="Iniciar sesión">
            </form>
            <hr>
            <!-- Botón para registrarse -->
            <form action="../PHP/registrarUsuario.php" method="get">
                <button type="submit" class="btn-regresar">Registrarse</button>
            </form>
        </div>
    </main>
</body>

</html>
