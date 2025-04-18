<?php
include_once "base_de_datos.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST["nombre"];
    $rfc = $_POST["rfc"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT); // Hashea la contraseña

    // Consulta para insertar los datos en la tabla `empresas`
    $sql = "INSERT INTO empresas (nombre, rfc, direccion, telefono, correo, password)
            VALUES (:nombre, :rfc, :direccion, :telefono, :correo, :password)";

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta
    try {
        $stmt->execute([ 
            ":nombre" => $nombre,
            ":rfc" => $rfc,
            ":direccion" => $direccion,
            ":telefono" => $telefono,
            ":correo" => $correo,
            ":password" => $password
        ]);

        // Verificar si la ejecución fue exitosa
        if ($stmt->rowCount() > 0) {
            echo "Empresa registrada correctamente.";
            // Redirigir al login después del registro
            header("Location: login.php?registro=exitoso");
            exit();
        } else {
            echo "Error al registrar la empresa.";
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
    <title>Registrar Empresa</title>
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
            position: relative;
        }

        /* Botón en la parte superior izquierda */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #F28D52; /* Color del botón */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #52D5F2; /* Hover en el botón */
        }

        /* Contenedor centrado */
        .container {
            background-color: #ffffff; /* Fondo blanco del formulario */
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #222626; /* Título en color oscuro */
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

        /* Botón de registro */
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
    </style>
</head>

<body>
    <!-- Botón de regreso a seleccionarRegistro.php -->
    <a href="seleccionarRegistro.php">
        <button class="back-button">Volver</button>
    </a>

    <div class="container">
        <h1>Registrar Empresa</h1>
        <form method="POST" action="registrarEmpresa.php">
            <input type="text" name="nombre" placeholder="Nombre de la Empresa" required><br>
            <input type="text" name="rfc" placeholder="RFC" required><br>
            <input type="text" name="direccion" placeholder="Ubicación" required><br>
            <input type="text" name="telefono" placeholder="Teléfono"><br>
            <input type="email" name="correo" placeholder="Correo" required><br>
            <input type="password" name="password" placeholder="Contraseña" required><br>
            <input type="submit" value="Registrar">
        </form>
    </div>
</body>

</html>
