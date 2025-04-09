<?php
include_once "base_de_datos.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $direccion = $_POST["direccion"];
    $correo = $_POST["correo"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); 

    // Depuración para verificar los datos antes del INSERT
    var_dump([":nombre" => $nombre, ":apellido" => $apellido, ":telefono" => $telefono, ":direccion" => $direccion, ":correo" => $correo, ":password" => $password]);

    // Consulta para insertar los datos en la tabla `usuarios`
    $sql = "INSERT INTO usuarios (nombre, apellido, telefono, direccion, correo, password)
            VALUES (:nombre, :apellido, :telefono, :direccion, :correo, :password)";

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . implode(", ", $pdo->errorInfo()));
    }

    // Ejecutar la consulta
    try {
        $stmt->execute([":nombre" => $nombre, ":apellido" => $apellido, ":telefono" => $telefono, ":direccion" => $direccion, ":correo" => $correo, ":password" => $password]);

        // Verificar si la ejecución fue exitosa
        if ($stmt->rowCount() > 0) {
            echo "Usuario registrado correctamente.";
            // Redirigir al login después del registro
            header("Location: login.php?registro=exitoso");
            exit();
        } else {
            echo "Error al registrar el usuario.";
        }
    } catch (PDOException $e) {
        die("Error en la base de datos: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
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
        input[type="text"],
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

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #52D5F2;
            box-shadow: 0px 0px 5px #52D5F2;
        }

        /* Botón de registrar */
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
    <a href="seleccionarRegistro.php" class="btn-regresar">Volver</a>

    <div class="container">
        <h1>Registrar Usuario</h1>
        <form method="POST" action="registrarUsuario.php">
            <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
            <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>
            <input type="text" id="telefono" name="telefono" placeholder="Teléfono" required>
            <input type="text" id="direccion" name="direccion" placeholder="Dirección" required>
            <input type="email" id="correo" name="correo" placeholder="Correo" required>
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Registrar">
        </form>

        <hr>
        <h2>¿Ya tienes cuenta?</h2>
        <form method="GET" action="login.php">
            <input type="submit" value="Iniciar sesión">
        </form>
    </div>
</body>

</html>
