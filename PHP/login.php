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
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #fff;
        }

        .top-bar {
            background-color:rgb(0, 0, 0);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
        }

        .top-bar .logo-section {
            display: flex;
            align-items: center;
        }

        .top-bar img {
            height: 40px;
            margin-right: 15px;
        }

        .top-bar h2 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .registro-text {
            text-align: center;
            margin-top: 30px;
            font-size: 28px;
            color: #222;
            font-weight: bold;
        }

        .btn-volver {
            margin: 20px 30px;
            padding: 10px 25px;
            background-color: #FFD966;
            color: #000;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-volver:hover {
            background-color: #F28D52;
            color: white;
        }

        .container {
            max-width: 400px;
            margin: auto;
            padding: 30px;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        form input[type="submit"] {
            background-color: #F28D52;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        form input[type="submit"]:hover {
            background-color: #FFD966;
            color: #000;
        }

        .error {
            text-align: center;
            color: red;
        }

        .show-password {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .show-password input[type="checkbox"] {
            transform: scale(1.2);
        }
    </style>

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
