<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
