<?php
session_start();
include_once "base_de_datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["contraseña"];

    $sqlUsuarios = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmtUsuarios = $pdo->prepare($sqlUsuarios);
    $stmtUsuarios->execute([":correo" => $correo]);
    $usuario = $stmtUsuarios->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $sqlEmpresas = "SELECT rfc, nombre, correo, direccion, contrasena FROM empresas WHERE correo = :correo";
        $stmtEmpresas = $pdo->prepare($sqlEmpresas);
        $stmtEmpresas->execute([":correo" => $correo]);
        $usuario = $stmtEmpresas->fetch(PDO::FETCH_ASSOC);
        $tipo = "empresa";
    } else {
        $tipo = "usuario";
    }

    if ($usuario && password_verify($password, $usuario["contrasena"])) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION["tipo"] = $tipo;
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
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login_2.css">

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
