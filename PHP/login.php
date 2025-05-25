<?php
session_start();
include_once "base_de_datos.php";

// Lógica de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"] ?? '';
    $password = $_POST["contraseña"] ?? ''; // Asegúrate que tu formulario HTML tenga 'name="contraseña"'

    if (empty($correo) || empty($password)) {
        echo "<div class='error'>Por favor, complete todos los campos.</div>";
        exit();
    }

    // Buscar primero en tabla de usuarios
    $sqlUsuarios = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmtUsuarios = $pdo->prepare($sqlUsuarios);
    $stmtUsuarios->execute([":correo" => $correo]);
    $usuario = $stmtUsuarios->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["contrasena"])) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION["tipo"] = "usuario";
        header("Location: index.php");
        exit();
    }

    // Si no se encontró o contraseña incorrecta, buscar en empresas
    $sqlEmpresas = "SELECT rfc, nombre, correo, direccion, contrasena FROM empresas WHERE correo = :correo";
    $stmtEmpresas = $pdo->prepare($sqlEmpresas);
    $stmtEmpresas->execute([":correo" => $correo]);
    $empresa = $stmtEmpresas->fetch(PDO::FETCH_ASSOC);

    if ($empresa && password_verify($password, $empresa["contrasena"])) {
        $_SESSION['usuario'] = $empresa;
        $_SESSION["tipo"] = "empresa";
        header("Location: index.php");
        exit();
    }

    // Si llega aquí, usuario o empresa no válida
    echo "<div class='error'>Credenciales incorrectas.</div>";
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login_2.css">
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }
    </script>
</head>

<body>
    <div class="top-bar">
        <div class="logo-section">
            <img src="../imagenes/logo_empresa.jpg" alt="Logo">
            <h2>MiEmpresa</h2>
        </div>
    </div>

    <a href="index.php" class="btn-volver">Volver al inicio</a>


    <div class="registro-text">Inicio de sesión</div>

    <div class="container">
        <form method="POST" action="login.php" autocomplete="off">
            <input type="email" name="correo" placeholder="Correo electrónico" required autocomplete="off">
            <input type="password" name="contraseña" placeholder="Contraseña" id="password" required autocomplete="new-password">

            <div class="show-password">
                <input type="checkbox" onclick="togglePassword()" id="togglePass">
                <label for="togglePass">Mostrar contraseña</label>
            </div>

            <input type="submit" value="Iniciar sesión">
        </form>

        <hr>

        <form action="registrarUsuario.php" method="get">
            <input type="submit" value="Registrarse">
        </form>
    </div>
</body>

</html>