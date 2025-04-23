<?php
include 'base_de_datos.php'; // Asegúrate de que esta ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['telefono']);
    $edad = intval($_POST['edad']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $curp = strtoupper(trim($_POST['curp']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $rol = 'comprador';
    $foto_default = 'imagen_default.png';

    // Validaciones del lado servidor
    if (strlen($curp) !== 18) {
        echo "<script>alert('La CURP debe tener 18 caracteres');</script>";
        exit;
    }

    if ($edad < 0 || $edad > 120) {
        echo "<script>alert('Edad no válida');</script>";
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo inválido');</script>";
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        echo "<script>alert('El teléfono debe tener 10 dígitos');</script>";
        exit;
    }

    try {
        // Verificar si el correo o curp ya están registrados
        $consulta = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo OR curp = :curp");
        $consulta->execute(['correo' => $correo, 'curp' => $curp]);

        if ($consulta->rowCount() > 0) {
            echo "<script>alert('El correo o CURP ya están registrados');</script>";
        } else {
            // Insertar usuario
            $insertar = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, telefono, edad, correo, direccion, curp, contrasena, rol, foto_perfil)
VALUES (:nombre, :apellidos, :telefono, :edad, :correo, :direccion, :curp, :password, :rol, :foto)");

            $insertar->execute([
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'telefono' => $telefono,
                'edad' => $edad,
                'correo' => $correo,
                'direccion' => $direccion,
                'curp' => $curp,
                'password' => $password,
                'rol' => $rol,
                'foto' => $foto_default
            ]);
            echo "<script>alert('Usuario registrado exitosamente'); window.location.href='login.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al registrar usuario: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Revisa que el nombre del archivo CSS esté correcto -->
    <link rel="stylesheet" href="../CSS/registarUsuario.css">
</head>

<body>
    <div class="top-bar">
        <div class="logo-section">
            <img src="../imagenes/logo_empresa.jpg" alt="Logo">
            <h2>MiEmpresa</h2>
        </div>
        <a href="login.php" class="btn-volver">Volver</a>
    </div>

    <div class="container">
        <h1>Registrar Usuario</h1>
        <form method="POST" action="registrarUsuario.php">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="text" name="edad" placeholder="Edad" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="curp" placeholder="CURP" maxlength="18" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Registrar">
        </form>

        <hr>

        <h2>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></h2>
    </div>
</body>

</html>