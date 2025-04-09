<?php
session_start();
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$isAdmin = $_SESSION["admin"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    <h1>Bienvenido</h1>
    <?php if ($isAdmin) { ?>
        <a href="administrar.php">Administrar Usuarios</a>
    <?php } ?>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>