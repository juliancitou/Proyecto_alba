<?php
session_start();
include_once "base_de_datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // Buscar en usuarios
    $sqlUsuarios = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmtUsuarios = $pdo->prepare($sqlUsuarios);
    $stmtUsuarios->execute([":correo" => $correo]);
    $usuario = $stmtUsuarios->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $sqlEmpresas = "SELECT rfc, nombre, correo, direccion, password FROM empresas WHERE correo = :correo";
        $stmtEmpresas = $pdo->prepare($sqlEmpresas);
        $stmtEmpresas->execute([":correo" => $correo]);
        $usuario = $stmtEmpresas->fetch(PDO::FETCH_ASSOC);
        $tipo = "empresa";
    } else {
        $tipo = "usuario";
    }
    
    // Validar contraseña
    if ($usuario && password_verify($password, $usuario["password"])) {
        $_SESSION["usuario"] = $usuario;
        $_SESSION["tipo"] = $tipo;

        header("Location: bienvenida.php");
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
    <title>Iniciar Sesión</title>
    <style>
        /* Estilos globales */
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #A6A6A6, #4A90E2); /* Fondo difuminado */
            color: #222626;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Contenedor centrado */
        .container {
            background-color: #ffffff; /* Fondo blanco del formulario */
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #222626; /* Título en color oscuro */
        }

        h2 {
            margin-top: 20px;
            font-size: 18px;
            color: #222626; /* Texto para el enlace a registrarse */
        }

        form {
            margin: 0;
        }

        /* Campos de entrada */
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #CCCCCC;
            border-radius: 8px;
            background-color: #ffffff;
            color: #222626;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px; /* Espaciado entre campos */
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #52D5F2;
            box-shadow: 0px 0px 5px #52D5F2;
        }

        /* Botón de inicio de sesión */
        input[type="submit"] {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            background-color: #F28D52; /* Color del botón */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #52D5F2; /* Hover en el botón */
            transform: scale(1.03); /* Ligera ampliación al pasar el cursor */
        }

        hr {
            border: 0;
            border-top: 1px solid #F27B50; /* Línea separadora */
            margin: 20px 0;
        }

        /* Mensajes */
        .error {
            color: #F27B50; /* Error en color cálido */
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Botón Regresar */
        .btn-regresar {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #F28D52;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-regresar:hover {
            background-color: #52D5F2;
        }
    </style>
</head>

<body>

    

    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form method="POST" action="login.php">
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Iniciar Sesión">
        </form>

        <hr>
        <h2>¿No tienes una cuenta?</h2>
        <form method="GET" action="seleccionarRegistro.php">
            <input type="submit" value="Registrarse">
        </form>
    </div>
</body>

</html>


